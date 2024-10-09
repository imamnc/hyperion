<?php

namespace Itpi\Providers;

use Itpi\Actions\Fortify\CreateNewUser;
use Itpi\Actions\Fortify\ResetUserPassword;
use Itpi\Actions\Fortify\UpdateUserPassword;
use Itpi\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Register
        Fortify::registerView(function () {
            return view('auth.register');
        });

        // Login
        Fortify::loginView(function () {
            if (request()->header('referer')) {
                $referer = request()->header('referer');
                $referer = explode('/', $referer);
                if ($referer[3] == 'api') {
                    return response()->json([
                        'message' => 'Unauthenticated.'
                    ], 401);
                } else {
                    return view('auth.login');
                }
            } else {
                return view('auth.login');
            }
        });

        // Forgot Password
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        // Reset Password
        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });
    }
}
