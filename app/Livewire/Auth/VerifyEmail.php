<?php

namespace App\Livewire\Auth;

use App\Mail\VerifyEmailMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.auth')]
class VerifyEmail extends Component
{
    public $email, $userData;
    public function mount($email)
    {
        $this->email = decrypt($email);
        $this->userData = User::where('email', $this->email)->first();
        
        if ($this->userData->hasVerifiedEmail()) {
            return;
        }

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $this->userData->id,
                'hash' => sha1($this->userData->email),
            ]
        );

        Mail::to($this->userData->email)->send(
            new VerifyEmailMail($url, $this->userData->name)
        );
        $this->dispatch('alert-success', 'A verification link has been sent to your email address.');

    }
    public function sendVerification()
    {
        $user = User::where('email', $this->email)->first();

        if ($user->hasVerifiedEmail()) {
            return;
        }

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        Mail::to($user->email)->send(
            new VerifyEmailMail($url, $user->name)
        );

        $this->dispatch('alert-success', 'A new verification link has been sent to your email address.');
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
