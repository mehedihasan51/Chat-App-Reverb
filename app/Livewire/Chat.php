<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{

    public $users;
    public $selectedUser = null;
    public $newMessage;

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

    public function submit(){
        if(!$this->newMessage) {
            return;
        }
        ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedUser->id,
            'message' => $this->newMessage,
        ]); 

        $this->newMessage = '';
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
