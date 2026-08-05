@props(['key' => 'error'])

@if(session($key))
    <div class="alert-error" role="alert">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="8" cy="8" r="7"/><path d="M8 5v3M8 10.5v.5"/>
        </svg>
        {{ session($key) }}
    </div>
@endif
