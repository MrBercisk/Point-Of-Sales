@php
    $settings = \App\Models\Settings::current();
    $logoUrl = $settings->logo ? \Illuminate\Support\Facades\Storage::url($settings->logo) : null;
@endphp

<div style="display: flex; align-items: center; gap:2px; width: 100%;">
    @if ($logoUrl)
        <img 
            src="{{ $logoUrl }}" 
            alt="{{ $settings->CompanyName }}"
            style="height: 86px; width: 86px; object-fit: contain; flex-shrink: 0;"
        />
    @endif

    <span style="font-size: 0.9rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
        {{ $settings->CompanyName ?? config('app.name') }}
    </span>
</div>