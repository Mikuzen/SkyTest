<?php

namespace App\Repositories\Interfaces;

use App\Http\Resources\UserResource;
use App\Models\Folder;

interface FileRepositoryInterface {

    /**
     * @param Folder $folder
     * @return UserResource
     */
    public function getFilesByUser($folder);

    /**
     * @param int $folder
     * @return UserResource
     */
    public function getFilesByFolder($folder);
}
