<?php

namespace App\Livewire\Admin;

use App\Models\User as ModelsUser;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class User extends Component
{
    public $search;
    public function render()
    {
        
        return view('livewire.admin.masterdata.user.index',[
            'data' => ModelsUser::when($this->search, function($query){
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%')
                      ->orWhere('telephone', 'like', '%'.$this->search.'%');
            })->where('role', '!=', 'patient')->paginate(10),
        ]);
    }
}
