<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
      
    $user= User::create([
      'name'=>$request->name,
      'email'=>$request->email,
      'password'=>Hash::make($request->password)

    ]);
     return $user;
    }

    public function login(Request $request)
    {
     // ✅ validation
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // 🔍 chercher utilisateur
    $user = User::where('email', $request->email)->first();

    // ❌ utilisateur inexistant ou mauvais mot de passe
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'error' => 'Email ou mot de passe incorrect'
        ], 401);
    }

    // ❌ pas admin
    if (!$user->is_admin) {
        return response()->json([
            'error' => 'Accès refusé (admin seulement)'
        ], 403);
    }

    // ✅ créer token
    $token = $user->createToken('admin_token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
    }
}
