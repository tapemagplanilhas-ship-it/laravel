<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('postagens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // usuário dono do post
            $table->text('conteudo'); // texto da postagem
            $table->timestamps(); // created_at e updated_at
        });
    }

    public function down(): void {
        Schema::dropIfExists('postagens');
    }
};
