<div class="space-y-8" wire:ignore.self>
    <!-- Header Section -->
    <x-ui.card padding="p-8" class="relative overflow-hidden card-brand-gradient">
        <!-- Background decoration -->
        <div class="absolute inset-0 bg-gradient-brand-glow opacity-5"></div>
        <div class="absolute -top-4 -right-4 h-20 w-20 rounded-full bg-neon-mint/20 opacity-60 glow-neon-mint"></div>
        
        <div class="relative flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-gradient-blue-mint flex items-center justify-center glow-neon-mint">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Your Inbox</h1>
                <p class="text-base text-slate-600 dark:text-slate-400">
                    Anonymous messages you've received. 
                    @if($unreadCount > 0)
                        You have <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-brand-orange text-white glow-brand-orange">{{ $unreadCount }}</span> unread.
                    @else
                        <span class="text-neon-mint">All caught up!</span>
                    @endif
                </p>
            </div>
        </div>
    </x-ui.card>

    <!-- Messages Section -->
    <section class="space-y-6">
        @forelse ($messages as $message)
            <x-ui.card class="group hover:border-brand-orange/40 hover:shadow-lg transition-all duration-200 {{ $message->status === 'unread' ? 'border-brand-orange/30 bg-gradient-to-r from-brand-orange/5 to-transparent' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 space-y-3">
                        <!-- Message Content -->
                        <div class="relative">
                            @if($message->status === 'unread')
                                <div class="absolute -left-4 top-0 w-1 h-full bg-gradient-orange-pink rounded-full"></div>
                            @endif
                            <p class="whitespace-pre-line text-sm leading-relaxed text-slate-900 dark:text-slate-100 {{ $message->status === 'unread' ? 'font-medium' : '' }}">{{ $message->message_text }}</p>
                            
                            @if($message->image_path)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $message->image_path) }}" alt="Message image" class="max-w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity" onclick="openImageModal(this.src)">
                                </div>
                            @endif
                        </div>
                        
                        <!-- Message Meta -->
                        <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-1">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Received {{ $message->created_at->diffForHumans() }}
                            </span>
                            @if($message->is_public)
                                <span class="flex items-center gap-1 text-neon-mint">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Public
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Status Badge -->
                    <span class="inline-flex shrink-0 items-center rounded-full px-3 py-1 text-xs font-medium {{ $message->status === 'unread' ? 'bg-brand-orange/20 text-brand-orange border border-brand-orange/30' : 'bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-400' }}">
                        @if($message->status === 'unread')
                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2L3 7v11a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V7l-7-5z"/>
                            </svg>
                        @endif
                        {{ ucfirst($message->status) }}
                    </span>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-6 flex flex-wrap gap-3">
                    <x-ui.button 
                        variant="outline" 
                        size="sm" 
                        wire:click="togglePublic({{ $message->id }})"
                        class="text-xs"
                    >
                        @if($message->is_public)
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L8.464 8.464m1.414 1.414L12 12m-3.122-3.122L6.757 6.757m0 0L5.636 5.636m1.121 1.121L9.88 9.88"/>
                            </svg>
                            {{ __('messages.make_private') }}
                        @else
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ __('messages.make_public') }}
                        @endif
                    </x-ui.button>
                    
                    @if ($message->status === 'unread')
                        <x-ui.button 
                            variant="mint" 
                            size="sm" 
                            wire:click="markRead({{ $message->id }})"
                            class="text-xs"
                        >
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Mark as read
                        </x-ui.button>
                    @else
                        <x-ui.button 
                            variant="ghost" 
                            size="sm" 
                            wire:click="markUnread({{ $message->id }})"
                            class="text-xs"
                        >
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Mark unread
                        </x-ui.button>
                    @endif
                    
                    <x-ui.button 
                        variant="danger" 
                        size="sm" 
                        wire:click="destroy({{ $message->id }})"
                        class="text-xs"
                        onclick="return confirm('Are you sure you want to delete this message?')"
                    >
                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </x-ui.button>
                </div>
            </x-ui.card>
        @empty
            <x-ui.card class="text-center" padding="p-12">
                <div class="mx-auto h-16 w-16 rounded-full bg-slate-100 dark:bg-white/10 flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Your inbox is waiting</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Share your profile link to start collecting anonymous messages.</p>
                <x-ui.button variant="primary" size="sm" onclick="window.location.href='{{ route('profile') }}'">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                    </svg>
                    Get Your Profile Link
                </x-ui.button>
            </x-ui.card>
        @endforelse
    </section>

    <!-- Pagination -->
    @if($messages->hasPages())
        <div class="flex justify-center">
            {{ $messages->links() }}
        </div>
    @endif

    <!-- Image Modal -->
    <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="relative max-w-4xl max-h-full p-4">
            <button id="closeModal" class="absolute -top-4 -right-4 bg-white text-black rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-200 z-10">
                ×
            </button>
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <script>
        function openImageModal(src) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = src;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const imageModal = document.getElementById('imageModal');
            const closeModal = document.getElementById('closeModal');

            // Close modal handlers
            const closeModalHandler = () => {
                imageModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            };

            closeModal?.addEventListener('click', closeModalHandler);
            
            imageModal.addEventListener('click', function(e) {
                if (e.target === imageModal) {
                    closeModalHandler();
                }
            });

            // ESC key to close
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !imageModal.classList.contains('hidden')) {
                    closeModalHandler();
                }
            });
        });
    </script>
</div>
