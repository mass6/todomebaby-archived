<template>

    <!-- Simple panel -->
    <div class="panel panel-flat">

        <div class="panel-body">

            <taskform :task="selectedTask"></taskform>
            <br/>

            <section id="task-list-container" v-show="showListName">
                <h2 class="list-heading text-light">{{ taskList.listName }}<small v-if="taskList.listType == 'project'">
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
                                    <span class="project-link" data-project="{{task.project_id}}" @click="selectProject(task.project)">{{ task.project ? '(' + task.project.name + ')' : '' }}</span><br/>
                                    <!-- Tags Block -->
                                    <div class="tag-block">
                                        <span v-for="context in task.contexts" class="task-selectable text-blue-tdm" @click.stop.prevent="selectContext(context)"> @{{ context.name }} </span>
                                        <span v-for="tag in task.tags" class="task-selectable text-teal-700" @click.stop.prevent="selectTag(tag)"> #{{ tag.name }} </span>
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
                                    <span class="input-group-addon"><i class="icon-calendar5"></i></span>
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
<script>

    import { store } from '../store'
    import TaskForm from './TaskForm.vue'
    import NewForm from './NewForm.vue'
    var timer;
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
                selectedTask: {id: null, title: ''}
            }
        },
        components: {
            'taskform': TaskForm,
            'newform': NewForm
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
            initializeDueDatePickers: function() {
                var that = this;
                $('.pickadate-due-date').pickadate({
                    format: 'yyyy-mm-dd',
                    onSet: function(context) {
                        that.updateTask(that.getTaskById(this.$node[0].getAttribute('data-task-id')));
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
                clearTimeout(timer);
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
            updateTask: function (task) {
                var that = this;
                this.store.saveTask(task, function(){
                    that.refreshTaskList();
                });
            },
            delayedUpdate: function(task) {
                var that = this;
                timer = setTimeout(function(){
                    that.updateTask(task);
                }, 6000);
            },
            setPriority: function(task) {

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
            editProject: function(project) {

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