<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}"
    class="fi antialiased h-full js-focus-visible"
    {{-- @class(['dark' => filament()->hasDarkModeToggle() && (filament()->getDefaultThemeMode()->value === 'dark')]) --}}
>
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ $title ?? config('app.name') }} — POS Cashier</title>

    @filamentStyles
    {{ filament()->getTheme()->getHtml() }}
    {{ filament()->getFontHtml() }}

    <style>
        [x-cloak] { display: none !important; }
        html, body { margin: 0; padding: 0; height: 100%; overflow: hidden; }
    </style>
</head>

<body class="fi-body fi-panel-admin h-full overflow-hidden bg-gray-50 dark:bg-gray-950">

    {{ $slot }}

    @livewire('notifications')
    @filamentScripts
    @stack('scripts')

</body>
</html>
