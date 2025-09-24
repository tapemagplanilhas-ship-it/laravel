<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        return Post::with('user')->orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'conteudo' => 'required|string|max:1000',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'conteudo' => $request->conteudo,
        ]);

        return response()->json($post, 201);
    }
}
