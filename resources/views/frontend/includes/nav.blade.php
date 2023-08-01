
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky">
    <a href="{{ route('frontend.user.dashboard') }}" class="navbar-brand"><img src="{{ asset('img/backend/brand/Group%20458.png') }}" style="margin-left:9%;" height="40" width="195"></a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="margin-left:3%;">
        <ul class="nav navbar-nav">
            @auth
                @if ($logged_in_user->role == "admin")
                <li class="nav-item"><a href="{{ route('frontend.user.dashboard') }}" class="nav-link {{ active_class(Route::is('frontend.user.dashboard')) }}">Status</a></li>
                <li class="nav-item"><a href="{{route('frontend.user.server')}}" class="nav-link {{ active_class(Route::is('frontend.user.server')) }}">Servers</a></li>
                <li class="nav-item"><a href="{{ url('/log') }}" class="nav-link {{ active_class(Route::is('frontend.user.log')) }}">Log</a></li>
                <li class="nav-item"><a href="{{route('frontend.user.adduser')}}" class="nav-link {{ active_class(Route::is('frontend.user.adduser')) }}">Users</a></li>
                @elseif ($logged_in_user->role == "user")
                <li class="nav-item"><a href="{{ route('frontend.user.dashboard') }}" class="nav-link {{ active_class(Route::is('frontend.user.dashboard')) }}">Status</a></li>
                <li class="nav-item"><a href="{{route('frontend.user.log')}}" class="nav-link {{ active_class(Route::is('frontend.user.log')) }}">Log</a></li>
                @else
                <li class="nav-item"><a href="{{ route('frontend.user.dashboard') }}" class="nav-link {{ active_class(Route::is('frontend.user.dashboard')) }}">Status</a></li>
                <li class="nav-item"><a href="{{route('frontend.user.server')}}" class="nav-link {{ active_class(Route::is('frontend.user.server')) }}">Servers</a></li>
                <li class="nav-item"><a href="{{route('frontend.user.log')}}" class="nav-link {{ active_class(Route::is('frontend.user.log')) }}">Log</a></li>
                <li class="nav-item"><a href="{{route('frontend.user.adduser')}}" class="nav-link {{ active_class(Route::is('frontend.user.adduser')) }}">Users</a></li>
                @endif
            @endauth
        </ul>
        <ul class="nav navbar-nav ml-auto">
            @guest
                <li class="nav-item"><a href="{{route('frontend.auth.login')}}" class="nav-link {{ active_class(Route::is('frontend.auth.login')) }}">@lang('navs.frontend.login')</a></li>
            @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuUser" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">{{ $logged_in_user->name }}</a>

                    <div class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="navbarDropdownMenuUser">
                        <a href="{{ route('frontend.user.account') }}" class="dropdown-item {{ active_class(Route::is('frontend.user.account')) }}">@lang('navs.frontend.user.account')</a>
                        <a href="{{ route('frontend.auth.logout') }}" class="dropdown-item">@lang('navs.general.logout')</a>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>
