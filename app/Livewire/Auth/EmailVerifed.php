<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
#[Layout('layouts.auth')]
class EmailVerifed extends Component
{
    public $userData;
    public function mount($id, $hash)
    {
        $this->userData = \App\Models\User::findOrFail($id);
        
        if (! hash_equals((string) $hash, sha1($this->userData->getEmailForVerification()))) {
            abort(403);
        }
        
        if ($this->userData->hasVerifiedEmail()) {
            return redirect('/');
        }
        
        $this->userData->markEmailAsVerified();

        $this->dispatch('alert-success', 'Your email has been verified successfully.');
    }
    public function render()
    {
        return view('livewire.auth.email-verifed');
    }
}
