<!DOCTYPE html>
@php
    $appLocale = app()->getLocale();
    $htmlLocale = match ($appLocale) {
        'ar' => 'ar-EG',
        default => str_replace('_', '-', $appLocale),
    };
@endphp
<html lang="{{ $htmlLocale }}" dir="{{ in_array($appLocale, ['ar']) ? 'rtl' : 'ltr' }}" class="{{ request()->cookie('theme', 'dark') === 'dark' || (request()->cookie('theme', 'dark') === 'system' && request()->header('Sec-CH-Prefers-Color-Scheme') === 'dark') ? 'dark' : '' }}">
    <head>
        <script type="text/javascript">
            (function(c,l,a,r,i,t,y){
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", "trsta0m5jl");
        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'sar7ne')</title>

        {{-- SEO meta defaults and overridable sections --}}
        <meta name="description" content="@yield('meta_description', __('messages.meta_description'))">
        <meta name="robots" content="@yield('meta_robots', 'index, follow')">
        <link rel="canonical" href="@yield('canonical', url()->current())">
        @php
            $altLocales = config('app.supported_locales', ['en','ar']);
            $currentUrl = url()->current();
            $currentQuery = request()->query();
            $localeParam = config('app.locale_param', 'lang');
        @endphp
        {{-- x-default points to default/fallback language --}}
        @php
            $defaultLoc = config('app.locale');
            $qDefault = array_merge($currentQuery, [$localeParam => $defaultLoc]);
            $hrefDefault = $currentUrl . (count($qDefault) ? ('?' . http_build_query($qDefault)) : '');
        @endphp
        <link rel="alternate" hreflang="x-default" href="{{ $hrefDefault }}">

        @foreach ($altLocales as $loc)
            @php
                $q = array_merge($currentQuery, [$localeParam => $loc]);
                $href = $currentUrl . (count($q) ? ('?' . http_build_query($q)) : '');
            @endphp
            <link rel="alternate" hreflang="{{ $loc }}" href="{{ $href }}">
        @endforeach

        {{-- Open Graph --}}
        <meta property="og:site_name" content="{{ config('app.name', 'sar7ne') }}">
        <meta property="og:title" content="@yield('og_title', trim(strip_tags(View::yieldContent('title'))))">
        <meta property="og:description" content="@yield('meta_description', 'sar7ne — anonymous messaging for creators and friends. Send kind, anonymous messages to people you care about.')">
        <meta property="og:type" content="@yield('og_type', 'website')">
        <meta property="og:url" content="@yield('canonical', url()->current())">
        <meta property="og:image" content="@yield('meta_image', asset('opengraph.png'))">

        {{-- Twitter --}}
        <meta name="twitter:card" content="@yield('twitter_card', 'summary_large_image')">
        <meta name="twitter:title" content="@yield('og_title', trim(strip_tags(View::yieldContent('title'))))">
        <meta name="twitter:description" content="@yield('meta_description', 'sar7ne — anonymous messaging for creators and friends. Send kind, anonymous messages to people you care about.')">
        <meta name="twitter:image" content="@yield('meta_image', asset('opengraph.png'))">

        <!-- Hint to browsers about supported color schemes -->
        <meta name="color-scheme" content="light dark">

        {{-- Structured data (JSON-LD) --}}
        @php
            $__siteStructuredData = [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'url' => config('app.url', url('/')),
                'name' => config('app.name', 'sar7ne'),
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => route('explore').'?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($__siteStructuredData, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) !!}</script>

        {{-- Early theme script: apply theme on first paint to avoid flicker and set meta theme-color --}}
        <script>
            (function() {
                function readThemeCookie() {
                    try {
                        var cookie = (document.cookie || '').split(';').map(function(s){return s.trim();}).find(function(s){return s.indexOf('theme=') === 0});
                        return cookie ? decodeURIComponent(cookie.split('=')[1]) : null;
                    } catch (e) { return null; }
                }

                function isSystemDark() {
                    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                }

                function setMetaThemeColor(isDark) {
                    try {
                        var metaDark = document.querySelector('meta[name="theme-color"][media*="dark"]');
                        var metaLight = document.querySelector('meta[name="theme-color"][media*="light"]');
                        var fallback = document.querySelector('meta[name="theme-color"]:not([media])');
                        if (fallback) { fallback.parentNode.removeChild(fallback); }
                        if (isDark) {
                            if (metaDark) metaDark.content = '#05070d';
                        } else {
                            if (metaLight) metaLight.content = '#ffffff';
                        }
                    } catch (e) { /* noop */ }
                }

                function applyThemeImmediate(theme) {
                    if (theme === 'system') {
                        if (isSystemDark()) {
                            document.documentElement.classList.add('dark');
                            setMetaThemeColor(true);
                        } else {
                            document.documentElement.classList.remove('dark');
                            setMetaThemeColor(false);
                        }
                    } else if (theme === 'dark') {
                        document.documentElement.classList.add('dark');
                        setMetaThemeColor(true);
                    } else {
                        document.documentElement.classList.remove('dark');
                        setMetaThemeColor(false);
                    }
                }

                try {
                    var theme = readThemeCookie() || 'dark';
                    applyThemeImmediate(theme);

                    // If theme is system, react to OS changes as early as possible
                    if (theme === 'system' && window.matchMedia) {
                        var mq = window.matchMedia('(prefers-color-scheme: dark)');
                        if (mq.addEventListener) {
                            mq.addEventListener('change', function(){ applyThemeImmediate('system'); });
                        } else if (mq.addListener) {
                            // Safari <14
                            mq.addListener(function(){ applyThemeImmediate('system'); });
                        }
                    }
                } catch (e) {
                    // ignore
                }
            })();
        </script>

        @if (!empty(env('GOOGLE_TAG_ID')))
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_TAG_ID') }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ env('GOOGLE_TAG_ID') }}');
            </script>
        @endif

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{--        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))--}}
            @vite(['resources/css/app.css'])
{{--        @endif--}}

        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#05070d" media="(prefers-color-scheme: dark)">
        <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Favicons / platform icons generated by scripts/generate-icons.mjs -->
        <link rel="icon" type="image/png" sizes="16x16" href="/icon-16x16.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/icon-32x32.png">
        <link rel="icon" type="image/png" sizes="48x48" href="/icon-48x48.png">
        <link rel="icon" type="image/png" sizes="64x64" href="/icon-64x64.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/icon-96x96.png">
        <link rel="icon" type="image/png" sizes="128x128" href="/icon-128x128.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/icon-192x192.png">
        <link rel="icon" type="image/png" sizes="256x256" href="/icon-256x256.png">
        <link rel="icon" type="image/png" sizes="384x384" href="/icon-384x384.png">
        <link rel="icon" type="image/png" sizes="512x512" href="/icon-512x512.png">
        <link rel="shortcut icon" href="/favicon.ico">

        <!-- Microsoft tile -->
        <meta name="msapplication-TileImage" content="/icon-144x144.png">
        <meta name="msapplication-TileColor" content="#0d6efd">

        {{-- Resource Hints for faster connections --}}
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="dns-prefetch" href="//fonts.googleapis.com">
        <link rel="dns-prefetch" href="//fonts.gstatic.com">

        {{-- Allow pages to push small head hints (preconnects, critical meta) --}}
        @stack('head')

        {{-- Livewire Styles --}}
        @livewireStyles
        {{-- Leaflet is now lazy-loaded on pages that need it --}}
    </head>
    <body class="@php
        $theme = request()->cookie('theme', 'dark');
        $isSystemDark = request()->header('Sec-CH-Prefers-Color-Scheme') === 'dark';
        $isDark = $theme === 'dark' || ($theme === 'system' && $isSystemDark);
        $isArabic = $appLocale === 'ar';
        $reduceMotion = auth()->check() ? (bool) auth()->user()->reduce_motion : false;
        echo 'min-h-screen bg-secondary text-primary font-sans antialiased' . ($isArabic ? ' font-cairo lang-ar' : '') . ($reduceMotion ? ' reduce-motion' : '');
    @endphp">
        <div class="min-h-screen pt-[calc(4rem+env(safe-area-inset-top))]">

            @if (session('status'))
                <div id="toast" class="fixed z-50 left-1/2 -translate-x-1/2 top-[calc(4rem+env(safe-area-inset-top))]" data-dismiss-timeout="{{ config('features.toast_timeout', 3500) }}">
                    <div class="rounded-xl bg-white text-black shadow-xl border border-slate-200 px-4 py-3 text-sm max-w-md flex items-start gap-3 animate-fade-in-up">
                        <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <div class="flex-1">{{ session('status') }}</div>
                        <button type="button" onclick="(function(){const t=document.getElementById('toast'); if(t){t.remove();}})()" class="text-slate-500 hover:text-slate-700">✕</button>
                    </div>
                </div>
                <script>
                    (function(){
                        const t = document.getElementById('toast');
                        const timeout = parseInt(t?.getAttribute('data-dismiss-timeout') || '3500', 10);
                        setTimeout(function(){ if (t) t.remove(); }, timeout);
                    })();
                </script>
            @endif

            @if (session('status'))
                <div class="mx-auto mt-4 w-full max-w-2xl px-4">
                    <div class="rounded-xl border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-200">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mx-auto mt-4 w-full max-w-2xl px-4">
                    <div class="rounded-xl border border-red-300 dark:border-red-500/40 bg-red-50 dark:bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-200">
                        <span class="font-semibold">{{ __('messages.we_found_some_issues') }}</span>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <main class="mx-auto w-full max-w-4xl px-4 py-6 text-primary">
    @yield('content')
    @include('components.gallery')
                {{ $slot ?? '' }}
            </main>
        </div>

        <x-ui.navbar />

        {{-- Livewire Scripts --}}
        @livewireScripts
        {{-- Reverb / Echo bootstrap (expects window.Echo when configured) --}}
        @vite(['resources/js/app.js'])
        @stack('body-end')
        <!-- Global Confirm Toast (Sonner-style) -->
        <div id="confirm-toast-root" class="fixed inset-0 z-[10000] pointer-events-none">
            <div id="confirm-toast-stack" class="absolute flex flex-col gap-3 bottom-[calc(1rem+env(safe-area-inset-bottom))] right-[calc(1rem+env(safe-area-inset-right))]"></div>
            <template id="confirm-toast-template">
                <div class="pointer-events-auto w-[320px] rounded-xl border border-slate-200 bg-white/95 backdrop-blur shadow-xl overflow-hidden" data-close-duration="{{ config('features.confirm_toast_close_ms', 180) }}">
                    <div class="p-3">
                        <div class="text-sm font-semibold text-slate-900" data-ct-title></div>
                        <div class="mt-1 text-xs text-slate-600" data-ct-message></div>
                    </div>
                    <div class="flex items-center justify-end gap-2 p-3 bg-slate-50 border-t border-slate-200">
                        <button type="button" class="px-3 py-1.5 text-xs rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100" data-ct-cancel>{{ __('messages.cancel') }}</button>
                        <button type="button" class="px-3 py-1.5 text-xs rounded-lg bg-red-600 text-white hover:bg-red-700" data-ct-confirm>{{ __('messages.delete') }}</button>
                    </div>
                </div>
            </template>
        </div>

        <script>
            (function(){
                const root = document.getElementById('confirm-toast-root');
                const stack = document.getElementById('confirm-toast-stack');
                const tpl = document.getElementById('confirm-toast-template');
                function createToast({ title, message, confirmText, cancelText, onConfirm }){
                    const node = tpl.content.firstElementChild.cloneNode(true);
                    node.querySelector('[data-ct-title]').textContent = title || '';
                    node.querySelector('[data-ct-message]').textContent = message || '';
                    const btnCancel = node.querySelector('[data-ct-cancel]');
                    const btnConfirm = node.querySelector('[data-ct-confirm]');
                    if (cancelText) btnCancel.textContent = cancelText;
                    if (confirmText) btnConfirm.textContent = confirmText;

                    const wrapper = document.createElement('div');
                    wrapper.className = 'translate-y-4 opacity-0 transition-all duration-200';
                    wrapper.appendChild(node);
                    stack.appendChild(wrapper);
                    requestAnimationFrame(()=>{
                        wrapper.classList.remove('translate-y-4','opacity-0');
                        wrapper.classList.add('translate-y-0','opacity-100');
                    });

                    function close(){
                        const closeDur = parseInt(node.getAttribute('data-close-duration') || '180', 10);
                        wrapper.classList.add('opacity-0','translate-y-4');
                        setTimeout(()=>{ wrapper.remove(); }, closeDur);
                    }
                    btnCancel.addEventListener('click', close);
                    btnConfirm.addEventListener('click', function(){ try { onConfirm && onConfirm(); } finally { close(); } });
                    // Keyboard accessibility: Esc to close
                    const keyHandler = (e) => { if (e.key === 'Escape') { close(); } };
                    document.addEventListener('keydown', keyHandler, { once: true });

                    // Focus management
                    setTimeout(()=>{ btnCancel.focus(); }, 0);
                }
                window.confirmToast = function(opts){
                    createToast(Object.assign({
                        title: '{{ __('messages.delete') }}',
                        message: '{{ __('messages.confirm_delete_post') }}',
                        confirmText: '{{ __('messages.delete') }}',
                        cancelText: '{{ __('messages.cancel') }}',
                    }, opts || {}));
                };

                // Wire up delete forms that opt in via data-confirm-delete
                document.addEventListener('click', function(e){
                    const btn = e.target.closest('[data-confirm-delete]');
                    if (!btn) return;
                    e.preventDefault();
                    const form = btn.closest('form');
                    window.confirmToast({ onConfirm: () => form?.submit() });
                });
            })();
        </script>
    </body>
</html>
