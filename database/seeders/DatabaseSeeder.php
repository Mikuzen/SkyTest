<?php

namespace Database\Seeders;

use App\Models\Folder;
use App\Models\User;
use App\Models\File;
use Database\Factories\FileFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory(10)->create();
         Folder::factory(4)->create();
         File::factory(10)->create();
    }
}
