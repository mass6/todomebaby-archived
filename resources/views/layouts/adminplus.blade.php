<!DOCTYPE html>
<html>
<head>

    @include('includes/head')

</head>
<body class="layout-container ls-top-navbar">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary navbar-full navbar-fixed-top">
    <div class="container">

        <!-- Navbar toggle -->
        <button class="navbar-toggler hidden-md-up pull-xs-right last-child-xs" type="button" data-toggle="collapse" data-target="#navbar"><span class="material-icons">menu</span></button>

        <!-- Brand -->
        <a class="navbar-brand" href="{{ url('/') }}">Brand</a>

        <!-- Collapse -->
        <div class="collapse navbar-toggleable-xs" id="navbar">
            <ul class="nav navbar-nav">
                <li class="nav-item active"><a class="nav-link" href="{{ url('/') }}">Fixed</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/sidebar') }}">Sidebar</a></li>
            </ul>
        </div>
        <!-- // END Collapse -->
    </div>
</nav>
<!-- // END Navbar -->

<!-- Sidebar -->
<div class="sidebar sidebar-left sidebar-size-3 sidebar-visible-md-up sidebar-light ls-top-navbar-xs-up sidebar-transparent-md simplebar sidebar-visible" id="sidebarLeft" data-scrollable="" style="overflow: auto;">
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item active">
            <a class="sidebar-menu-button" href="index.html">
                <i class="sidebar-menu-icon material-icons">home</i> Dashboard
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="appointments.html">
                <i class="sidebar-menu-icon material-icons">today</i> Appointments
                <span class="sidebar-menu-label label label-primary">10</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="invoice.html">
                <i class="sidebar-menu-icon material-icons">receipt</i> Invoice
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="learning-course.html">
                <i class="sidebar-menu-icon material-icons">import_contacts</i> Course
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="property.html">
                <i class="sidebar-menu-icon material-icons">place</i> Property
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="tickets.html">
                <i class="sidebar-menu-icon material-icons">assignment</i> Tickets
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="email.html">
                <i class="sidebar-menu-icon material-icons">mail</i> Email
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="user-profile.html">
                <i class="sidebar-menu-icon material-icons">person</i> User Profile
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="timeline.html">
                <i class="sidebar-menu-icon material-icons">list</i> Timeline
                <span class="sidebar-menu-label label label-primary">NEW</span>
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="login-signup.html">
                <i class="sidebar-menu-icon material-icons">lock</i> Login / Sign Up
            </a>
        </li>
    </ul>
</div>
<!-- / end Sidebar -->

<!-- Content -->
<div class="layout-content" data-scrollable>
    <div class="container">

        @yield('breadcrumb')

        @yield('content')

    </div>
</div>
<!-- // END Content -->

<!-- App JS (includes vendor assets) -->
<script src="{{ asset('js/app.js') }}"></script>

</body>
</html>