<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    protected $table = 'postagens'; // garante que a tabela correta seja usada

    protected $fillable = ['user_id', 'conteudo'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
