<!DOCTYPE html>
<html class="bootstrap-layout">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>TodoMeBaby</title>
    <meta name="description" content="Project and task manager. TodoMeBaby is a the perfect online task management app to help you get things done.!" />

    <!-- Material Design Icons  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Raleway Web Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
    <link type="text/css" href="{{ asset('css/icons/icomoon/styles.css') }}" rel="stylesheet">
    <!-- App CSS -->
    {{--<link type="text/css" href="assets/css/style.css" rel="stylesheet">--}}
    <link type="text/css" href="{{ asset('css/adminplus/style.css') }}" rel="stylesheet">
    {{--<link rel="stylesheet" href="examples/css/bootstrap-datepicker.min.css">--}}
    <link type="text/css" href="{{ asset('css/adminplus/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    {{--<link type="text/css" href="assets/css/custom.css" rel="stylesheet">--}}
    <link type="text/css" href="{{ asset('css/adminplus/tokenfield.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('css/adminplus/typeahead.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('css/adminplus/sweet-alerts.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('css/adminplus/custom.css') }}" rel="stylesheet">


</head>

<body id="app" class="layout-container ls-top-navbar si-l3-md-up">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary navbar-full navbar-fixed-top">

    <!-- Toggle sidebar -->
    <button class="navbar-toggler pull-xs-left hidden-md-up" type="button" data-toggle="sidebar" data-target="#sidebarLeft"><span class="material-icons">menu</span></button>

    <!-- Brand -->
    <a href="/web" class="navbar-brand first-child-md"><img class="logo" src="./images/tdm-nav-logo.png"/></a>


    <!-- Menu -->
    <ul class="nav navbar-nav pull-xs-right nav-strip-right">


        <!-- User dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link active p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
                <i class="material-icons">settings</i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-list" aria-labelledby="Preview">
                {{--<a class="dropdown-item" href="#"><i class="material-icons md-18">account_circle</i> <span class="md-icon-text">Edit Account</span></a>--}}
                <a class="dropdown-item" href="{{ url('/logout') }}"
                   onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">Logout
                </a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </li>
        <!-- // END User dropdown -->

    </ul>
    <!-- // END Menu -->

</nav>
<!-- // END Navbar -->
<!-- Sidebar -->
<sidebar></sidebar>
<!-- // END Sidebar -->

<!-- Content -->
<div id="main-content" class="layout-content" data-scrollable>
    <div class="container-fluid">


        <router-view></router-view>



    </div>
</div>

<!-- Sound notifications -->
<div id="sound"></div>

<!-- jQuery -->
{{--<script src="assets/vendor/jquery.min.js"></script>--}}
<script src="{{ asset('js/adminplus/jquery.min.js') }}"></script>
<!-- Bootstrap -->
{{--<script src="assets/vendor/tether.min.js"></script>--}}
<script src="{{ asset('js/adminplus/tether.min.js') }}"></script>
{{--<script src="assets/vendor/bootstrap.min.js"></script>--}}
<script src="{{ asset('js/adminplus/bootstrap.min.js') }}"></script>

<!-- AdminPlus -->
{{--<script src="assets/vendor/adminplus.min.js"></script>--}}
<script src="{{ asset('js/adminplus/adminplus.min.js') }}"></script>


<!-- App JS -->
<script src="js/app.js"></script>

<!-- Main JS -->
{{--<script src="assets/js/main.min.js"></script>--}}
<script src="{{ asset('js/adminplus/main.min.js') }}"></script>

<!-- Plugins -->
{{--<script src="assets/vendor/bootstrap-datepicker.min.js"></script>--}}
<script src="{{ asset('js/adminplus/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/tags/tokenfield.min.js') }}"></script>
<script src="{{ asset('js/plugins/tags/prism.min.js') }}"></script>
<script src="{{ asset('js/plugins/typeahead/typeahead.bundle.min.js') }}"></script>
<script src="{{ asset('js/plugins/notifications/sweet_alert.min.js') }}"></script>

<script>
    window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
    ]); ?>
</script>

</body>

</html>