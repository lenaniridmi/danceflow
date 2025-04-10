<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Добавляем импорт Http
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['message' => 'Login successful', 'user' => $user], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        Auth::login($user);

        return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
    }

    public function handleGoogleCallback(Request $request)
    {
        $credential = $request->input('credential');
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $credential,
        ])->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if ($response->successful()) {
            $googleUser = $response->json();
            $user = User::firstOrCreate(
                ['email' => $googleUser['email']],
                ['name' => $googleUser['name'], 'password' => bcrypt(uniqid())]
            );

            Auth::login($user);
            return redirect('/dashboard');
        }

        return redirect('/login')->with('error', 'Google login failed');
    }

    public function handleYandexCallback(Request $request)
    {
        $code = $request->input('code');
        $response = Http::asForm()->post('https://oauth.yandex.com/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => '1a559d3253024b65b5f46e22fe24c0ac',
            'client_secret' => '230198af8d0146c3a004071844676382',
        ]);

        if ($response->successful()) {
            $accessToken = $response->json()['access_token'];
            $userResponse = Http::withHeaders([
                'Authorization' => 'OAuth ' . $accessToken,
            ])->get('https://login.yandex.ru/info');

            if ($userResponse->successful()) {
                $yandexUser = $userResponse->json();
                $user = User::firstOrCreate(
                    ['email' => $yandexUser['default_email']],
                    ['name' => $yandexUser['real_name'], 'password' => bcrypt(uniqid())]
                );

                Auth::login($user);
                return redirect('/dashboard');
            }
        }

        return redirect('/login')->with('error', 'Yandex login failed');
    }

    public function handleMailruCallback(Request $request)
    {
        $code = $request->input('code');
        $response = Http::asForm()->post('https://oauth.mail.ru/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => 'b2f2212a2a7d4fb9b5ca2153ebbef3ff',
            'client_secret' => '506df04c22ea43098397d32abc5373f5',
            'redirect_uri' => 'http://localhost:8000/api/auth/mailru/callback',
        ]);

        if ($response->successful()) {
            $accessToken = $response->json()['access_token'];
            $userResponse = Http::get('https://oauth.mail.ru/userinfo', [
                'access_token' => $accessToken,
            ]);

            if ($userResponse->successful()) {
                $mailruUser = $userResponse->json();
                $user = User::firstOrCreate(
                    ['email' => $mailruUser['email']],
                    ['name' => $mailruUser['name'], 'password' => bcrypt(uniqid())]
                );

                Auth::login($user);
                return redirect('/dashboard');
            }
        }

        return redirect('/login')->with('error', 'Mail.ru login failed');
    }
}