<div>
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Chat') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('Manage your profile and account settings') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <!-- chatdesigne use -->
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg p-4 flex flex-col overflow-y-auto">
            <h2 class="text-xl font-bold mb-4 text-center border-b pb-2">Users</h2>

            @foreach ($users as $user)
            <div
                class="border-b py-3 text-center cursor-pointer hover:bg-gray-200 {{ $selectedUser && $selectedUser->id === $user->id ? 'bg-indigo-100' : '' }}"
                wire:click="selectUser({{ $user->id }})">
                <h3 class="text-md font-semibold">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
            @endforeach
        </div>

        <!-- Main Panel -->
        <div class="flex-1 flex flex-col h-full">
            <div class="bg-indigo-600 text-white text-lg font-semibold px-6 py-4">
                @if($selectedUser)
                Chat with {{ $selectedUser->name }}
                @else
                Select a user to start chat
                @endif
            </div>

            <div id="chat-box" class="flex-1 overflow-y-auto p-6 space-y-3 bg-gray-50">
                @foreach ($messages as $message)
                @if ($message->sender_id === Auth::id())
                <!-- Sent message -->
                <div class="flex justify-end">
                    <span class="bg-blue-500 text-white px-4 py-2 rounded-lg max-w-xs text-sm">
                        {{ $message->message }}
                    </span>
                </div>
                @else
                <!-- Received message -->
                <div class="flex justify-start">
                    <span class="bg-gray-300 text-gray-900 px-4 py-2 rounded-lg max-w-xs text-sm">
                        {{ $message->message }}
                    </span>
                </div>
                @endif
                @endforeach
            </div>

            <div id="typing-indicator" class="px-4 pb-1 text-xs text-gray-400"></div>


            <form wire:submit.prevent="submit" class="flex border-t px-6 py-4">
                <input
                    type="text"
                    wire:model.live="newMessage"
                    placeholder="Type your message..."
                    class="flex-1 border rounded-l-md px-4 py-2 focus:outline-none focus:ring focus:border-indigo-300"
                    @if(!$selectedUser) disabled @endif>
                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-r-md hover:bg-indigo-700"
                    @if(!$selectedUser) disabled @endif>
                    Send
                </button>
            </form>

        </div>
    </div>
</div>


<script>

document.addEventListener('livewire:initialized', () => {
    Livewire.on('userTyping', function (event) {
        if (window.Echo && window.Echo.private) {
            window.Echo.private(`chat.${event.selectedUserId}`)
                .whisper('typing', {
                    userId: event.userId,
                    userName: event.userName
                });
        }
    });

    if (window.Echo && window.Echo.private) {
        window.Echo.private(`chat.{{$loginId}}`)
            .listenForWhisper('typing', (event) => {
                const t = document.getElementById('typing-indicator');
                if (t) {
                    t.innerText = `${event.userName} is typing...`;
                    
                }
                setTimeout(() => {
                    if (t) {
                        t.innerText = '';
                    }
                }, 3000); // Clear typing indicator after 3 seconds
            });
    }
});




</script>