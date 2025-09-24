<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function registrar(Request $request) 
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $dados['password'] = bcrypt($dados['password']);
        $dados['picture'] = 'https://cdn0.iconfinder.com/data/icons/seo-web-4-1/128/Vigor_User-Avatar-Profile-Photo-02-1024.png';
        $dados['status'] = 'active';
        $dados['enabled'] = true;

        $usuario = User::create($dados);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário registrado com sucesso.',
            'user' => $usuario,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }

    public function fotoUpload(Request $request)
{
    $request->validate([
        'picture' => 'required|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $usuario = $request->user();

    $path = $request->file('picture')->store('pictures', 'public');

    $usuario->update(['picture' => $path]);

    return response()->json([
        'message' => 'Foto enviada com sucesso.',
        // Aqui garantimos URL completa
        'picture_url' => url('storage/' . $path)
    ]);
}


    public function desativarConta(Request $request)
    {
        $usuario = $request->user();

        $usuario->update(['enabled' => false, 'status' => 'inactive']);

        return response()->json(['message' => 'Conta desativada com sucesso.']);
    }

    public function perfil(Request $request)
    {
        return response()->json($request->user());
    }

    public function editar(Request $request)
    {
        $usuario = $request->user();

        $dados = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        if (!empty($dados['password'])) {
            $dados['password'] = bcrypt($dados['password']);
        } else {
            unset($dados['password']);
        }

        $usuario->update($dados);

        return response()->json([
            'message' => 'Dados atualizados com sucesso.',
            'user' => $usuario
        ]);
    }
}
