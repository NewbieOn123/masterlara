<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Stisla</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">St</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item active">
                <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Manage User</li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user"></i>
                    <span>User Management</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('useraccess') }}">User Access</a></li>
                    <li><a class="nav-link" href="{{ route('groupaccess') }}">Group Access</a></li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="blank.html"><i class="far fa-square"></i>
                    <span>Blank Page</span>
                </a>
            </li>
        </ul>
    </aside>
</div>