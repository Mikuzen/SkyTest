<?php

namespace App\Http\Resources;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'disk' => $request->user()->sizeFiles() . '/ 100 МБайт',
            'folder' => $this->when(isset($request->folder),
                new FolderResource($request->folder)),
            'files' => $this->when(!isset($request->folder),
                FileResource::collection(File::where('user_id', $this->id)->get())),
        ];
    }
}
