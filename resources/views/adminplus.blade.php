<!DOCTYPE html>
<html class="bootstrap-layout">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tickets</title>

    <!-- Prevent the demo from appearing in search engines (REMOVE THIS) -->
    <meta name="robots" content="noindex">

    <!-- Material Design Icons  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Roboto Web Font -->
    <!--  <link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300">
    <!-- App CSS -->
    <link type="text/css" href="css/adminplus/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/adminplus/bootstrap-datepicker.min.css">
    <link type="text/css" href="css/adminplus/custom.css" rel="stylesheet">


</head>

<body class="layout-container ls-top-navbar si-l3-md-up">

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary navbar-full navbar-fixed-top">

    <!-- Toggle sidebar -->
    <button class="navbar-toggler pull-xs-left hidden-md-up" type="button" data-toggle="sidebar" data-target="#sidebarLeft"><span class="material-icons">menu</span></button>

    <!-- Brand -->
    <a href="index.html" class="navbar-brand first-child-md"><img class="logo" src="./assets/images/logo-red-32.png"/></a>


    <!-- Menu -->
    <ul class="nav navbar-nav pull-xs-right nav-strip-right">


        <!-- User dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link active p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
                <i class="material-icons">settings</i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-list" aria-labelledby="Preview">
                <a class="dropdown-item" href="#"><i class="material-icons md-18">lock</i> <span class="icon-text">Edit Account</span></a>
                <a class="dropdown-item" href="#"><i class="material-icons md-18">person</i> <span class="icon-text">Public Profile</span></a>
                <a class="dropdown-item" href="#">Logout</a>
            </div>
        </li>
        <!-- // END User dropdown -->

    </ul>
    <!-- // END Menu -->

</nav>
<!-- // END Navbar -->
<!-- Sidebar -->
<div class="sidebar sidebar-left si-si-3 sidebar-visible-md-up sidebar-light ls-top-navbar-xs-up sidebar-transparent-md" id="sidebarLeft" data-scrollable>
    <ul class="sidebar-menu">
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="index.html">
                <i class="sidebar-menu-icon material-icons">home</i> Inbox
            </a>
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="appointments.html">
                <i class="sidebar-menu-icon material-icons">today</i> Next
                <span class="sidebar-menu-label label label-primary">10</span>
            </a>
        </li>
        <li class="sidebar-divider">
        </li>
        <li class="sidebar-menu-item">
            <a class="sidebar-menu-button" href="learning-course.html">
                <i class="sidebar-menu-icon material-icons">date_range</i> Due Date
            </a>
            <ul class="sidebar-submenu">
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="property.html">
                        <i class="sidebar-menu-icon material-icons">today</i> Today
                        <span class="sidebar-menu-label label label-primary">10</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="property.html">
                        <i class="sidebar-menu-icon material-icons">event</i> Tomorrow
                        <span class="sidebar-menu-label label label-primary">10</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="property.html">
                        <i class="sidebar-menu-icon material-icons">view_week</i> This Week
                        <span class="sidebar-menu-label label label-primary">10</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="property.html">
                        <i class="sidebar-menu-icon material-icons">next_week</i> Next Week
                        <span class="sidebar-menu-label label label-primary">10</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a class="sidebar-menu-button" href="property.html">
                        <i class="sidebar-menu-icon material-icons">forward</i> Future
                        <span class="sidebar-menu-label label label-primary">10</span>
                    </a>
                </li>

            </ul>

        </li>

        </li>
        <li class="sidebar-divider">
        </li>

        <li class="sidebar-menu-item">


            <div class="category-tabs">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#projects-tab" data-toggle="tab">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contexts-tab" data-toggle="tab">Contexts</a>
                    </li>
                    <!--
                                  <li class="nav-item">
                                    <a class="nav-link" href="#tags-tab" data-toggle="tab">Tags</a>
                                  </li>
                    -->
                </ul>
                <div class="card-block tab-content">
                    <div class="tab-pane active" id="projects-tab">

                        <ul class="list-unstyled">
                            <li class="submenu-item">
                                <a class="sidebar-menu-button project-link" href="property.html">Project One
                                    <span class="sidebar-menu-label label label-primary">10</span>
                                </a>
                            </li>
                            <li class="submenu-item">
                                <a class="sidebar-menu-button project-link" href="property.html">Project Two
                                    <span class="sidebar-menu-label label label-primary">10</span>
                                </a>
                            </li>
                            <li class="submenu-item">
                                <a class="sidebar-menu-button project-link" href="property.html">Project Three
                                    <span class="sidebar-menu-label label label-primary">10</span>
                                </a>
                            </li>
                            <li class="submenu-item">
                                <a class="sidebar-menu-button project-link new-project" href="property.html">+ New Project
                                </a>
                            </li>
                        </ul>

                    </div>
                    <div class="tab-pane" id="contexts-tab">

                        <ul class="list-unstyled">
                            <li class="submenu-item">
                                <a class="sidebar-menu-button context-link" href="property.html">@home
                                    <span class="sidebar-menu-label label label-primary">10</span>
                                </a>
                            </li>
                            <li class="submenu-item">
                                <a class="sidebar-menu-button context-link" href="property.html">@office
                                    <span class="sidebar-menu-label label label-primary">10</span>
                                </a>
                            </li>
                            <li class="submenu-item">
                                <a class="sidebar-menu-button context-link" href="property.html">@travel
                                    <span class="sidebar-menu-label label label-primary">10</span>
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>


        </li>
    </ul>
