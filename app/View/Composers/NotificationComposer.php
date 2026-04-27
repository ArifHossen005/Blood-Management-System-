<?php

namespace App\View\Composers;

use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view): void
    {
        if (!auth()->check()) {
            $view->with('unreadCount', 0)->with('recentNotifications', collect());
            return;
        }

        $user = auth()->user();
        $view->with('unreadCount', $user->unreadNotifications()->count())
             ->with('recentNotifications', $user->notifications()->latest()->limit(6)->get());
    }
}
