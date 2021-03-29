@component('mail::message')
# Introduction

Hello {{ $item->name }}, this is our monthly digest.

@component('mail::button', ['url' => 'unsubscribe/'.$item->email])
Unsubscribe
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
