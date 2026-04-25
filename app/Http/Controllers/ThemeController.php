<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Switch the UI theme. Persists to session (always) and to the user
     * record (when authenticated). Whitelisted against config/locale.php.
     */
    public function switch(Request $request, string $theme)
    {
        $supported = config('locale.themes', ['light', 'dark']);
        abort_unless(in_array($theme, $supported, true), 404);

        $request->session()->put('theme', $theme);

        if ($user = $request->user()) {
            $user->update(['theme' => $theme]);
        }

        return back();
    }
}
