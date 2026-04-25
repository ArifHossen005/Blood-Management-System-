<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch the UI language. Persists to session (always) and to the user
     * record (when authenticated). Whitelisted against config/locale.php so
     * arbitrary path values can't leak into app()->setLocale().
     */
    public function switch(Request $request, string $locale)
    {
        $supported = array_keys(config('locale.supported', []));
        abort_unless(in_array($locale, $supported, true), 404);

        $request->session()->put('locale', $locale);

        if ($user = $request->user()) {
            $user->update(['locale' => $locale]);
        }

        return back();
    }
}
