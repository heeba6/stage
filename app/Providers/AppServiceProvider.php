<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Password;
//use App\Models\UtilisateurModel;
//use Illuminate\Auth\Events\PasswordReset;
//use Illuminate\Support\Facades\Event; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
        // Password::createUrlUsing(function (UtilisateurModel $user, string $token) {
        //     return config('app.frontend_url') . "/forgot-password?token=$token&email=" . urlencode($user->email);
        // });
        // Event::listen(PasswordReset::class, function ($event) {
        //     $url = config('app.frontend_url') . "/forgot-password?token={$event->token}&email=" . urlencode($event->user->email);
        //     // Garde une trace ou utilise cette URL si nécessaire.
        // });
    }
}
