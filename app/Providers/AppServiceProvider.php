<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        // Layout Components
        Blade::component('layouts.app', \App\View\Components\AppLayout::class);
        Blade::component('application-logo', \App\View\Components\ApplicationLogo::class);
        Blade::component('nav-link', \App\View\Components\NavLink::class);
        Blade::component('dropdown', \App\View\Components\Dropdown::class);
        Blade::component('dropdown-link', \App\View\Components\DropdownLink::class);
        Blade::component('responsive-nav-link', \App\View\Components\ResponsiveNavLink::class);

        // Form Components
        Blade::component('label', \App\View\Components\Label::class);
        Blade::component('input', \App\View\Components\Input::class);
        Blade::component('button', \App\View\Components\Button::class);
    }
}
