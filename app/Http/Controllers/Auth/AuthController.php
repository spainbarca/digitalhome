<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    // ─── Mostrar formulario de login ───────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('sign-in');
    }

    // ─── Login con email y contraseña ──────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no coinciden con nuestros registros.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.dashboard.ecommerce'));
    }

    // ─── Logout ────────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ─── Redirigir a Google ────────────────────────────────────────────────
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // ─── Callback de Google ────────────────────────────────────────────────
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'No se pudo autenticar con Google. Intenta nuevamente.',
            ]);
        }

        // Buscar user existente por provider_id o email
        $user = User::where('provider', 'google')
                    ->where('provider_id', $googleUser->getId())
                    ->first();

        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        if ($user) {
            // Actualizar datos de Google
            $user->update([
                'provider'       => 'google',
                'provider_id'    => $googleUser->getId(),
                'provider_token' => $googleUser->token,
                'avatar_url'     => $googleUser->getAvatar(),
            ]);

            // Sincronizar avatar con persona si existe
            if ($user->persona) {
                $user->persona->update(['avatar_url' => $googleUser->getAvatar()]);
            }
        } else {
            // Crear persona nueva
            $persona = Persona::create([
                'id'      => Str::uuid(),
                'nombres' => $googleUser->getName(),
                'email'   => $googleUser->getEmail(),
                'avatar_url' => $googleUser->getAvatar(),
                'activo'  => true,
            ]);

            // Crear user vinculado
            $user = User::create([
                'id'             => Str::uuid(),
                'persona_id'     => $persona->id,
                'name'           => $googleUser->getName(),
                'email'          => $googleUser->getEmail(),
                'provider'       => 'google',
                'provider_id'    => $googleUser->getId(),
                'provider_token' => $googleUser->token,
                'avatar_url'     => $googleUser->getAvatar(),
                'password'       => Hash::make(Str::random(24)),
            ]);
        }

        Auth::login($user);

        return redirect()->intended(route('dashboard.dashboard.ecommerce'));
    }
}
