<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

/**
 * Resolves the active UI locale + theme for every web request.
 *
 * Resolution order (first match wins):
 *   1. Authenticated user's `users.locale` / `users.theme`
 *   2. Session values set by LocaleController / ThemeController (guests)
 *   3. Configured default in config/locale.php
 *
 * The resolved values are pushed to the app locale and shared with all views
 * so layouts can set <html lang> / data-theme without re-resolving.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = array_keys(config('locale.supported', []));
        $supportedThemes  = config('locale.themes', ['light', 'dark']);

        $locale = $this->resolve(
            $request,
            'locale',
            $supportedLocales,
            config('locale.default', 'bn')
        );

        $theme = $this->resolve(
            $request,
            'theme',
            $supportedThemes,
            config('locale.default_theme', 'light')
        );

        App::setLocale($locale);

        View::share('appLocale', $locale);
        View::share('appTheme', $theme);
        View::share('supportedLocales', config('locale.supported'));

        return $next($request);
    }

    private function resolve(Request $request, string $key, array $whitelist, string $fallback): string
    {
        $user = $request->user();
        if ($user && isset($user->{$key}) && in_array($user->{$key}, $whitelist, true)) {
            return $user->{$key};
        }

        $fromSession = $request->session()->get($key);
        if ($fromSession && in_array($fromSession, $whitelist, true)) {
            return $fromSession;
        }

        return $fallback;
    }
}
