<template>

    <!-- Simple panel -->
    <div class="panel panel-flat">

        <div class="panel-body">

            <taskform :task="selectedTask" v-bind:task-list="taskList"></taskform>
            <br/>

            <section id="task-list-container" v-show="showListName">
                <h2 class="list-heading text-light">{{ taskList.listType == 'tag' && taskList.listName.charAt(0) !== '@' ? '#' : '' }}{{ taskList.listName }}<small v-if="taskList.listType == 'project'">
                    <span class="project-edit clickable label border-orange label-flat text-orange" @click.stop.prevent="editProject">edit</span></small></h2>
                <div id="context-tasks" class="table-responsive">
                    <table class="table tasks-list table-lg" v-show="displayTaskList">
                        <thead>
                        <tr>
                            <th></th>
                            <th style="width: 40%;"></th>
                            <th>Priority</th>
                            <th>Due Date</th>
                            <th>Next</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="! taskList.tasks.length"><td>You have no tasks in this list.</td></tr>
                        <tr class="task-item" v-for="task in taskList.tasks" track-by="id" id="task-row-{{ task.id }}" :class="{ 'row-active': task.id == selectedTask.id, 'row-complete': task.complete == true}">
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
                                <div class="input-group due-date-picker">
                                    <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                                    <input v-model="task.due_date" id="task-{{task.id}}-due-date" data-task-id="{{task.id}}" type="text" class="form-control pickadate-due-date">
                                </div>
                            </td>
                            <td><i class="task-next" :class="{ 'icon-star-empty3': task.next == false, 'icon-star-full2': task.next == true}" @click="toggleNext(task)"><a href="javascript:void(0)" id="task-next">&nbsp;</a></i></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

</template>
<style>
    h2.list-heading {
        margin-bottom: -40px;
    }
    table.tasks-list {
        margin-bottom: 360px;
    }
    table.tasks-list>tbody>tr:last-child {
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
    .picker--opened .picker__holder {
        min-width: 250px;
    }
    .table>tbody>tr>td.check {
        padding: 12px 2px;
        width: 20px;
    }
    span.project-edit.clickable {
        margin-left: 6px;
        font-size: .65em;
        vertical-align: super;
    }
    #context-tasks.table-responsive {
        min-height: 101px;
        border:none;
    }
    .table-striped>tbody>tr.task-item, .table>tbody>tr.task-item {
        border-left: 6px solid white;
    }
    .table-striped>tbody>tr.task-item.row-active, .table>tbody>tr.task-item.row-active {
        background-color: #F9F9F9;
        border-left: 6px solid #055D92;
    }
    .table-striped>tbody>tr.row-complete, .table>tbody>tr.row-complete {
        text-decoration: line-through;
        background-color: #E6E6E6;
        color: #7e7e7e;
    }
    .row-complete span.task-selectable.task-title {
        color: #7e7e7e;
        opacity: .6;
    }
    .fade-transition {
        transition: opacity 1s ease;
    }
    .fade-leave {
        opacity: 0;
        -webkit-transition-delay: 1s; /* Safari */
        transition-delay: 1s;
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
    var timer = [];
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
                selectedTask: {id: null, title: '', tags: []}
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
                window.scrollTo(0, 0);
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
                this.store.fetchTaskList(listId, listType, function(result) {
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
                $('.pickadate-due-date').pickadate({
                    format: 'yyyy-mm-dd',
                    onSet: function(context) {
                        let component = this;
                        setTimeout(function(){
                            that.updateTask(that.getTaskById(component.$node[0].getAttribute('data-task-id')));
                        }, 500);
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
                clearTimeout(timer[task.id]);
                task.complete = ! task.complete;
                if (task.complete) {
                    this.store.playSound('ding');
                }
                this.delayedUpdate(task);
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
            delayedUpdate: function(task) {
                var that = this;
                timer[task.id] = setTimeout(function(){
                    that.updateTask(task);
                }, 6000);
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
        },
        events: {
            taskFormDeactivated: function() {
                this.unSelectTask();
            },
            taskSaved: function() {
                this.refreshTaskList();
                this.$route.router.go({path: this.taskList.listPath});
            }
        }

    }
</script>