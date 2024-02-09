<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">Laramise</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">Mise</a>
        </div>
        <ul class="sidebar-menu">

            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>

            <li class="menu-header">Admin Area</li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Admin Area</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('user.index') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('user.index') }}">User</a>
                    </li>
                </ul>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('category.index') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('category.index') }}">Categories</a>
                    </li>
                </ul>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('product.index') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('product.index') }}">Products</a>
                    </li>
                </ul>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('order.index') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('order.index') }}">Orders</a>
                    </li>
                </ul>
            </li>

    </aside>
</div>
