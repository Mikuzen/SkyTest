<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /**
     * @method \Laravel\Sanctum\NewAccessToken createToken()
     * @method UserFactory factory()
     * @method \Illuminate\Database\Eloquent\Relations\MorphMany tokens()
     */

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Аттрибуты для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'token'
    ];

    /**
     * Атрибуты, которые должны быть скрыты от сериализации.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Получение файлов пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Получение папок пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * Расчет размера всех файлов пользователя
     *
     * @return float
     */
    public function sizeFiles()
    {
        return round(File::where('user_id', $this->id)->sum('size') / 1024, 2);
    }
}
