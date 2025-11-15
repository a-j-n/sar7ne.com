<?php

use App\Http\Controllers\Auth\EmailLoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\InboxMessageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicMessageController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Explore as ExplorePage;
use App\Livewire\Inbox as InboxPage;
use App\Livewire\Profile\Info as ProfileInfoPage;
use App\Livewire\Profile\Settings as ProfileSettingsPage;
use App\Livewire\PublicProfile as PublicProfilePage;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

Route::pattern('username', '[a-z0-9_]+');

// Define rate limiter for posts submissions
RateLimiter::for('posts-submission', function (\Illuminate\Http\Request $request) {
    $key = optional($request->user())->id ? 'user:'.$request->user()->id : 'ip:'.$request->ip();

    return Limit::perMinutes(1, 5)->by($key);
});

Route::get('/', ExplorePage::class)->name('explore');

// Simple Posts page placeholder
Route::view('/posts', 'posts.index')->name('posts');
Route::middleware('throttle:posts-submission')->post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->middleware('auth')->name('posts.comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->middleware('auth')->name('comments.destroy');
Route::post('/posts/{post}/share', [PostController::class, 'trackShare'])->name('posts.share');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like');

Route::get('/login', [EmailLoginController::class, 'show'])->name('login');
Route::post('/login', [EmailLoginController::class, 'login'])->name('login.attempt');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('guest')->name('password.update');

Route::controller(SocialLoginController::class)->group(function () {
    Route::get('/auth/{provider}/redirect', 'redirect')->name('oauth.redirect');
    Route::get('/auth/{provider}/callback', 'callback')->name('oauth.callback');
});

// Allow guests to view pages, actions remain protected below
Route::get('/inbox', InboxPage::class)->name('inbox');
Route::get('/profile', ProfileInfoPage::class)->name('profile');
Route::get('/profile/info', ProfileInfoPage::class)->name('profile.info');
Route::get('/profile/settings', ProfileSettingsPage::class)->name('profile.settings');

// Auth-only actions for inbox and profile updates
Route::middleware('auth')->group(function () {
    Route::patch('/inbox/{message}/read', [InboxMessageController::class, 'markRead'])->name('inbox.messages.read');
    Route::patch('/inbox/{message}/unread', [InboxMessageController::class, 'markUnread'])->name('inbox.messages.unread');
    Route::put('/inbox/{message}/public', [InboxMessageController::class, 'togglePublic'])->name('inbox.messages.toggle-public');
    Route::delete('/inbox/{message}', [InboxMessageController::class, 'destroy'])->name('inbox.messages.destroy');

    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/settings', [ProfileController::class, 'update'])->name('profile.settings.update');

    // Timeline feature removed
});

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('explore');
})->middleware('auth')->name('logout');

$profileDomain = config('app.profile_domain_root');

if ($profileDomain) {
    Route::domain('{username.'.$profileDomain)->group(function () {
        Route::get('/', [PublicProfileController::class, 'showByUsername'])->name('profiles.show.subdomain');
        Route::post('/', [PublicMessageController::class, 'storeByUsername'])
            ->middleware('throttle:message-submission')
            ->name('profiles.message.subdomain');
    });
}

Route::get('/p/{user:username}', PublicProfilePage::class)->name('profiles.show');
Route::post('/p/{user:username}/messages', [PublicMessageController::class, 'store'])
    ->middleware('throttle:message-submission')
    ->name('profiles.message');

// Add /{user:username} route for public profiles (must be after all other specific routes)
Route::get('/{user:username}', PublicProfilePage::class)->name('profiles.show.short');
Route::post('/{user:username}/messages', [PublicMessageController::class, 'store'])
    ->middleware('throttle:message-submission')
    ->name('profiles.message.short');

// Privacy Policy
Route::view('/privacy', 'public.privacy')->name('privacy');

Route::get('/language/{locale}', [LanguageController::class, 'switchLanguage'])->name('language.switch');
Route::get('/theme/{theme}', [\App\Http\Controllers\ThemeController::class, 'switchTheme'])->name('theme.switch');

// Sitemap
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

// robots.txt
Route::get('/robots.txt', function () {
    return response()->view('robots')->header('Content-Type', 'text/plain');
})->name('robots');

Route::get('/_debug/locale', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'app_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'cookie_locale' => $request->cookie('locale'),
    ]);
})->name('debug.locale');
