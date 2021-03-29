@component('mail::message')
# Introduction

Thank you {{ $item->name }} for subscribing to our newsletter.  you are now on our mailing list and will be receiving our weekly digest.

@component('mail::button', ['url' => 'unsubscribe/'.$item->email])
Unsubscribe
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
