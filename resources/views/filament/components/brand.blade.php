@php
    $settings = \App\Models\Settings::current();
    $logoUrl  = $settings->logo ? \Illuminate\Support\Facades\Storage::url($settings->logo) : null;
    $name     = $settings->CompanyName ?? config('app.name');
@endphp

<div style="
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 4px 0;
    margin-right: auto;
    min-width: 0;
    width: 100%;
">
    @if ($logoUrl)
        <div style="
            width: 52px;
            height: 52px;
            border-radius: 14px;
            overflow: hidden;
            flex-shrink: 0;
            background: #f0fdf4;
            border: 2px solid #bbf7d0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(22,163,74,0.15);
        ">
            <img
                src="{{ $logoUrl }}"
                alt="{{ $name }}"
                style="width:100%; height:100%; object-fit:contain; padding:4px;"
            />
        </div>
    @else
        <div style="
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #16a34a, #15803d);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(22,163,74,0.3);
        ">
            <span style="color: white; font-size: 1.3rem; font-weight: 800;">
                {{ strtoupper(substr($name, 0, 1)) }}
            </span>
        </div>
    @endif

    <div style="
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
        flex: 1;
    ">
        <span style="
            font-size: 0.82rem;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.02em;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        ">{{ $name }}</span>

        <div style="display: flex; align-items: center; gap: 5px;">
            <div style="
                width: 5px;
                height: 5px;
                border-radius: 50%;
                background: #16a34a;
                flex-shrink: 0;
            "></div>
            <span style="
                font-size: 0.6rem;
                font-weight: 700;
                color: #16a34a;
                letter-spacing: 0.1em;
                text-transform: uppercase;
            ">Admin Panel</span>
        </div>
    </div>
</div>