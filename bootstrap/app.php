<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [__DIR__.'/../routes/dashboard.php', __DIR__.'/../routes/web.php'],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'user.type' => \App\Http\Middleware\CheckUserType::class,
            'provider.approved' => \App\Http\Middleware\CheckProviderStatus::class,
            'set.locale' => \App\Http\Middleware\SetLocale::class,
            'admin.permission' => \App\Http\Middleware\CheckAdminPermission::class,
            'check.account.status' => \App\Http\Middleware\CheckAccountStatus::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\UpdateLastSeen::class,
            \App\Http\Middleware\TrackVisitor::class,
        ]);
        
        $middleware->appendToGroup('localize', [
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
        ]);
        $middleware->appendToGroup('localizationRedirect', [
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        ]);
        $middleware->appendToGroup('localeSessionRedirect', [
            \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
        ]);
        $middleware->appendToGroup('localeCookieRedirect', [
            \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
        ]);
        $middleware->appendToGroup('localeViewPath', [
            \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class
        ]);

        $middleware->validateCsrfTokens(except: [
            'admin-panel/memberships/*/update-status',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
