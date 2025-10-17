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
                <h1 class="text-3xl font-bold tracking-tight text-black dark:text-slate-100">{{ __('messages.inbox') }}</h1>
                <p class="text-base text-black/70 dark:text-slate-400">
                    {{ __('messages.inbox_subtitle') }}
                    @if($unreadCount > 0)
                        {{ __('messages.inbox_unread_prefix') }} <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-brand-orange text-white glow-brand-orange">{{ $unreadCount }}</span> {{ __('messages.inbox_unread_suffix') }}
                    @else
                        <span class="text-neon-mint">{{ __('messages.inbox_all_caught_up') }}</span>
                    @endif
                </p>
            </div>
        </div>
    </x-ui.card>

    <!-- Messages Section -->
    <section class="space-y-6">
        @php($sorted = $messages->sortByDesc(fn($m) => $m->status === 'unread')->values())
        @forelse ($sorted as $index => $message)
            @include('messages.partials.card', ['message' => $message])
        @empty
            <x-ui.card class="text-center" padding="p-12">
                <div class="mx-auto h-16 w-16 rounded-full bg-slate-100 dark:bg-slate-800/60 flex items-center justify-center mb-4">
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
