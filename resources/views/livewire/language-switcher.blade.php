<div class="relative" x-data="{ open: false }" @click.outside="open = false"
    x-on:language-changed.window="setTimeout(() => window.location.reload(), 300)">

    {{-- Trigger Button - Globe Icon --}}
    <button
        @click="open = !open"
        type="button"
        style="display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; border:none; background:transparent; cursor:pointer; color:#6b7280; transition:background 0.15s;"
        onmouseover="this.style.background='#f3f4f6'"
        onmouseout="this.style.background='transparent'"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="9"/>
            <path d="M12 3c0 0-4 3-4 9s4 9 4 9"/>
            <path d="M12 3c0 0 4 3 4 9s-4 9-4 9"/>
            <path d="M3.5 9h17"/>
            <path d="M3.5 15h17"/>
        </svg>
    </button>

    {{-- Dropdown --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display:none; position:fixed; top:56px; right:64px; width:280px; background:white; border-radius:16px; border:1px solid #e5e7eb; box-shadow:0 10px 25px rgba(0,0,0,0.12); z-index:9999; overflow:hidden;"
    >
        {{-- Header --}}
        <div style="padding:12px 16px; border-bottom:1px solid #f3f4f6;">
            <p style="font-size:0.7rem; font-weight:600; text-transform:uppercase; letter-spacing:0.08em; color:#9ca3af; margin:0;">
                Select Language
            </p>
        </div>

        {{-- Grid --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1px; background:#f3f4f6; max-height:360px; overflow-y:auto;">
            @foreach($languages as $code => $lang)
                <button
                    wire:click="switchLanguage('{{ $code }}')"
                    @click="open = false"
                    type="button"
                    style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px; padding:16px 8px; position:relative; background:{{ $currentLocale === $code ? '#f0fdf4' : 'white' }}; border:none; cursor:pointer; transition:background 0.15s;"
                    onmouseover="if('{{ $currentLocale }}' !== '{{ $code }}') this.style.background='#f9fafb'"
                    onmouseout="this.style.background='{{ $currentLocale === $code ? '#f0fdf4' : 'white' }}'"
                >
                    @if($currentLocale === $code)
                        <span style="position:absolute; top:6px; right:6px; width:7px; height:7px; background:#16a34a; border-radius:50%;"></span>
                    @endif

                    <span style="font-size:2rem; line-height:1;">{{ $lang['flag'] }}</span>

                    <span style="font-size:0.72rem; font-weight:{{ $currentLocale === $code ? '600' : '400' }}; color:{{ $currentLocale === $code ? '#16a34a' : '#374151' }}; text-align:center; line-height:1.3;">
                        {{ $lang['label'] }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>
</div>