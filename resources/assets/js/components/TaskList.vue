<template>

    <!-- Simple panel -->
    <div class="panel panel-flat">

        <div class="panel-body">

            <!--<newform :task="selectedTask"></newform>-->
            <taskform :task="selectedTask"></taskform>
            <br/>

            <section id="task-list-container">
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
                        <tr class="task-item" v-for="task in taskList.tasks" id="task-row-{{ task.id }}" :class="{ 'row-active': task.id == selectedTask.id, 'row-complete': task.complete == true}" v-show="!task.complete">
                            <!-- Complete Box -->
                            <td class="check-complete" id="task-complete-{{ task.id }}"><i class="blue" :class="{ 'icon-checkbox-unchecked2': task.complete == false, 'icon-checkbox-checked2': task.complete == true}" id="toggle-complete-{{ task.id }}" @click="toggleComplete(task)"></i></td>
                            <!-- /complete box -->

                            <!-- Task Title -->
                            <td class="task-title" id="task-title-selection-{{ task.id }}">
                                <div>
                                    <span class="task-selectable task-title" @click="selectTask(task)">{{ task.title }} </span>
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
                            <td>{{ task.due_date ? task.due_date : '' }}</td>
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
    export default{
        data(){
            return {
                sharedState: store.state,
                store: store,
                taskList: {
                    listName: '',
                    tasks: []
                },
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
                if (this.$route.name == 'tasks.list' && (this.$route.path !== this.sharedState.previousRoute.path || this.taskList.listName == '')) {
                    this.$broadcast('taskListUpdated');
                    this.setListName(this.$route.params.listName);
                    this.fetchTaskList();
                }
            }
        },
        methods: {
            fetchTaskList: function() {
                this.displayTaskList = false;
                var that = this;
                this.store.fetchTaskList(this.$route.params.listName, function(result) {
                    that.taskList.tasks = result;
                    that.displayTaskList = true;
                });
            },
            selectTask: function(task) {
                this.selectedTask = Object.assign({}, task);
                this.$broadcast('taskSelected', task);
                this.$route.router.go('/tasks/' + task.id + '/edit');
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
        },
        events: {
            taskFormDeactivated: function() {
                this.selectedTask = {title: ''};
            }
        }

    }
</script>