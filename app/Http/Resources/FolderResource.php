<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'size' => round(File::where('folder_id', $this->id)->sum('size') / 1024, 2) . ' МБайт',
            'files' => FileResource::collection(File::where('folder_id', $this->id)->get()),
        ];
    }
}
