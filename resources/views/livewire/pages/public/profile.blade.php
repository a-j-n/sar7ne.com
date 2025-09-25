<div class="space-y-10" wire:ignore.self>
    @php
        $messageRoute = route('profiles.message', $user);
        if (request()->routeIs('profiles.show.subdomain')) {
            $messageRoute = route('profiles.message.subdomain', ['username' => $user->username]);
        }
    @endphp

        <section class="flex flex-col gap-6 rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 text-center shadow-xl md:flex-row md:items-center md:text-left">
            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }} avatar" class="mx-auto h-28 w-28 rounded-3xl object-cover md:mx-0 cursor-pointer hover:opacity-80 transition-opacity" data-profile-image />
            <div class="flex-1 space-y-2">
                <h1 class="text-3xl font-semibold text-slate-900 dark:text-slate-100">{{ "@".$user->username }}</h1>
                @if ($user->display_name)
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $user->display_name }}</p>
                @endif
                <p class="text-sm text-slate-600 dark:text-slate-300">{{ $user->bio ?? __('messages.drop_anonymous_message') }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('messages.messages_received', ['count' => $user->total_messages_count]) }} • {{ __('messages.powered_by', ['app' => config('app.name')]) }}</p>
                
                <!-- Share Profile Button -->
                <div class="flex justify-center md:justify-start mt-3">
                    <button onclick="shareProfile()" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-white/10 rounded-2xl hover:bg-slate-200 dark:hover:bg-white/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        {{ __('messages.share_profile') }}
                    </button>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-xl">
            <h2 class="text-lg font-semibold">{{ __('messages.send_anonymous_message') }}</h2>
            <p class="mt-1 text-xs text-slate-600 dark:text-slate-400">{{ __('messages.identity_notice') }}</p>

            <form class="mt-6 space-y-4" method="POST" action="{{ $messageRoute }}" enctype="multipart/form-data">
                @csrf
                <textarea id="message" name="message_text" rows="5" maxlength="500" placeholder="{{ __('messages.textarea_placeholder') }}" class="w-full rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400 focus:border-slate-300 dark:focus:border-white/40 focus:outline-none"></textarea>
                
                <!-- Image Upload Section -->
                <div class="space-y-2">
                    <label for="messageImage" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-white/10 rounded-2xl hover:bg-slate-200 dark:hover:bg-white/20 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ __('messages.attach_image') }}
                    </label>
                    <input type="file" id="messageImage" name="image" accept="image/*" class="hidden">
                    <div id="imagePreview" class="mt-2"></div>
                    @error('image')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="w-full rounded-2xl bg-slate-900 dark:bg-white px-5 py-3 text-sm font-semibold text-white dark:text-black transition hover:bg-black dark:hover:bg-slate-200">{{ __('messages.send_anonymously') }}</button>
            </form>

            <p class="mt-3 text-center text-[11px] tracking-wide text-slate-500 dark:text-slate-400">{{ __('messages.rate_limit_notice') }}</p>
        </section>

        @if ($publicMessages->isNotEmpty())
        <section class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 p-6 shadow-xl">
            <h2 class="text-lg font-semibold">{{ __('messages.public_messages') }}</h2>
            <!-- PUBLIC_MESSAGES_START -->
            <ul class="mt-4 space-y-3" data-section="public-messages">
                @foreach ($publicMessages as $msg)
                    <li class="rounded-2xl border border-slate-200 dark:border-white/10 p-4 text-sm text-slate-800 dark:text-slate-100 bg-white/80 dark:bg-white/5">
                        <div class="space-y-3">
                            <p>{{ e(\Illuminate\Support\Str::of($msg->message_text)->limit(500)) }}</p>
                            @if($msg->image_path)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $msg->image_path) }}" alt="Message image" class="max-w-full h-48 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity" onclick="openImageModal(this.src)">
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
            <!-- PUBLIC_MESSAGES_END -->
        </section>
        @endif

        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $user->display_name ?? '@'.$user->username,
            'url' => url()->current(),
            'image' => $user->avatarUrl(),
        ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
        </script>

        <!-- Image Modal -->
        <div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
            <div class="relative max-w-4xl max-h-full p-4">
                <button id="closeModal" class="absolute -top-4 -right-4 bg-white text-black rounded-full w-8 h-8 flex items-center justify-center hover:bg-gray-200 z-10">
                    ×
                </button>
                <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
            </div>
        </div>

        <!-- Share Profile Script -->
        <script>
            function shareProfile() {
                const url = window.location.href;
                const title = '{{ "@" . $user->username . " on sar7ne" }}';
                
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        url: url
                    }).catch(console.error);
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(url).then(() => {
                        // Show a temporary notification
                        const button = event.target.closest('button');
                        const originalText = button.innerHTML;
                        button.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.link_copied') }}
                        `;
                        setTimeout(() => {
                            button.innerHTML = originalText;
                        }, 2000);
                    }).catch(() => {
                        // Fallback: show URL in alert
                        alert('{{ __('messages.copy_link') }}: ' + url);
                    });
                }
            }

            function openImageModal(src) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                modalImage.src = src;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        </script>

        <script src="{{ asset('js/image-modal.js') }}"></script>
    </div>
