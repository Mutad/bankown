<nav class="" role="navigation">
    <div class="content container">
        <ul class="d-flex justify-content-around align-items-center p-2 m-0">
            <li>
                <a href="{{route('welcome',app()->getLocale())}}" class="{{ (strpos(Route::currentRouteName(), 'welcome') === 0) ? 'active' : '' }}">
                    <img src="{{asset('logo.png')}}" alt="logo image"
                        class="logo">
                </a>
            </li>
            <li>
                <a href="{{route('hub',app()->getLocale())}}"
                    class="{{ (strpos(Route::currentRouteName(), 'hub') === 0) ? 'active' : '' }}">Hub</a>
            </li>
            <li><a href="#">Apps</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contacts</a></li>
        </ul>
    </div>
</nav>