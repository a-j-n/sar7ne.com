<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\UsernameGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class SocialLoginController extends Controller
{
    private const SUPPORTED_PROVIDERS = ['twitter', 'facebook'];

    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect(string $provider): RedirectResponse
    {
        $provider = $this->validateProvider($provider);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider and log them in.
     */
    public function callback(string $provider): RedirectResponse
    {
        $provider = $this->validateProvider($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('login')->with(
                'authError',
                'Unable to sign in with '.Str::ucfirst($provider).'. Please try again.'
            );
        }

        $user = $this->findOrCreateUser($socialUser, $provider);

        $user->forceFill(['last_login_at' => now()])->save();

        Auth::login($user, true);

        return redirect()->intended(route('inbox'));
    }

    /**
     * Ensure the provider is one of the supported drivers.
     */
    protected function validateProvider(string $provider): string
    {
        $provider = Str::lower($provider);

        if (! in_array($provider, self::SUPPORTED_PROVIDERS, true)) {
            throw new NotFoundHttpException;
        }

        return $provider;
    }

    /**
     * Locate an existing user or create a new account from the provider payload.
     */
    protected function findOrCreateUser(ProviderUser $providerUser, string $provider): User
    {
        $providerId = (string) $providerUser->getId();

        $user = User::query()
            ->where('provider_type', $provider)
            ->where('provider_id', $providerId)
            ->first();

        $payload = [
            'display_name' => $providerUser->getName() ?? $providerUser->getNickname(),
            'avatar_url' => $providerUser->getAvatar(),
        ];

        if ($user) {
            $user->fill(array_filter($payload, fn ($value) => ! is_null($value)));
            $user->save();

            return $user;
        }

        $usernameSeed = $providerUser->getNickname()
            ?? $providerUser->getName()
            ?? Str::random(6);

        return User::create([
            'username' => UsernameGenerator::generate($usernameSeed),
            'display_name' => $payload['display_name'],
            'avatar_url' => $payload['avatar_url'],
            'provider_id' => $providerId,
            'provider_type' => $provider,
        ]);
    }
}
