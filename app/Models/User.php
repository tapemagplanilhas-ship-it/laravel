<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Adicione esta linha
use Spatie\Permission\Traits\HasRoles; // Adicione esta linha

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable; // Adicione HasApiTokens e HasRoles aqui

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}