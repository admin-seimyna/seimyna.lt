@component('mail::message')
# {{ $subject }}

{{ $text }}

@if(isset($code))
@component('mail::panel')
    <span class="verification-code">{{ $code }}</span>
@endcomponent
@endif

{{ config('app.name') }}
@endcomponent
