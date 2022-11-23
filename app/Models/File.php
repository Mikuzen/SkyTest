<?php

namespace App\Models;

use Database\Factories\FileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * @method FileFactory factory()
     */
    use HasFactory;

    /**
     * Аттрибуты для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'folder_id',
        'src',
        'ext',
        'title',
        'size',
        'mime',
    ];

    /**
     * Получение пользователя, которому принадлежит файл
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Получение папки, в которой лежит файл
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function folder() {
        return $this->belongsTo(Folder::class);
    }
}
