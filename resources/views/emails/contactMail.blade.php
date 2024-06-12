@component('mail::message')
# New Contact Mail at {{env('APP_NAME')}}

Name : {{$details['name']}} <br>

Email : {{$details['email']}} <br>

Subject : {{$details['subject']}} <br>

Message : {{$details['message']}} <br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
