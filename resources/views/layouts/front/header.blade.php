<header>
    <!-- Navbar
  ================================================== -->
    <div class="navbar navbar-static-top">
        <div class="navbar-inner">
            <div class="container">
                <!-- logo -->
                <div class="logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('frontend/assets/img/logo.png') }}" alt="" />
                    </a>
                </div>
                <!-- end logo -->
                <!-- top menu -->
                <div class="navigation">
                    <nav>
                        <ul class="nav topnav">
                            <li>
                                <a href="{{ url('/') }}"><i class="icon-home"></i> Home </a>
                            </li>
                            <li>
                                <a href="{{ route('post.allPost') }}"><i class=" icon-folder-open-alt"></i> All Post </a>
                            </li>
                            <li>
                                <a href="{{ route('post.contact') }}"><i class="icon-envelope-alt"></i> Contact </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- end menu -->
            </div>
        </div>
    </div>
</header>