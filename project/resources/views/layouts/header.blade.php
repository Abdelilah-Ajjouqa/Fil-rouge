@auth
    @include('layouts.headers.auth-header')
@else
    @include('layouts.headers.guest-header')
@endauth
