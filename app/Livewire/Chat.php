<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{

    public $users;
    public $selectedUser = null;

    public function mount()
    {
        if (Auth::check()) {
            $this->users = User::where('id', '!=', Auth::id())->get();
        } else {
            $this->users = collect();
        }
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
