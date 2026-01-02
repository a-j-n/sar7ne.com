@guest
    <x-auth.login-required 
        :title="__('messages.sign_in_to_view_inbox') ?? 'Sign in to view your inbox'"
        :message="__('messages.login_required_inbox') ?? 'Your inbox contains private messages. Please sign in to access them.'"
        illustration="/illustrations/lock-mail.svg"
    />
@else
<div class="space-y-8" wire:ignore.self>
    <!-- Header Section -->
    <x-ui.card padding="p-6 md:p-8" class="relative overflow-hidden card-brand-gradient">
        <div class="absolute inset-0 bg-gradient-brand-glow opacity-5"></div>
        <div class="absolute -top-4 -right-4 h-20 w-20 rounded-full bg-neon-mint/20 opacity-60 glow-neon-mint"></div>
        
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-4">
                <div class="h-12 w-12 rounded-2xl bg-gradient-blue-mint flex items-center justify-center glow-neon-mint">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-black dark:text-slate-100">{{ __('messages.inbox') }}</h1>
                    <p class="text-base text-black/70 dark:text-slate-400 leading-relaxed">
                        {{ __('messages.inbox_subtitle') }}
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if($unreadCount > 0)
                    <span class="inline-flex items-center gap-2 rounded-full bg-brand-orange/15 px-3 py-2 text-sm font-semibold text-brand-orange shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-brand-orange animate-pulse"></span>
                        {{ __('messages.inbox_unread_prefix') }} {{ $unreadCount }} {{ __('messages.inbox_unread_suffix') }}
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 rounded-full bg-neon-mint/15 px-3 py-2 text-sm font-semibold text-emerald-700 dark:text-emerald-300 shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ __('messages.inbox_all_caught_up') }}
                    </span>
                @endif
                <a href="{{ route('profiles.show', auth()->user()) }}" class="inline-flex items-center gap-2 rounded-xl border border-white/60 bg-white/70 px-4 py-2 text-sm font-semibold text-slate-900 shadow-md transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-100">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 5v14M5 12h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    {{ __('messages.share_profile') }}
                </a>
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
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">{{ __('messages.inbox_empty_title') }}</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">{{ __('messages.inbox_empty_desc') }}</p>
                <x-ui.button variant="primary" size="sm" onclick="window.location.href='{{ route('profile') }}'">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                    </svg>
                    {{ __('messages.get_profile_link') }}
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
@endguest
