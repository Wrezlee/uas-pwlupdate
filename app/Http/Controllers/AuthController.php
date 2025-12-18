<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Tampilkan form login (HANYA untuk pengelola)
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    // Proses login (HANYA untuk pengelola)
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->route('dashboard')
                ->with('success', 'Login berhasil! Selamat datang ' . auth()->user()->name);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->except('password'));
    }

    // Tampilkan form register (HANYA untuk pengelola baru)
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    // Proses register (HANYA untuk pengelola baru) - TANPA usertype
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:3',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 3 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        
        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang ' . $user->name);
    }

    // Logout (untuk pengelola)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('welcome')
            ->with('status', 'Anda telah berhasil logout.');
    }

    // Halaman welcome/landing untuk pembeli
    public function welcome()
    {
        return view('welcome');
    }

    // Aksi untuk pembeli masuk ke halaman pemesanan
    public function sebagaiPembeli()
    {
        // Set session bahwa user adalah pembeli (tanpa login)
        session(['user_type' => 'pembeli']);
        session(['user_name' => 'Pembeli']);
        
        // Redirect ke halaman pembuatan pesanan untuk pembeli
        return redirect()->route('pembeli.pesanan.create')
            ->with('info', 'Selamat berbelanja! Anda masuk sebagai pembeli.');
    }
}