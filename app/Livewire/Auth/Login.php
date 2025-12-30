<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

// UPDATE: Gunakan 'layouts.guest' bukan 'layouts.app' agar UI tidak rusak
#[Layout('layouts.guest')] 
class Login extends Component
{
    public $email, $password;
    public $remember = false; // Tambahkan properti remember me

    public function login()
    {
        try {
            // Validasi Input
            $this->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Cek apakah email ada di database
            $user = \App\Models\User::where('email', $this->email)->first();

            if (!$user) {
                // Dispatch alert error (ditangkap oleh script di layouts.guest)
                $this->dispatch('alert-error', 'Email belum terdaftar.');
                return;
            }

            // Proses Login
            if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                session()->regenerate();
                
                // Dispatch sukses (opsional, karena langsung redirect)
                $this->dispatch('alert-success', 'Login Berhasil!');
                
                return redirect()->route('dashboard');
            } else {
                $this->dispatch('alert-error', 'Password salah.');
            }
        } catch (ValidationException $e) {
            // Menangkap error validasi dan menampilkannya sebagai alert
            $this->dispatch('alert-error', collect($e->errors())->flatten()->first());
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('auth.login');
    }

    public function render()
    {
        // Pastikan path view sesuai dengan lokasi file yang dibuat di atas
        return view('livewire.auth.login');
    }
}