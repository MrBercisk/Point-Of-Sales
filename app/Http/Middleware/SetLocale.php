<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $settings = Settings::current();
        
        $locale = $settings->default_language ?? config('app.locale');
        
        $localeMap = [
            'en'    => 'en',
            'Ind'   => 'id',
            'kr'    => 'kr',
            'fr'    => 'fr',
            'ja'    => 'ja',
            'ar'    => 'ar',
        ];

        App::setLocale($localeMap[$locale] ?? 'en');

        return $next($request);
    }
}