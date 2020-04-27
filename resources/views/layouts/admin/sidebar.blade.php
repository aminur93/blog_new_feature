<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="">Blogs</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="">BL</a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="dropdown active">
            <a href="{{ route('home') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
        </li>

        <li class="dropdown">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-list"></i> <span>Catgeroy</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('category') }}">View Category</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-tags"></i> <span>Tags</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('tag') }}">View Tags</a></li>
            </ul>
        </li>

        <li class="dropdown">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-clipboard"></i> <span>Post</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('post') }}">View Post</a></li>
            </ul>
        </li>

        <hr style="width: 225px;">

        {{--@hasrole('Admin|Sub-Admin')--}}
        <li class="dropdown">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i> <span>User Management</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('user') }}">View User</a></li>
                <li><a class="nav-link" href="{{ route('role') }}">View Role</a></li>
                @role('Admin')
                <li><a class="nav-link" href="{{ route('permission') }}">View Permission</a></li>
                @endrole
            </ul>
        </li>
        {{--@endrole--}}
    </ul>
</aside>