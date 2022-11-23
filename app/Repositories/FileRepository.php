<?php

namespace App\Repositories;

use App\Http\Resources\FileResource;
use App\Http\Resources\UserResource;
use App\Models\File;
use App\Models\Folder;
use App\Repositories\Interfaces\FileRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class FileRepository implements FileRepositoryInterface
{
    /**
     * @param Folder $folder
     * @return UserResource
     */

    public function getFilesByUser($folder)
    {
       return isset($folder) ? $this->getFilesByFolder($folder->id) :
           new UserResource(Auth::user());
    }

    /**
     * @param int $folder
     * @return UserResource
     */

    public function getFilesByFolder($folder)
    {
        return new UserResource(Auth::user());
    }
}
