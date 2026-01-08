<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Profile extends Component
{
    public $userId, $userData, $name, $email, $country, $telephone, $password;

    public function mount($role, $id)
    {
        $this->userId = decrypt($id);
        $this->userData = User::find($this->userId);
        $this->name = $this->userData->name;
        $this->email = $this->userData->email;
        $this->country = $this->userData->country;
        $this->telephone = $this->userData->telephone;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->userId,
            'country' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::find($this->userId);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->country = $this->country;
        $user->telephone = $this->telephone;

        if (!empty($this->password)) {
            $user->password = bcrypt($this->password);
        }

        $user->save();

        return redirect()->route(Auth::user()->role . '.dashboard')->with('success-alert', 'Profile updated successfully.');
    }
    public function render()
    {
        return view('livewire.auth.profile');
    }
}
