<template>

    <!-- Simple panel -->
    <div class="panel panel-flat app-panel">

        <div class="panel-heading">
            <h6 class="panel-title list-heading"><span v-show="showListName">{{ taskListPrefix }}{{ taskList.listName }}<small v-if="taskList.listType == 'project'">
                <span class="project-edit clickable label label-default" @click.stop.prevent="editProject">edit</span></small></span>&nbsp;</h6>
        </div>

        <div class="panel-body">

            <taskform :task="selectedTask" v-bind:task-list="taskList"></taskform>
            <br/>

            <section id="task-list-container" v-show="showListName">

                <!--  Open Tasks List -->
                <div id="open-tasks" class="table-responsive">
                    <table class="table tasks-list tasks-list-open table-lg" v-show="displayTaskList">
                        <thead v-show="!taskListEmpty">
                            <tr class="border-double">
                                <th colspan="2">Task</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                                <th>Next</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-if="taskListEmpty"><td>Way to go!  You have no open tasks in this list.</td></tr>
                        <tr class="task-item" v-for="task in taskList.tasks" track-by="id" id="task-row-{{ task.id }}" :class="{ 'row-active': task.id == selectedTask.id, 'row-complete': task.complete == true}" v-if="!task.complete">
                            <!-- Complete Box -->
                            <td class="check-complete" id="task-complete-{{ task.id }}"><i class="blue" :class="{ 'icon-checkbox-unchecked2': task.complete == false, 'icon-checkbox-checked2': task.complete == true}" id="toggle-complete-{{ task.id }}" @click="toggleComplete(task)"></i></td>
                            <!-- /complete box -->

                            <!-- Task Title -->
                            <td class="task-title" id="task-title-selection-{{ task.id }}">
                                <div>
                                    <a href="javascript:void(0)" class="task-selectable task-title" @click="selectTask(task)">{{ task.title }} </a>
                                    <span v-if="task.project" class="project-link" data-project="{{task.project_id}}" @click="getTasksByProject(task.project)">({{ task.project.name}})</span><br/>
                                    <!-- Tags Block -->
                                    <div class="tag-block">
                                        <a href="javascript:void(0)" v-for="tag in task.tags" class="tag-selectable text-teal-700" @click.stop.prevent="getTasksByTag(tag)"> {{ ! tag.is_context ? '#' : '' }}{{ tag.name }} </a>
                                    </div>
                                    <!-- /tags block -->
                                </div>
                            </td>
                            <!-- / task title-->

                            <td>
                                <div class="btn-group">
                                    <a href="#" class="label dropdown-toggle"
                                       :class="{ 'label-danger': task.priority == 'high', 'label-primary': task.priority == 'medium', 'label-success': task.priority == 'low' }"
                                       data-toggle="dropdown">
                                        {{ task.priority ? task.priority.toUpperCase(): ''}}
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li :class="{ 'active': task.priority == 'high'}"><a href="#" @click.prevent="setPriority(task, 'high')"><span class="status-mark position-left bg-danger"></span> High</a></li>
                                        <li :class="{ 'active': task.priority == 'medium'}"><a href="#" @click.prevent="setPriority(task, 'medium')"><span class="status-mark position-left bg-primary"></span> Medium</a></li>
                                        <li :class="{ 'active': task.priority == 'low'}"><a href="#" @click.prevent="setPriority(task, 'low')"><span class="status-mark position-left bg-success"></span> Low</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-transparent bordered-date">
                                    <div class="input-group-addon"><i class="icon-calendar3 position-left"></i></div>
                                    <input v-model="task.due_date" id="task-{{task.id}}-due-date" data-task="{{task | json}}" type="text" class="form-control datepicker" v-bind:class="{ 'text-danger': isPastDue(task) }">
                                </div>
                            </td>
                            <td><i class="task-next" :class="{ 'icon-star-empty3': task.next == false, 'icon-star-full2': task.next == true}" @click="toggleNext(task)"><a href="javascript:void(0)" id="task-next">&nbsp;</a></i></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <br/>

                <button class="btn btn-xs bg-blue-tdm text-white button-complete" @click="toggleCompletedList">{{ !withCompletedTasks ? 'Show Completed Tasks' : 'Hide Completed Tasks' }}</button>

                <!--  Completed Tasks List -->
                <div id="completed-tasks" class="table-responsive">
                    <table class="table tasks-list tasks-list-complete table-xs" v-show="withCompletedTasks">
                        <tbody>
                        <tr class="task-item row-complete" v-for="task in taskList.tasks" track-by="id" id="task-row-{{ task.id }}-completed" :class="{ 'row-active': task.id == selectedTask.id}" v-show="task.complete">
                            <!-- Complete Box -->
                            <td class="check-complete" id="task-complete-{{ task.id }}-completed"><i class="blue" :class="{ 'icon-checkbox-unchecked2': task.complete == false, 'icon-checkbox-checked2': task.complete == true}" id="toggle-complete-{{ task.id }}-completed" @click="toggleComplete(task)"></i></td>
                            <!-- /complete box -->

                            <!-- Task Title -->
                            <td class="task-title" id="task-title-selection-{{ task.id }}-completed">
                                <a href="javascript:void(0)" class="task-selectable task-title line-through" @click="selectTask(task)">{{ task.title }} </a>
                                <span v-if="task.project" class="project-link" >({{ task.project.name}})</span><br/>
                                <p class="completed-at">Completed {{ completedAt(task.completed_at) }}</p>
                            </td>
                            <!-- / task title-->
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

