@if(Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isEmployee()))
    @include('admin.sidebar')
@else
    @include('memory_user.sidebar')
@endif

