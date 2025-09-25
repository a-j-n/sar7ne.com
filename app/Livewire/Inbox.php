<?php

namespace App\Livewire;

use App\Models\Message;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Inbox extends Component
{
    use WithPagination;

    public int $unreadCount = 0;

    public function mount(): void
    {
        $this->unreadCount = Message::query()
            ->where('receiver_id', Auth::id())
            ->where('status', Message::STATUS_UNREAD)
            ->count();
    }

    public function markRead(int $id): void
    {
        $message = $this->findOwned($id);
        $message->markRead();
        $this->unreadCount = max(0, $this->unreadCount - 1);
    }

    public function markUnread(int $id): void
    {
        $message = $this->findOwned($id);
        $message->markUnread();
        $this->unreadCount++;
    }

    public function destroy(int $id): void
    {
        $message = $this->findOwned($id);
        $message->delete();
        session()->flash('status', 'Message deleted.');
    }

    public function togglePublic(int $id): void
    {
        $message = $this->findOwned($id);

        $user = Auth::user();
        if (! $user || ! $user->allow_public_messages) {
            session()->flash('errors', ['public' => __('messages.public_messages_disabled')]);

            return;
        }

        $message->forceFill(['is_public' => ! (bool) $message->is_public])->save();
        session()->flash('status', __('messages.message_privacy_updated'));
    }

    protected function findOwned(int $id): Message
    {
        $message = Message::query()->findOrFail($id);
        abort_if($message->receiver_id !== Auth::id(), 403);

        return $message;
    }

    public function getMessagesProperty(): LengthAwarePaginator
    {
        return Message::query()
            ->where('receiver_id', Auth::id())
            ->latest()
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire/pages/inbox', [
            'messages' => $this->messages,
            'unreadCount' => $this->unreadCount,
        ])->title('Inbox · sar7ne')
            ->with([
                'meta_description' => 'Your sar7ne inbox — view and manage anonymous messages you have received.',
                'og_type' => 'website',
                'canonical' => route('inbox'),
            ]);
    }
}