</template>
<style>
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
    .table>tbody>tr>td.check {
        padding: 12px 2px;
        width: 20px;
    }
    /*#context-tasks.table-responsive, #completed-tasks.table-responsive {*/
        /*min-height: 101px;*/
        /*border:none;*/
        /*overflow-x: visible;*/
    /*}*/
    .table-striped>tbody>tr.task-item, .table>tbody>tr.task-item {
        border-left: 6px solid white;
    }
    .table-striped>tbody>tr.task-item.row-active, .table>tbody>tr.task-item.row-active {
        background-color: #F9F9F9;
        border-left: 6px solid #055D92;
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
</style>
<script>

    import { store } from '../store'
    import TaskForm from './TaskForm.vue'
    export default{
        data(){
            return {
                sharedState: store.state,
                store: store,
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
                selectedTask: {id: null, title: '', tags: []}
            }
        },
        computed: {
            taskListEmpty: function() {
                return ! this.taskList.tasks.filter(function(task) {
                    return ! task.complete;
                }).length;
            },
            taskListPrefix: function() {
                if (this.taskList.listType == 'scheduled')
                        return 'Due ';
                if (this.taskList.listType == 'tag' && this.taskList.listName.charAt(0) !== '@')
                        return '#';
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
                this.store.fetchTaskList(listId, listType, this.withCompletedTasks, function(result) {
                    that.taskList = result;
                    that.taskList.listType = listType;
                    that.taskList.listId = listId;
                    that.taskList.listPath = that.$route.path;
                    that.showListName = true;
                    that.displayTaskList = true;
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
                this.$route.router.go({name: 'projects.show', params:{id: project.id}});
            },
            getTasksByTag: function(tag) {
                this.$route.router.go({name: 'tags.show', params:{id: tag.id}});
            },
            initializeDueDatePickers: function() {
                var that = this;
                $(".datepicker").datepicker({
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    dateFormat: "yy-mm-dd",
                    onSelect: function (date, picker) {
                        let task = JSON.parse(this.getAttribute('data-task'));
                        task.due_date = date;
                        that.updateTask(task);
                    }
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
                    this.store.playSound('ding');
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
                this.store.saveTask(task, function(){
                    that.refreshTaskList();
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
                console.log(timestamp);
                if (timestamp == 'undefined' || timestamp == null) {
                    return 'recently';
                } else {
                    return timestamp;
                }
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