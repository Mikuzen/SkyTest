<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::where('id', random_int(1, 3))->first();
        $folder = $user->id == 1 ? random_int(1, 4) : null;
        $mime = 'image/png';
        $title = fake()->name;
        $src = str_replace(' ', '_', $user->name .
            Carbon::now()->timestamp . $title . '.png');

        return [
            'user_id' => $user->id,
            'folder_id' => $folder,
            'src' => $src,
            'ext' => 'png',
            'title' => $title,
            'size' => random_int(100, 20000),
            'mime' => 'image/png',
        ];
    }
}
