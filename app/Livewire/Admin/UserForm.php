<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class UserForm extends Component
{

    public $name, $email, $telephone, $role, $idUser, $password;
    public function mount($id = null)
    {
        if ($id) {
            $this->idUser = $id;
            $userData = \App\Models\User::find($this->idUser);
            $this->name = $userData->name;
            $this->email = $userData->email;
            $this->telephone = $userData->telephone;    
            $this->role = $userData->role;
        }
    }

    public function save()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$this->idUser,
                'telephone' => 'required|string|max:20',
                'role' => 'required|in:admin,doctor,therapist',
                'password' => $this->idUser ? 'nullable|min:6' : 'required|min:6',
            ]);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'telephone' => $this->telephone,
                'role' => $this->role,
            ];
            if ($this->password) {
                $data['password'] = \Illuminate\Support\Facades\Hash::make($this->password);
            }
            \App\Models\User::updateOrCreate(
                ['id' => $this->idUser],
                $data
            );
            if ($this->idUser) {
                return redirect()->route('admin.user')->with('success-alert', 'User updated successfully.');
            }
            return redirect()->route('admin.user')->with('success-alert', 'User created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error-alert', collect($e->errors())->flatten()->first());
        }
    }
    public function render()
    {
        return view('livewire.admin.masterdata.user.form');
    }
}
