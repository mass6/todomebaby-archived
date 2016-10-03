<template>



    <div class="row">
        <div class="col-md-12">
            <h3 class="list-heading"><span v-show="showListName">{{ taskListPrefix }}{{ service.truncateText(taskList.listName, 40) }}</span><small v-if="taskList.listType == 'project'">
                <span class="project-edit clickable label label-primary-outline" @click.stop.prevent="editProject"  v-show="showListName">edit</span></small>&nbsp;</h3>
        </div>
    </div>

    <taskform :task="selectedTask" v-bind:task-list="taskList"></taskform>

    <!-- Task List -->

    <div class="row">

        <div class="card m-x-2 task-list">
            <ul class="list-group list-group-fit">


                <li v-for="task in taskList.tasks" track-by="id" class="list-group-item" id="task-row-{{ task.id }}" :class="{ 'row-active': task.id == selectedTask.id, 'row-complete': task.complete == true}" v-if="!task.complete">
                    <div class="media">
                        <div id="task-complete-{{ task.id }}" class="radio-media-left media-left media-middle">
                            <i class="radio-icon material-icons md-18 clickable" id="toggle-complete-{{ task.id }}" @click="toggleComplete(task)">
                                {{ task.complete == false ? 'radio_button_unchecked' : 'radio_button_checked' }}
                            </i>
                        </div>
                        <div id="task-title-selection-{{ task.id }}" class="media-body media-middle">
                            <h4 class="card-title m-b-0"><a href="javascript:void(0)" class="task-selectable task-title" @click="selectTask(task)">{{ task.title }}</a></h4>
                            <small><span v-for="tag in task.tags" class="clickable" v-bind:class="{ 'text-danger': tag.is_context, 'text-muted': ! tag.is_context }" @click.stop.prevent="getTasksByTag(tag)">{{ ! tag.is_context ? '#' : '' }}{{ tag.name }} </span></small>
                        </div>
                        <div class="media-right media-middle task-flags">

                            <!-- Due Date-->
                            <span class="task-date clickable datepicker" id="task-{{task.id}}-due-date" data-task="{{task.id}}" >
                                <span v-if="task.due_date" v-bind:class="{ 'text-danger': isPastDue(task) }">{{ task.due_date | shortDate }}</span>
                                <i v-else class="material-icons md-16 text-muted">
                                    event
                                </i>
                            </span>
                            <!-- /due date-->

                            <!-- Priority -->
                            <a class="nav-link active p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
                                <i class="tasklist-icon material-icons priority-flag" v-bind:class="priorityClass(task)">network_cell</i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-list priority-dropdown-menu" aria-labelledby="Preview">
                                <a class="dropdown-item" v-bind:class="{ 'active' : task.priority == 'low'}" href="Javascript:void (0)" @click.prevent="setPriority(task, 'low')"><i class="material-icons priority-flag text-success">network_cell</i> <span class="icon-text">Low</span></a>
                                <a class="dropdown-item" v-bind:class="{ 'active' : task.priority == 'medium'}" href="Javascript:void (0)" @click.prevent="setPriority(task, 'medium')"><i class="material-icons priority-flag text-warning">network_cell</i> <span class="icon-text">Medium</span></a>
                                <a class="dropdown-item" v-bind:class="{ 'active' : task.priority == 'high'}" href="Javascript:void (0)" @click.prevent="setPriority(task, 'high')"><i class="material-icons priority-flag text-danger">network_cell</i> <span class="icon-text">High</span></a>
                            </div>
                            <!-- /priority -->

                            <!-- Next -->
                            <i class="tasklist-icon material-icons text-danger clickable" @click="toggleNext(task)">{{ task.next ? 'star' : 'star_border' }}</i>
                            <!-- /next-->

                            <div v-if="task.project" style="width:100px" class="text-muted text-right project-link" >
                                <small data-project="{{task.project_id}}" class="truncated clickable" @click="getTasksByProject(task.project)">{{ task.project.name}}</small>
                            </div>
                        </div>
                    </div>
                </li>
                <li v-if="taskListEmpty" class="list-group-item">Way to go!  You have no open tasks in this list.</li>
            </ul>
        </div>

    </div>

    <!-- / task list -->


    <!-- Completed tasks -->



    <div id="toggle-show-completed" class="row">
        <div class="form-group col-md-12">
            <button class="btn btn-sm" v-bind:class="showCompletedClass" @click="toggleCompletedList">
                {{ !withCompletedTasks ? 'Show Completed Tasks' : 'Hide Completed Tasks' }}
            </button>
        </div>
    </div>



    <div class="card m-x-2 task-list task-list-completed" v-show="withCompletedTasks">
        <ul class="list-group list-group-fit">
            <li class="list-group-item" v-for="task in taskList.tasks" track-by="id" id="task-row-{{ task.id }}-completed" :class="{ 'row-active': task.id == selectedTask.id}" v-if="task.complete">
                <div class="media">
                    <div id="task-complete-{{ task.id }}-completed" class="radio-media-left media-left media-middle">
                        <i class="radio-icon material-icons md-18 clickable text-muted" id="toggle-complete-{{ task.id }}-completed" @click="toggleComplete(task)">
                            {{ task.complete == false ? 'radio_button_unchecked' : 'radio_button_checked' }}
                        </i>
                    </div>
                    <div id="task-title-selection-{{ task.id }}-completed" class="media-body media-middle">
                        <h4 class="card-title m-b-0"><a href="javascript:void(0)" class="text-muted task-selectable task-title" @click="selectTask(task)">{{ task.title }}</a></h4>
                        <span class="completed-at">Completed {{ completedAt(task.completed_at) }}</span>
                    </div>
                    <div class="media-right media-middle task-flags">

                        <!-- Due Date-->
                        <span class="task-date clickable datepicker" id="task-{{task.id}}-due-date-completed" data-task="{{task.id}}" >
                            <span v-if="task.due_date">{{ task.due_date | shortDate }}</span>
                            <i v-else class="material-icons md-16 text-muted">
                                event
                            </i>
                        </span>
                        <!-- /due date-->

                        <!-- Priority -->
                        <a class="nav-link active p-a-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false">
                            <i class="tasklist-icon material-icons priority-flag" v-bind:class="priorityClass(task)">network_cell</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-list priority-dropdown-menu" aria-labelledby="Preview">
                            <a class="dropdown-item" v-bind:class="{ 'active' : task.priority == 'low'}" href="Javascript:void (0)" @click.prevent="setPriority(task, 'low')"><i class="material-icons priority-flag text-success">network_cell</i> <span class="icon-text">Low</span></a>
                            <a class="dropdown-item" v-bind:class="{ 'active' : task.priority == 'medium'}" href="Javascript:void (0)" @click.prevent="setPriority(task, 'medium')"><i class="material-icons priority-flag text-warning">network_cell</i> <span class="icon-text">Medium</span></a>
                            <a class="dropdown-item" v-bind:class="{ 'active' : task.priority == 'high'}" href="Javascript:void (0)" @click.prevent="setPriority(task, 'high')"><i class="material-icons priority-flag text-danger">network_cell</i> <span class="icon-text">High</span></a>
                        </div>
                        <!-- /priority -->

                        <!-- Next -->
                        <i class="tasklist-icon material-icons next-flag clickable" @click="toggleNext(task)">{{ task.next ? 'star' : 'star_border' }}</i>
                        <!-- /next-->

                        <div v-if="task.project" style="width:100px" class="text-muted text-right project-link" >
                            <small data-project="{{task.project_id}}" class="truncated clickable" @click="getTasksByProject(task.project)">{{ task.project.name}}</small>
                        </div>
                    </div>
                </div>
            </li>
            <li v-show="displayCompletedTasks && ! hasCompletedTasks" class="list-group-item">No completed tasks in this list</li>
        </ul>
    </div>




    <!-- /completed tasks -->


