<div class="space-y-10 sm:space-y-12 animate-fade-in-up" wire:ignore.self>
    @php
        $messageRoute = route('profiles.message', $user);
        if (request()->routeIs('profiles.show.subdomain')) {
            $messageRoute = route('profiles.message.subdomain', ['username' => $user->username]);
        }
    @endphp

        <!-- Enhanced Profile Header -->
        <section class="relative overflow-hidden rounded-3xl border border-primary bg-primary shadow-2xl animate-fade-in-up">
            <!-- Background Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-brand-orange/5 via-brand-pink/5 to-brand-blue/5 dark:from-brand-orange/10 dark:via-brand-pink/10 dark:to-brand-blue/10"></div>
            
            <!-- Content -->
            <div class="relative flex flex-col gap-8 p-5 sm:p-8 md:p-10 text-center md:flex-row md:items-center md:text-left">
                <!-- Profile Image with Enhanced Styling -->
                <div class="relative mx-auto md:mx-0 animate-scale-in">
                    <div class="absolute -inset-2 sm:-inset-3 rounded-3xl bg-gradient-orange-pink opacity-75 blur-lg"></div>
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }} avatar" 
                         class="relative h-28 w-28 sm:h-32 sm:w-32 md:h-40 md:w-40 rounded-3xl object-cover cursor-pointer transition-all duration-300 hover:scale-105 hover:shadow-2xl border-4 border-white/20" 
                         data-profile-image />
                    <!-- Online Status Indicator -->
                    <div class="absolute -bottom-2 -right-2 h-6 w-6 sm:h-7 sm:w-7 md:h-8 md:w-8 rounded-full bg-brand-mint border-4 border-primary shadow-xl">
                        <div class="absolute inset-1 rounded-full bg-brand-mint animate-pulse"></div>
                    </div>
                </div>
                
                <!-- Profile Info -->
                <div class="flex-1 space-y-5 animate-fade-in-up" style="animation-delay: 120ms">
                    <!-- Username with Enhanced Gradient -->
                    <div class="space-y-3">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black  bg-clip-text  leading-tight tracking-tight">
                            {{ "@".$user->username }}
                        </h1>
                        @if ($user->display_name)
                            <p class="text-lg sm:text-xl font-semibold text-secondary">{{ $user->display_name }}</p>
                        @endif
                    </div>
                    
                    <!-- Bio -->
                    <div class="relative mx-auto max-w-2xl md:mx-0">
                        <p class="text-base sm:text-lg text-secondary leading-relaxed font-medium">
                            {{ $user->bio ?? __('messages.drop_anonymous_message') }}
                        </p>
                        <!-- Decorative underline -->
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 md:left-0 md:translate-x-0 w-16 h-1 bg-gradient-orange-pink rounded-full opacity-60"></div>
                    </div>
                    
                    <!-- Stats Row -->
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 sm:gap-6 text-sm animate-slide-in-right" style="animation-delay: 200ms">
                        <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-secondary/50">
                            <div class="w-2 h-2 rounded-full bg-brand-mint"></div>
                            <span class="font-medium text-secondary">
                                {{ __('messages.messages_received', ['count' => $user->total_messages_count]) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Enhanced Share Button -->
                    <div class="flex justify-center md:justify-start mt-4 sm:mt-6">
                        <button onclick="shareProfile()" 
                                class="group inline-flex items-center gap-3 px-5 sm:px-6 py-3 text-sm sm:text-base font-semibold text-white bg-gradient-orange-pink rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 glow-brand-orange">
                            <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            {{ __('messages.share_profile') }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Enhanced Message Form -->
        <section class="relative overflow-hidden rounded-3xl border border-primary bg-primary shadow-2xl">
            <!-- Subtle Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-blue/20 via-transparent to-brand-mint/20"></div>
            </div>
            
            <div class="relative p-6 sm:p-8 md:p-10">
                <!-- Enhanced Header -->
                <div class="text-center mb-8 sm:mb-10">
                    <div class="relative inline-block mb-6">
                        <!-- Background decoration -->
                        <div class="absolute -inset-4 bg-gradient-orange-pink/5 rounded-3xl blur-xl"></div>
                        <div class="relative inline-flex items-center gap-3 sm:gap-4 px-6 sm:px-8 py-3 sm:py-4 rounded-2xl bg-gradient-orange-pink/10 border-2 border-brand-orange/20 shadow-lg">
                            <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full bg-brand-orange animate-pulse shadow-lg"></div>
                            <h2 class="text-xl sm:text-2xl font-black text-primary tracking-tight">{{ __('messages.send_anonymous_message') }}</h2>
                        </div>
                    </div>
                    <p class="text-sm sm:text-base text-tertiary max-w-xl mx-auto leading-relaxed">{{ __('messages.identity_notice') }}</p>
                </div>

                <form class="space-y-6" method="POST" action="{{ $messageRoute }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Enhanced Textarea -->
                    <div class="relative">
                        <textarea id="message" name="message_text" rows="6" maxlength="500" 
                                  placeholder="{{ __('messages.textarea_placeholder') }}" 
                                  class="w-full rounded-2xl border-2 border-secondary bg-primary px-5 sm:px-6 py-4 text-sm sm:text-base text-primary placeholder:text-muted focus:border-brand-orange focus:ring-4 focus:ring-brand-orange/10 focus:outline-none transition-all duration-300 resize-none"
                                  oninput="updateCharCount(this)"></textarea>
                        
                        <!-- Character Counter -->
                        <div class="absolute bottom-3 right-4 text-xs text-muted">
                            <span id="charCount">0</span>/500
                        </div>
                    </div>
                    
                    <!-- Enhanced Image Upload -->
                    <div class="space-y-3">
                        <label for="messageImage" 
                               class="group relative flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-3 px-5 sm:px-6 py-4 text-sm font-medium text-secondary bg-secondary/30 border-2 border-dashed border-secondary rounded-2xl hover:bg-secondary/50 hover:border-brand-orange transition-all duration-300 cursor-pointer text-center sm:text-left">
                            <svg class="w-5 h-5 text-brand-orange group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ __('messages.attach_image') }}</span>
                            <span class="text-xs text-muted">(Optional)</span>
                        </label>
                        <input type="file" id="messageImage" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                        <div id="imagePreview" class="hidden rounded-2xl overflow-hidden border-2 border-secondary">
                            <img id="previewImg" src="" alt="Preview" class="w-full h-48 object-cover">
                            <div class="p-3 bg-secondary/50 flex flex-col sm:flex-row gap-2 sm:gap-0 sm:justify-between sm:items-center text-center sm:text-left">
                                <span class="text-sm text-secondary">Image attached</span>
                                <button type="button" onclick="removeImage()" class="text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                            </div>
                        </div>
                        @error('image')
                            <p class="text-red-500 text-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Enhanced Submit Button -->
                    <button type="submit" 
                            class="group relative w-full overflow-hidden rounded-2xl bg-gradient-orange-pink px-6 sm:px-8 py-4 text-sm sm:text-base font-bold text-white shadow-2xl transition-all duration-300 hover:scale-[1.02] hover:shadow-3xl glow-brand-orange">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                        <span class="relative flex items-center justify-center gap-3">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            {{ __('messages.send_anonymously') }}
                        </span>
                    </button>
                </form>

                <!-- Rate Limit Notice -->
                <div class="mt-5 sm:mt-6 text-center">
                    <p class="text-[11px] sm:text-xs text-muted bg-secondary/30 px-3 sm:px-4 py-2 rounded-full inline-block">
                        {{ __('messages.rate_limit_notice') }}
                    </p>
                </div>
            </div>
        </section>

        @if ($publicMessages->isNotEmpty())
        <!-- Enhanced Public Messages -->
        <section class="relative overflow-hidden rounded-3xl border border-primary bg-primary shadow-2xl">
            <!-- Background Decoration -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-brand-mint/10 to-transparent rounded-full -translate-y-16 translate-x-16"></div>
            
            <div class="relative p-6 sm:p-8 md:p-10">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6 sm:mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-blue-mint flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-primary">{{ __('messages.public_messages') }}</h2>
                    </div>
                    <div class="hidden sm:block flex-1 h-px bg-gradient-to-r from-brand-mint/50 to-transparent"></div>
                    <div class="px-3 py-1 rounded-full bg-brand-mint/10 border border-brand-mint/20 w-fit">
                        <span class="text-sm font-medium text-brand-mint">{{ $publicMessages->count() }} {{ $publicMessages->count() === 1 ? 'message' : 'messages' }}</span>
                    </div>
                </div>
                
                <!-- Messages Grid -->
                <div class="grid gap-5 sm:gap-6" data-section="public-messages">
                    @foreach ($publicMessages as $index => $msg)
                        <article class="group relative overflow-hidden rounded-2xl border border-secondary bg-secondary/30 p-5 sm:p-6 transition-all duration-300 hover:shadow-xl hover:scale-[1.02] hover:border-brand-mint/50"
                                 style="animation-delay: {{ $index * 100 }}ms">
                            <!-- Message Content -->
                            <div class="space-y-4">
                                <!-- Message Text -->
                                <div class="relative">
                                    <p class="text-base text-primary leading-relaxed">
                                        {{ e(\Illuminate\Support\Str::of($msg->message_text)->limit(500)) }}
                                    </p>
                                    <!-- Decorative Quote -->
                                    <div class="absolute -top-2 -left-2 w-6 h-6 text-brand-mint/20">
                                        <svg fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Message Image -->
                                @if($msg->image_path)
                                    <div class="relative overflow-hidden rounded-xl">
                                        <img src="{{ Storage::url($msg->image_path) }}" 
                                             alt="Message image" 
                                             class="w-full h-64 object-cover cursor-pointer transition-all duration-300 hover:scale-105" 
                                             onclick="openImageModal(this.src)">
                                        <!-- Image Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        <!-- Zoom Icon -->
                                        <div class="absolute top-3 right-3 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-75 group-hover:scale-100">
                                            <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Message Footer -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pt-2 border-t border-secondary/50">
                                    <div class="flex items-center gap-2 text-xs text-muted">
                                        <div class="w-2 h-2 rounded-full bg-brand-mint"></div>
                                        <span>Anonymous message</span>
                                    </div>
                                    <time class="text-xs text-muted">
                                        {{ $msg->created_at->diffForHumans() }}
                                    </time>
                                </div>
                            </div>
                            
                            <!-- Hover Gradient Border -->
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-brand-mint/0 via-brand-mint/20 to-brand-mint/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        </article>
                    @endforeach
                </div>
            </div>
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

        <!-- Enhanced JavaScript -->
        <script>
            // Share Profile Function
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
                            <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            // Character Counter Function
            function updateCharCount(textarea) {
                const charCount = document.getElementById('charCount');
                const currentLength = textarea.value.length;
                const maxLength = 500;
                
                charCount.textContent = currentLength;
                
                // Change color based on character count
                if (currentLength > maxLength * 0.9) {
                    charCount.className = 'text-red-500 font-medium';
                } else if (currentLength > maxLength * 0.7) {
                    charCount.className = 'text-yellow-500 font-medium';
                } else {
                    charCount.className = 'text-muted';
                }
            }

            // Image Preview Function
            function previewImage(input) {
                const preview = document.getElementById('imagePreview');
                const previewImg = document.getElementById('previewImg');
                
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        preview.classList.remove('hidden');
                        
                        // Add fade-in animation
                        preview.style.opacity = '0';
                        preview.style.transform = 'translateY(10px)';
                        setTimeout(() => {
                            preview.style.transition = 'all 0.3s ease';
                            preview.style.opacity = '1';
                            preview.style.transform = 'translateY(0)';
                        }, 10);
                    };
                    
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Remove Image Function
            function removeImage() {
                const preview = document.getElementById('imagePreview');
                const input = document.getElementById('messageImage');
                const previewImg = document.getElementById('previewImg');
                
                // Fade out animation
                preview.style.transition = 'all 0.3s ease';
                preview.style.opacity = '0';
                preview.style.transform = 'translateY(-10px)';
                
                setTimeout(() => {
                    preview.classList.add('hidden');
                    input.value = '';
                    previewImg.src = '';
                    preview.style.opacity = '';
                    preview.style.transform = '';
                    preview.style.transition = '';
                }, 300);
            }

            // Enhanced Image Modal Function
            function openImageModal(src) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                
                modalImage.src = src;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                
                // Add entrance animation
                modal.style.opacity = '0';
                setTimeout(() => {
                    modal.style.transition = 'opacity 0.3s ease';
                    modal.style.opacity = '1';
                }, 10);
            }

            // Initialize animations on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Stagger animation for message cards
                const messageCards = document.querySelectorAll('[data-section="public-messages"] article');
                messageCards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100 + 200);
                });
            });
        </script>

        <script src="{{ asset('js/image-modal.js') }}"></script>
    </div>
