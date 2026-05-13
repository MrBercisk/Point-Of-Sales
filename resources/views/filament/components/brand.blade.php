@php
    $settings = \App\Models\Settings::current();
    $logoUrl  = $settings->logo ? \Illuminate\Support\Facades\Storage::url($settings->logo) : null;
    $name     = $settings->CompanyName ?? config('app.name');
@endphp

<div style="display:flex; align-items:center; gap:10px; padding:2px 0; width:100%;">

    @if ($logoUrl)
        <img
            src="{{ $logoUrl }}"
            alt="{{ $name }}"
            style="height:40px; width:40px; object-fit:contain; border-radius:10px; flex-shrink:0;"
        />
    @endif

    <div style="display:flex; flex-direction:column; line-height:1.3; min-width:0; flex:1;">
        <span style="
            font-size: 0.85rem;
            font-weight: 700;
            color: #111827;
            white-space: normal;
            word-break: break-word;
            letter-spacing: -0.01em;
            line-height: 1.25;
        ">{{ $name }}</span>
        <span style="
            font-size: 0.65rem;
            font-weight: 600;
            color: #16a34a;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-top: 1px;
        ">Admin Panel</span>
    </div>

</div>