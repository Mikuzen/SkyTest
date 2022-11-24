<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\FileStoreRequest;
use App\Http\Requests\RenameFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\Folder;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * @var FileRepositoryInterface
     */
    private $fileRepository;

    /**
     * FileController конструктор
     *
     * @param FileRepositoryInterface $fileRepository
     */
    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * Просмотр всех файлов пользователя или файлов конкретной папки пользователя
     *
     * @param Folder|null $folder передается id в адресной строке
     * @return \App\Http\Resources\UserResource|\Illuminate\Http\JsonResponse
     */

    public function index(Folder $folder = null)
    {
        if (isset($folder) && $folder->user_id != Auth::id()) {
            return response()->json(['message' => 'This not your folder'], 422);
        }

        return $this->fileRepository->getFilesByUser($folder);
    }

    /**
     * Сохраняет файл в сервисе, если он не является исполняемым
     * файлом .php и если не превышел лимит памяти в 100 Мбайт
     *
     * @param FileStoreRequest $request файлы передаются files[]
     * @param null $folder
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \League\Flysystem\FilesystemException
     */

    public function store(FileStoreRequest $request, $folder = null)
    {
        $folder = Folder::where('id',  $folder)->first();

        $filesList = [];
        $folders = $this->getFolders(Auth::id());

        $size = Auth::user()->sizeFiles();

        foreach ($request->file('files') as $file) {
            $metaData = $this->getMetaFile($file);

            if ($metaData['mime'] == 'text/x-php' || ((round($metaData['size'] / 1024, 2)) + $size) > 100) {
                return response()->json(['message' => 'You cannot upload this file']);
            }

            !Storage::disk('public')->has('files/' . Auth::id() . '/' . $folders['folder']) ?
                $this->createFolder($folders['folder']) : '';

            Storage::putFileAs($folders['folderOriginal'], $file, $metaData['src']);

            $filesList[] = File::create([
                'user_id' => Auth::id(),
                'folder_id' => isset($folder) ? $folder : null,
                'src' => $metaData['src'],
                'ext' => $metaData['ext'],
                'title' => $metaData['title'],
                'size' => $metaData['size'],
                'mime' => $metaData['mime'],
            ]);

        }
        if ($filesList) {
            return FileResource::collection($filesList);
        }

        return response()->json(['message' => 'File has not uploaded'], 315);
    }

    /**
     * Переименование файла
     *
     * @param File $file передается id в адресной строке
     * @param RenameFileRequest $request передается title в качестве нового имени файла
     * @return FileResource|\Illuminate\Http\JsonResponse
     */

    public function rename(File $file, RenameFileRequest $request)
    {
        if ($file->user_id == Auth::id()) {
            $file->title = $request->title;
            $file->save();

            return new FileResource($file);
        }

        return response()->json(['message' => 'You can not rename someone file'], 423);
    }

    /**
     * Удаление файла
     *
     * @param File $file передается id в адресной строке
     * @return \Illuminate\Http\JsonResponse
     */

    public function delete(File $file)
    {
        if ($file->user_id == Auth::id()) {

            \Illuminate\Support\Facades\File::delete(storage_path('app/public/files/' . $file->user_id .
                '/' . $file->created_at->toDateString() . '/' . $file->src));

            $file->delete();

            return response()->json(['message' => 'File has been deleted'], 200);
        }

        return response()->json(['message' => 'You can not delete someone file'], 423);
    }

    /**
     * Скачивание файла
     *
     * @param File $file передается id в адресной строке
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */

    public function download(File $file)
    {
        if ($file->user_id != Auth::id()) {
            return response()->json(['message' => 'You can not download this file']);
        }

        $path = storage_path('app/public/files/' . Auth::id() . '/'
            . $file->created_at->toDateString(). '/' . $file->src);

        return response()->download($path, $file->title . $file->ext);
    }

    /**
     * Метод который создает папку пользователя в системе
     *
     * @param $folder
     */

    private function createFolder($folder)
    {
        Storage::disk('public')->makeDirectory('files/' . Auth::id()
            . '/' . $folder);
    }

    /**
     * Генерирует имена папок для сохранения файлов
     *
     * @param $user передается id пользователя
     * @return array
     */

    private function getFolders($user)
    {
        $folder = Carbon::now()->toDateString();
        $folderOriginal = '/public/files/' . $user . '/' . $folder;

        return [
            'folder' => $folder,
            'folderOriginal' => $folderOriginal,
        ];
    }

    /**
     * Формируется массив мета данных файла для сохранения в бд
     *
     * @param $file передается файл
     * @return array
     */
    private function getMetaFile($file)
    {
        $mime = $file->getMimeType();
        $title = strstr($file->getClientOriginalName(), '.', true);
        $size = round($file->getSize() / 1024);
        $ext = $file->extension();
        $src = str_replace(' ', '_', Auth::user()->name . Carbon::now()->timestamp . $title . '.' . $ext);

        return [
            'src' => $src,
            'ext' => $ext,
            'title' => $title,
            'size' => $size,
            'mime' => $mime,
        ];
    }
}