</div>
<!-- // END Sidebar -->

<!-- Content -->
<div id="main-content" class="layout-content" data-scrollable>
    <div class="container-fluid">


        <div class="row">
            <div class="col-md-12">
                <h3 class="list-heading"><span>Internet of Things Festival</span></h3>
            </div>


        </div>

        <div id="task-form">

            <div class="form-group">
                <i class="material-icons md-36 text-danger">check_box_outline_blank</i>
                <input id="task-title" name="task-title" type="text" class="form-control inline-control" placeholder="Add a task" />
            </div>


            <section v-if="editMode" transition="expand">

                <div class="form-group" v-if="task.id">

                </div>


                <div class="row">

                    <!-- Due Date -->
                    <div class="col-md-6">
                        <label for="task-due-date">Due Date</label>
                        <div class="input-group">
                            <span class="input-group-addon addon-sm"><i class="sidebar-menu-icon material-icons">date_range</i></span>
                            <input v-model="task.due_date" id="task-due-date" name="task-due-date" type="text" class="form-control input-sm datepicker" placeholder="Due Date">
                        </div>

                    </div>
                    <!-- / due date -->

                    <!-- Priority -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="task-priority">Priority</label>
                            <select v-model="task.priority" id="task-priority" name="task-priority" class="form-control input-select">
                                <option value="LOW" selected>Low</option>
                                <option value="MED">Medium</option>
                                <option value="HGH">High</option>
                            </select>
                        </div>

                    </div>
                    <!-- / priority -->

                </div>
                <br/>
                <!-- /due date-->

                <!-- Tags -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- Using typeahead -->
                        <div class="form-group">
                            <label for="task-tagsinput">Tags</label>
                            <input v-model="task.tagsinput" id="task-tagsinput" name="task-tagsinput" type="text" class="form-control tokenfield-typeahead input-sm" value="">
                        </div>
                        <!-- /using typeahead -->

                    </div>
                </div>
                <!-- /tags -->

                <!-- Project -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="task-project">Project</label>
                            <select v-model="task.project_id" id="task-project" name="task-project" class="form-control input-select">
                                <option value> -- None -- </option>
                                <option v-for="project in sharedState.projects" :value="project.id" v-bind:selected="project.slug == taskList.listId">
                                    @{{ project.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <br/>
                <!-- /project -->


                <!-- Details -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea v-model="task.details" id="task-details" name="task-details" rows="4" cols="4" class="form-control" placeholder="Task Details"></textarea>
                        </div>
                    </div>
                </div>
                <!-- /details -->

                <div class="row form-actions">
                    <div class="col-md-12">
                        <button type="submit" id="save-task-button" class="btn btn-success-outline btn-sm" v-bind:class="{ 'disabled': !task.title.length }" @click.stop.prevent="saveTask">Save Task <i class="icon-checkmark3 position-right"></i></button>
                        <button class="btn btn-primary-outline btn-sm" @click="cancelForm">Cancel </button>
                        <button v-if="task.id" type="button" class="btn btn-primary-outline btn-sm" id="delete-task-button" @click="deleteTask(task)">Delete <i class="icon-bin position-right"></i></button>
                    </div>
                </div>
            </section>
        </div>


        <!-- Task List -->

        <div class="row">

            <div class="card">
                <ul class="list-group list-group-fit">
                    <li class="list-group-item">
                        <div class="media">
                            <div class="radio-media-left media-left media-middle">
                                <i class="radio-icon material-icons md-18">radio_button_unchecked</i>
                            </div>
                            <div class="media-body media-middle">
                                <h4 class="card-title m-b-0"><a href="#">I Need help with notifications on desktop</a></h4>
                                <small><span class="context-tags">@work @phone</span> <span class="text-muted">#errands #shopping</span></small>
                            </div>
                            <div class="media-right media-middle task-flags">
                                <span class="task-date">24 Dec</span>
                                <a class="nav-link active p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
                                    <i class="tasklist-icon material-icons priority-flag text-success">network_cell</i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-list priority-dropdown-menu" aria-labelledby="Preview">
                                    <a class="dropdown-item active" href="#"><i class="material-icons priority-flag text-success">network_cell</i> <span class="icon-text">Low</span></a>
                                    <a class="dropdown-item" href="#"><i class="material-icons priority-flag text-warning">network_cell</i> <span class="icon-text">Medium</span></a>
                                    <a class="dropdown-item" href="#"><i class="material-icons priority-flag text-danger">network_cell</i> <span class="icon-text">High</span></a>
                                </div>
                                <i class="tasklist-icon material-icons next-flag">star</i>

                                <div style="width:100px" class="text-muted text-right project-link">
                                    <small>My Great Project</small>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="media">
                            <div class="radio-media-left media-left media-middle">
                                <i class="radio-icon sidebar-menu-icon material-icons md-18">radio_button_unchecked</i>
                            </div>
                            <div class="media-body media-middle">
                                <h4 class="card-title m-b-0"><a href="#">How can I manually toggle the Sidebar?</a></h4>
                                <small><span class="text-danger">@work @phone</span> <span class="text-muted">#errands #shopping</span></small>
                            </div>
                            <div class="media-right media-middle task-flags">
                                <span class="task-date"></span>
                                <a class="nav-link active p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
                                    <i class="tasklist-icon material-icons priority-flag text-danger">network_cell</i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-list priority-dropdown-menu" aria-labelledby="Preview">
                                    <a class="dropdown-item" href="#"><i class="material-icons priority-flag text-success">network_cell</i> <span class="icon-text">Low</span></a>
                                    <a class="dropdown-item" href="#"><i class="material-icons priority-flag text-warning">network_cell</i> <span class="icon-text">Medium</span></a>
                                    <a class="dropdown-item active" href="#"><i class="material-icons priority-flag text-danger">network_cell</i> <span class="icon-text">High</span></a>
                                </div>
                                <i class="tasklist-icon material-icons next-flag">star</i>

                                <div style="width:100px" class="text-muted text-right project-link">
                                    <small>Festival in the V...</small>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="media">
                            <div class="radio-media-left media-left media-middle">
                                <i class="radio-icon sidebar-menu-icon material-icons md-18">radio_button_unchecked</i>
                            </div>
                            <div class="media-body media-middle">
                                <h4 class="card-title m-b-0"><a href="#">Building a Chat Application</a></h4>
                                <small><span class="text-danger">@work @phone</span> <span class="text-muted">#errands #shopping</span></small>
                            </div>
                            <div class="media-right media-middle task-flags">
                                <span class="task-date text-danger">24 Dec</span>
                                <a class="nav-link active p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
                                    <i class="tasklist-icon material-icons priority-flag text-warning">network_cell</i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-list priority-dropdown-menu" aria-labelledby="Preview">
                                    <a class="dropdown-item" href="#"><i class="material-icons priority-flag text-success">network_cell</i> <span class="icon-text">Low</span></a>
                                    <a class="dropdown-item active" href="#"><i class="material-icons priority-flag text-warning">network_cell</i> <span class="icon-text">Medium</span></a>
                                    <a class="dropdown-item" href="#"><i class="material-icons priority-flag text-danger">network_cell</i> <span class="icon-text">High</span></a>
                                </div>
                                <i class="tasklist-icon material-icons next-flag-empty">star_bordered</i>

                                <div style="width:100px" class="text-muted text-right project-link">
                                    <small>My Great Project</small>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="media">
                            <div class="radio-media-left media-left media-middle">
                                <i class="radio-icon sidebar-menu-icon material-icons md-18">radio_button_unchecked</i>
                            </div>
                            <div class="media-body media-middle">
                                <h4 class="card-title m-b-0"><a href="#">Set a new Linux development server</a></h4>
                                <small><span class="text-danger">@work @phone</span> <span class="text-muted">#errands #shopping</span></small>
                            </div>
                            <div class="media-right media-middle task-flags">
                                <span class="task-date text-danger">24 Dec</span>
                                <a class="nav-link active p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
                                    <i class="tasklist-icon material-icons priority-flag text-success">network_cell</i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-list priority-dropdown-menu" aria-labelledby="Preview">
                                    <a class="dropdown-item active" href="#"><i class="material-icons priority-flag text-success">network_cell</i> <span class="icon-text">Low</span></a>
                                    <a class="dropdown-item" href="#"><i class="material-icons priority-flag text-warning">network_cell</i> <span class="icon-text">Medium</span></a>
                                    <a class="dropdown-item" href="#"><i class="material-icons priority-flag text-danger">network_cell</i> <span class="icon-text">High</span></a>
                                </div>
                                <i class="tasklist-icon material-icons next-flag-empty">star_bordered</i>
                                <div style="width:100px" class="text-muted text-right project-link">
                                    <small>House Remode...</small>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        <!-- / task list -->


        <!-- Completed tasks -->

        <div id="toggle-show-completed" class="row">
            <div class="col-md-12">
                <button class="btn btn-info-outline btn-sm" >Show completed tasks</button>
            </div>
        </div>

        <!-- /completed tasks -->


    </div>
</div>

<!-- jQuery -->
<script src="js/adminplus/jquery.min.js"></script>

<!-- Bootstrap -->
<script src="js/adminplus/tether.min.js"></script>
<script src="js/adminplus/bootstrap.min.js"></script>

<!-- AdminPlus -->
<script src="js/adminplus/adminplus.min.js"></script>

<!-- App JS -->

<script src="js/app.js"></script>
<script src="s/adminplus/main.min.js"></script>

<!-- Plugins -->
<script src="js/adminplus/bootstrap-datepicker.min.js"></script>

<script>

    $(".datepicker").datepicker({
        format: "yy-mm-dd",
        todayHighlight: true,
        todayBtn: 'linked',
        clearBtn: true,
        calendarWeeks: true,
        weekStart: 1,
        autoclose: true

    });


</script>

</body>

</html>