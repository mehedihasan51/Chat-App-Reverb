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
    public $messages;

    public function mount()
    {
        if (Auth::check()) {
            $this->users = User::where('id', '!=', Auth::id())->get();
            $this->selectedUser = $this->users->first(); // Select the first user by default
            $this->loadMessages();
        } else {
            $this->users = collect();
        }
    }

    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->loadMessages();
    }


    public function loadMessages()
    {
        $this->messages = ChatMessage::where(function ($query) {
            $query->where('sender_id', Auth::id())
                ->orWhere('receiver_id', Auth::id());
        })->where(function ($query) {
            $query->where('sender_id', $this->selectedUser->id)
                ->orWhere('receiver_id', $this->selectedUser->id);
        })->get();
        $this->newMessage = '';
    }

    public function submit()
    {
        if (!$this->newMessage) {
            return;
        }
        $messages = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedUser->id,
            'message' => $this->newMessage,
        ]);

        $this->messages->push($messages);


        $this->newMessage = '';
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
