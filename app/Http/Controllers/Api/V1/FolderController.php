<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FolderRequest;
use App\Http\Resources\FolderResource;
use App\Models\Folder;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    /**
     * Создание папки
     *
     * @param FolderRequest $request
     * @return FolderResource
     */

    public function create(FolderRequest $request) {

        $folder = Folder::create([
            'user_id' => Auth::id(),
            'title' => $request->title
        ]);

        return new FolderResource($folder);
    }
}
