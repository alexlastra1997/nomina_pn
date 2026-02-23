<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'email' => ['required','email','max:180','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Auto login después de registrar (opcional)
        $request->session()->regenerate();
        $request->session()->put('auth_ok', true);
        $request->session()->put('auth_user_id', $user->id);
        $request->session()->put('auth_name', $user->name);

        return redirect()->route('admin.form.index')->with('success', 'Cuenta creada. Bienvenido.');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return back()->with('error', 'Credenciales incorrectas.')->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('auth_ok', true);
        $request->session()->put('auth_user_id', $user->id);
        $request->session()->put('auth_name', $user->name);

        return redirect()->route('admin.form.index')->with('success', 'Sesión iniciada.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['auth_ok','auth_user_id','auth_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.loginForm')->with('success', 'Sesión cerrada.');
    }
}