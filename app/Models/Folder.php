<?php

namespace App\Models;

use Database\Factories\FolderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    /**
     * @method FolderFactory factory()
     */

    use HasFactory;

    /**
     * Аттрибуты для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
    ];

    /**
     * Получение файлов, которые принадлежат папке
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files() {
        return $this->hasMany(File::class);
    }

    /**
     * Получение пользователя, которому принадлежит папка
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