</template>
<style scoped>
    section#task-list-container {
        margin-bottom:36px;
    }
    span.project-edit.clickable {
        margin-left: 10px;
        font-size: .65em;
        vertical-align: super;
        cursor: pointer;
    }
    table.tasks-list {
        margin-bottom: 20px;
    }
    table.tasks-list-open>tbody>tr:last-child {
        border-bottom: 1px solid #dddddd;
    }
    .table-lg > tbody > tr > td.check-complete {
        padding: 15px 10px;
        vertical-align: top;
        line-height: 26px;
    }
    td.check-complete { width:40px;}
    .check-complete > i {font-size: 1.3em;}
    .table-lg > tbody > tr > td.task-title {padding: 17px 5px 5px; vertical-align: top;}

    td.check-complete > .icon-checkbox-unchecked2 {
        cursor: pointer;
        color: #025f97;
    }
    .icon-checkbox-checked2.blue {
        color: #0277BD;
        cursor: pointer;
    }
    .due-date-picker {
        width: 150px;
    }
    .bordered-date > .input-group-addon {
        padding: 2px 2px 2px 10px;
        border: 1px solid #dadada;
    }
    .bordered-date .form-control {
        padding-left: 10px;
        border: 1px solid #dadada;
        border-left: none;
    }
    .picker--opened .picker__holder {
        min-width: 250px;
    }
    i.icon-star-empty3, i.icon-star-full2 {
        color:green;
        font-size: 2.3em;
        line-height: 46px;
    }
    .table>tbody>tr>td.check {
        padding: 12px 2px;
        width: 20px;
    }
    /*#context-tasks.table-responsive, #completed-tasks.table-responsive {*/
        /*min-height: 101px;*/
        /*border:none;*/
        /*overflow-x: visible;*/
    /*}*/
    .card.task-list.task-list-completed li.list-group-item {
        background-color: #efebeb;
        border-radius: 6px;
    }
    .card.task-list.task-list-completed li.list-group-item h4 {
        text-decoration: line-through;
    }
    .table-striped>tbody>tr.task-item, .table>tbody>tr.task-item {
        border-left: 6px solid white;
    }
    .card.task-list, .card.task-list li {
        border: none;
        box-shadow: none;
    }
    .card.task-list li {
        border-bottom: 1px solid #eceeef;
        margin: 1px 0;
    }
    li.list-group-item.row-active {
        border-left: 4px solid #ba0000;
        margin-left: -4px;
        background-color: #FDFAFA;
    }
    li.list-group-item.row-active {
        border-left: 4px solid #ba0000;
        margin-left: -4px;
        background-color: #FDFAFA;
    }
    .button-complete {
        margin-bottom:10px;
        width:148px;
        text-align: left;
    }
    .table.tasks-list-complete>tbody>tr.row-complete {
        background-color: #f6f6f6;
        color: #7e7e7e;
        font-size: .9em;
        border: 2px solid white;
    }
    .row-complete span.task-selectable.task-title {
        color: #7e7e7e;
        opacity: .6;
    }
    .tasks-list-complete td.task-title {
        padding:0;
    }
    .completed-at {
        margin-top: 5px;
        font-style: italic;
        font-size: .65rem;
    }
    .fade-transition {
        transition: opacity .5s ease;
    }
    .fade-leave {
        opacity: 0;
    }
    .fade-enter {
        opacity: 0;
        -webkit-transition-delay: .25s; /* Safari */
        transition-delay: .25s;
    }
    .task-selectable.task-title {font-size:1.1em;color: #042a4a;}
    a.task-selectable.task-title {text-decoration: none;}
    .task-selectable.task-title:hover {
        border-bottom: 1px dashed #427ef5;
        display: inline;
    }
    .tag-selectable:hover {cursor: pointer;}
    .tag-block {margin-top: 5px;margin-left:8px;font-size: .85em;}
    div.tag-block > span.tag-selectable {margin-right: 2px;}
    span.project-link {
        font-size: .85em;
        color: #9E9E9E;
    }
    span.project-link:hover {
        cursor: pointer;
    }
    .tasklist-icon {
        vertical-align: middle;
        width: 18px;
        font-size: 18px;
        display: inline-block;
        line-height: normal;
        position: relative;
    }
    .clickable {
        cursor: pointer;
    }
    .label-primary-outline {
        background-color: #ffffff;
        border: 1px solid #EF5350;
        color: #EF5350;
    }
    .label-primary-outline[href]:focus, .label-primary-outline[href]:hover {
        background-color: #EF5350;
        color: #ffffff;
    }
</style>
<script>

    import { store } from '../store'
    import { service } from '../service'
    import { repo } from '../repository'
    import TaskForm from './TaskForm.vue'
    export default{
        data(){
            return {
                sharedState: store.state,
                service: service,
                repo: repo,
                taskList: {
                    listName: '',
                    tasks: [],
                    listId: null,
                    listType: null,
                    listPath: null
                },
                showListName: false,
                displayTaskList: false,
                withCompletedTasks: false,
                displayCompletedTasks: false,
                selectedTask: {id: null, title: '', tags: []}
            }
        },
        computed: {
            taskListEmpty: function() {
                return ! Boolean(this.taskList.tasks.filter(function(task) {
                    return ! task.complete;
                }).length);
            },
            hasCompletedTasks: function() {
                return Boolean(this.taskList.tasks.filter(function(task) {
                    return task.complete;
                }).length);
            },
            taskListPrefix: function() {
                let scheduled = ['Today', 'Tomorrow', 'This Week', 'Next Week', 'Future'];
                if (scheduled.indexOf(this.taskList.listName) !== -1)
                        return 'Due ';
                if (this.taskList.listType == 'tag' && this.taskList.listName.charAt(0) !== '@')
                        return '#';
            },
            showCompletedClass: function() {
                return this.withCompletedTasks == true ? 'btn-primary' : 'btn-primary-outline';
            }
        },
        components: {
            'taskform': TaskForm
        },
        route: {
            data: function (transition) {
                if (this.isNotAnEditRequest() && this.listShouldBeUpdated()) {
                    this.updateTaskList();
                }
                this.withCompletedTasks = false;
            }
        },
        methods: {
            isNotAnEditRequest: function() {
                return this.$route.name !== 'tasks.edit';
            },
            listShouldBeUpdated: function () {
                return this.$route.path !== this.taskList.path || this.taskList.listName == '';
            },
            updateTaskList: function() {
                this.$broadcast('taskListUpdated');
                this.showListName = false; // hide task list while data is being fetched
                this.displayTaskList = false; // hide task list while data is being fetched
                this.displayCompletedTasks = false; // hide task list while data is being fetched
                this.taskList.listName = '';
                this.fetchTasks(this.getListType(), this.getListId());
            },
            getListType: function(action) {
                if (action == 'refresh') {
                    return this.taskList.listType ? this.taskList.listType : this.sharedState.defaultRoute.listType;
                }
                return this.$route.listType;
            },
            getListId: function(action) {
                if (action == 'refresh') {
                    return this.taskList.listId ? this.taskList.listId : this.sharedState.defaultRoute.params.id;
                }
                return this.$route.params.id ? this.$route.params.id : this.sharedState.defaultRoute.params.id;
            },
            fetchTasks: function(listType, listId) {
                var that = this;
                this.repo.fetchTaskList(listId, listType, this.withCompletedTasks, function(result) {
                    that.sharedState.tasks = result;
                    that.taskList = result;
                    that.taskList.listType = listType;
                    that.taskList.listId = listId;
                    that.taskList.listPath = that.$route.path;
                    that.showListName = true;
                    that.displayTaskList = true;
                    that.displayCompletedTasks = that.withCompletedTasks;
                    that.$nextTick(function(){
                        that.initializeDueDatePickers();
                    });
                });
            },
            refreshTaskList: function() {
                this.fetchTasks(this.getListType('refresh'), this.getListId('refresh'));
                this.unSelectTask();
            },
            getTaskById: function (taskId) {
                return this.taskList.tasks.filter(function(task) {
                    return task.id == taskId;
                })[0];
            },
            getTasksByProject: function(project) {
                this.$route.router.go({name: 'projects.show', params:{id: project.slug}});
            },
            getTasksByTag: function(tag) {
                this.$route.router.go({name: 'tags.show', params:{id: tag.slug}});
            },
            initializeDueDatePickers: function() {
                var that = this;
                $(".datepicker").datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    todayBtn: 'linked',
                    clearBtn: true,
                    calendarWeeks: true,
                    weekStart: 1,
                    autoclose: true
                }).on('changeDate', function(e) {
                    let taskId = this.getAttribute('data-task');
                    let task = that.getTaskById(taskId);
                    task.due_date = e.format('yyyy-mm-dd');
                    that.updateTask(task);
                });
            },
            selectTask: function(task) {
                this.selectedTask = Object.assign({}, task);
                this.$broadcast('taskSelected', task);
                this.$route.router.go('/tasks/' + task.id + '/edit');
            },
            unSelectTask: function(task) {
                this.selectedTask = {title: '', next: false};
            },
            toggleComplete: function(task) {
                task.complete = ! task.complete;
                if (task.complete) {
                    service.playSound('ding');
                }
                this.updateTask(task);
            },
            toggleNext: function(task) {
                task.next = ! task.next;
                this.updateTask(task);
            },
            setPriority: function(task, priority) {
                if (task.priority != priority) {
                    task.priority = priority;
                    this.updateTask(task);
                }
            },
            updateTask: function (task) {
                var that = this;
                service.saveTask(task, function(){
//                    that.refreshTaskList();
                });
            },
            setListName: function(name) {
                this.taskList.listName =  this.toTitleCase(this.hyphensToSpaces(name));
            },
            hyphensToSpaces: function(string) {
                return string.replace(/-/g, " ");
            },
            toTitleCase: function(string){
                return string.replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
            },
            editProject: function() {
                this.$route.router.go({name: 'projects.edit', params: {id: this.taskList.listId}});
            },
            isPastDue: function(task) {
                return moment(task.due_date).isBefore(moment().format('YYYY-MM-DD'));
            },
            toggleCompletedList: function() {
                this.withCompletedTasks = ! this.withCompletedTasks;
                if (this.withCompletedTasks){
                    this.refreshTaskList(this.withCompletedTasks);
                }
            },
            completedAt: function(timestamp) {
                if (timestamp == 'undefined' || timestamp == null) {
                    return 'recently';
                } else {
                    return timestamp;
                }
            },
            priorityClass: function(task) {
                let classes = {
                    low: 'text-success',
                    medium: 'text-warning',
                    high: 'text-danger'
                };
                return classes[task.priority];
            }
        },
        filters: {
            shortDate: function(date) {
                if (date == null) {return;}
                return moment(date, "YYYY-MM-DD").format('DD MMM');
            }
        },
        events: {
            taskFormDeactivated: function() {
                this.unSelectTask();
            },
            taskSaved: function() {
                this.refreshTaskList();
                this.$route.router.go({path: this.taskList.listPath});
            },
            taskDeleted: function() {
                this.refreshTaskList();
                this.$route.router.go({path: this.taskList.listPath});
            }
        }

    }
</script>