<nav class="navbar navbar-default navbar-static-top">
  <div class="container">
    <div class="navbar-header">

      <!-- Collapsed Hamburger -->
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <!-- Branding Image -->
      <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name', 'Laravel') }}
      </a>
    </div>

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
      <!-- Left Side Of Navbar -->
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Threads <span
                class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ route('threads.index') }}">All Threads</a></li>
            <li><a href="{{ route('threads.index') }}?popular=1">Popular Threads all time</a></li>
            <li><a href="{{ route('threads.index') }}?unanswered=1">Unanswered threads</a></li>
            @if(auth()->check())
              <li><a href="{{ route('threads.index') }}?by={{ auth()->user()->name }}">My Threads</a></li>
            @endif
          </ul>
        </li>

        <li><a href="{{ route('threads.create') }}">New Thread</a></li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Channels <span class="caret"></span></a>
          <ul class="dropdown-menu">
            @foreach($channels as $channel)
              <li><a href="{{ route('threads.by_channel', $channel->slug) }}">{{ $channel->name }}</a></li>
            @endforeach
          </ul>
        </li>
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="nav navbar-nav navbar-right">
        <!-- Authentication Links -->
        @if (Auth::guest())
          <li><a href="{{ route('login') }}">Login</a></li>
          <li><a href="{{ route('register') }}">Register</a></li>
        @else
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
              {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="{{ route('profiles.show', auth()->user()) }}">Profile</a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                  Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                </form>
              </li>
            </ul>
          </li>
        @endif
      </ul>
    </div>
  </div>
</nav>
