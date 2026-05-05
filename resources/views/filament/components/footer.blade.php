@php
    $settings = \App\Models\Settings::current();
@endphp

<div style="
    text-align: center; 
    padding: 12px 16px; 
    font-size: 0.75rem; 
    color: #6b7280;
    border-top: 1px solid #e5e7eb;
    background: white;
">
    &copy; {{ date('Y') }} {{ $settings->footer ?? config('app.name') }}. All rights reserved.
    
    @if(!empty($settings->developed_by))
        &mdash; Developed by <strong>{{ $settings->developed_by }}</strong>
    @endif
</div>