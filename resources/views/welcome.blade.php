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
                    <span>Username</span>
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
        <div class="sidebar sidebar-main bg-blue-800">
            <div class="sidebar-content">


                <!-- Main navigation -->
                <div class="sidebar-category sidebar-category-visible">
                    <div class="category-content no-padding">
                        <ul class="navigation navigation-main navigation-accordion">

                            <!-- Main -->
                            <!-- <li class="active"><a href="../index.html"><i class="icon-home4"></i> <span>Dashboard</span></a></li> -->
                            <li class="">
                                <a href="Javascript:void(0)"><i class="icon-calendar"></i> <span>Due Date</span></a>
                                <ul>
                                    <li><a href="#"><span>Today</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="scheduledTaskCounts.today">3</span></a></li>
                                    <li><a href="#"><span>Tomorrow</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="scheduledTaskCounts.today">3</span></a></li>
                                    <li><a href="#" class="sidebar-active"><span>This Week</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="scheduledTaskCounts.today">4</span></a></li>
                                    <li><a href="#"><span>Next Week</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="scheduledTaskCounts.today">3</span></a></li>
                                    <li><a href="#"><span>Future</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="scheduledTaskCounts.today">3</span></a></li>
                                </ul>
                            </li>
                            <li class="">
                                <a href="#"><i class="icon-stack"></i> <span>Projects</span></a>
                                <ul>
                                    <a href="#" id="add-project" class="add-project pull-right" alt="Add Project"><i class="icon-plus-circle2"></i></a>
                                    <li><a href="#"><span>Project Alpha</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="taskCounts[project.id]">2</span></a></li>
                                    <li><a href="#"><span>Project Bravo</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="taskCounts[project.id]">2</span></a></li>
                                    <li><a href="#"><span>Project Charlie</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="taskCounts[project.id]">2</span></a></li>
                                </ul>
                            </li>
                            <li class="">
                                <a href="Javascript:void(0)"><i class="icon-question3"></i> <span>Contexts</span></a>
                                <ul>
                                    <li><a href="#"><span>@home</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="taskCounts[project.id]">2</span></a></li>
                                    <li><a href="#"><span>@work</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="taskCounts[project.id]">2</span></a></li>
                                    <li><a href="#"><span>@travel</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="taskCounts[project.id]">2</span></a></li>
                                </ul>
                            </li>
                            <!-- /main -->

                        </ul>
                    </div>
                </div>
                <!-- /main navigation -->

            </div>
        </div>
        <!-- /main sidebar -->


        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Page header -->

            <!-- /page header -->


            <!-- Content area -->
            <div class="content no-padding">

                <!-- Simple panel -->
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Simple panel</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="close"></a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body">
                        <h6 class="text-semibold">Base layout</h6>
                    </div>
                </div>

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

</body>
</html>
