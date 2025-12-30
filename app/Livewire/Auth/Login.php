<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Login extends Component
{
    public $email, $password;

    public function login()
    {
        try {
            $this->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = \App\Models\User::where('email', $this->email)->first();

            if (!$user) {
                $this->dispatch('alert-error', 'Email not registered.');
                return;
            }

            if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
                session()->regenerate();
                return redirect()->route('dashboard');
            } else {
                $this->dispatch('alert-error', 'Invalid password.');
            }
        } catch (ValidationException $e) {
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
        return view('livewire.auth.login');
    }
}
