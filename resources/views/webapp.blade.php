<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Limitless - Responsive Web Application Kit by Eugene Kopyov</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/css/theme.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="/temp/jquery.min.js"></script>
    <script type="text/javascript" src="/temp/bootstrap.min.js"></script>
    <script type="text/javascript" src="/temp/limitless-app.js"></script>
    <!-- /core JS files -->

    <!-- App JS files -->
    <script type="text/javascript" src="/js/app.js"></script>
    <!-- /app JS files -->



</head>

<body class="navbar-top">
    <div id="app">
        <!-- Main navbar -->
        <div class="navbar navbar-default navbar-fixed-top header-highlight">
            <div class="navbar-header bg-blue-tdm">
                <a class="navbar-brand" href="web"><img src="/images/logo.png" alt="Todomebaby"></a>

                <ul class="nav navbar-nav visible-xs-block">
                    <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
                    <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
                </ul>
            </div>

            <div class="navbar-collapse collapse" id="navbar-mobile">
                <ul class="nav navbar-nav">
                    <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">

                    <li class="dropdown dropdown-user">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="caret"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>
                            <li><a href="#"><i class="icon-cog5"></i> Account settings</a></li>
                            <li class="divider"></li>
                            <li><a href="#"><i class="icon-switch2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /main navbar -->


        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <!-- Main sidebar -->
                <sidebar></sidebar>
                <!-- /main sidebar -->


                <!-- Main content -->
                <div class="content-wrapper">

                    <!-- Page header -->

                    <!-- /page header -->


                    <!-- Content area -->
                    <div class="content no-padding">

                        <router-view></router-view>

                        <!-- Footer -->
                        <div class="footer text-muted">
                            &copy; 2016. <a href="Javascript:void(0)">TodoMeBaby</a> by <a href="http://charmwebservices.com" target="_blank">Charm Web Services</a>
                        </div>
                        <!-- /footer -->

                    </div>
                    <!-- /content area -->

                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

        </div>
        <!-- /page container -->
    </div>
<!-- Theme JS files -->
<script type="text/javascript" src="/js/app.js"></script>
<!-- Pickadate -->
<script type="text/javascript" src="/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="/js/plugins/pickers/pickadate/legacy.js"></script>
<!-- /pickadate -->
<script>
    window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
    ]); ?>
</script>
</body>
</html>
