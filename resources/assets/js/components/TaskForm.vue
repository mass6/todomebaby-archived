<template>
    <div id="task-form-container">
        <!-- Grid -->
        <p id="instructions" class="text text-bold" v-if="$route.name == 'tasks.list'">Add a Task</p>

        <div class="form-group form-control input-group-facade" :class="{'margin-left-45': task.id }">
            <div class="col-sm-12">
                <div class="form-group">
                    <i class="blue large inline-control"
                       :class="{ 'icon-checkbox-unchecked2': task.complete == false, 'icon-checkbox-checked2': task.complete == true}"
                       id="task-complete"
                       @click="markComplete(task)"
                       v-if="task.id"
                    ></i>
                    <input v-model="task.title" id="task-title" name="task-title" type="text" class="inline-control transparent-input" v-bind:style="{ marginLeft: task.id ? '20px' : 0 }" placeholder="Task Title" v-on:keyup.enter="saveTask" @focus="activateForm">
                    <i class="pull-right task-next" :class="taskClassNext" data-popup="tooltip" title="Mark as Next" data-placement="top" @click="task.next = !task.next" v-if="editMode"><a href="javascript:void(0)" id="task-next">&nbsp;</a></i>
                </div>
            </div>
        </div>

        <section v-if="editMode" transition="expand">

            <div class="form-group" v-if="task.id">

            </div>


            <!-- Due Date -->
            <div class="row">

                <div class="col-md-6">

                    <div class="input-group">
                        <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                        <input v-model="task.due_date" id="task-due-date" name="task-due-date" type="text" class="form-control pickadate" placeholder="Date due">
                    </div>

                </div>

            </div>
            <br/>
            <!-- /due date-->

            <!-- Tags -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Using typeahead -->
                    <div class="form-group">
                        <label for="task-tagsinput">Tags</label>
                        <input v-model="task.tagsinput" id="task-tagsinput" name="task-tagsinput" type="text" class="form-control tokenfield-typeahead" value="">
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
                        <select v-model="task.project_id" id="task-project" name="task-project" class="form-control">
                            <option value> -- None -- </option>
                            <option v-for="project in sharedState.projects" :value="project.id" v-bind:selected="project.id == taskList.listId">
                                {{ project.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <br/>
            <!-- /project -->

            <!-- Priority -->
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="task-priority">Priority</label>
                        <select v-model="task.priority" id="task-priority" name="task-priority" class="form-control">
                            <option value="LOW" selected>Low</option>
                            <option value="MED">Medium</option>
                            <option value="HGH">High</option>
                        </select>
                    </div>

                </div>
            </div>
            <br/>
            <!-- /priority -->

            <!-- Details -->
            <div class="row">
                <div class="col-md-12">


                    <div class="form-group">
                        <textarea v-model="task.details" id="task-details" name="task-details" rows="4" cols="4" class="form-control" placeholder="Task Details"></textarea>
                    </div>
                    <!-- /vertical form -->

                </div>
            </div>
            <!-- /details -->

            <div>
                <button type="submit" id="save-task-button" class="btn btn-primary" v-bind:class="{ 'disabled': !task.title.length }" @click.stop.prevent="saveTask">Save Task <i class="icon-checkmark3 position-right"></i></button>
                <button class="btn btn-grey" @click="cancelForm">Cancel </button>
                <button v-if="task.id" type="button" class="btn btn-danger btn-sm" id="delete-task-button" @click="deleteTask(task)">Delete <i class="icon-bin position-right"></i></button>
            </div>
        </section>

    </div>
</template>
<style>
    .expand-transition {
        transition: all .6s ease;
        overflow: hidden;
    }
    /* .expand-enter defines the starting state for entering */
    /* .expand-leave defines the ending state for leaving */
    .expand-enter, .expand-leave {
        height: 0;
        opacity: 0;
    }
    i#task-complete {
        color: #055D92;
        font-size: 2.5em;
        margin-left: -55px;
    }

    i.icon-star-empty3, i.icon-star-full2 {
        color:green;
        font-size: 1.8em;
        line-height: 36px;
    }
    .form-control.input-group-facade {
        padding:0;
        width:auto;
    }
    .transparent-input {
        border:none;
        background: none;
        min-height: 33px;
    }
    #task-title {
        width:80%;
    }
    .task-next {
        cursor: pointer;
    }
    .tokenfield .token-input {
        width: 240px!important;
        min-width: 60px;
    }
    .sweet-alert button.cancel {
        background-color: #ddd;
    }
    @media (min-width:1025px) {
        .sweet-alert {
            top:30%;
        }
    }
    [v-cloak] {
        display: none;
    }
</style>
<script>
    import { store } from '../store'
    import { taskFormPlugins } from '../TaskFormPlugins'
    export default{
        props: {
            task: {
                type: Object,
                default: function () {
                    return {
                        title: '',
                        next: false,
                        tags: []
                    }
                }
            },
            taskList: {}
        },
        data(){
            return{
                sharedState: store.state,
                store: store,
                filter: {filterType: '', filterValue: ''},
                defaultTags: null,
                editMode: false,
                taskFormPlugins: taskFormPlugins
            }
        },
        computed: {
            taskClassNext: function() {
                return {
                    'icon-star-empty3': !this.task.next,
                    'icon-star-full2': this.task.next
                }
            }
        },
        created: function() {
            if (this.isAnEditRequest()) {
                var that = this;
                this.store.fetchTask(this.$route.params.id, function(result) {
                    that.activateForm();
                    that.task = result;
                });
            }
        },
        methods: {
            isAnEditRequest: function() {
                return !this.task.id && this.$route.name == 'tasks.edit'
            },
            activateForm: function() {
                if (this.editMode == false) {
                    this.expandForm();
                }
                this.$nextTick(function(){
                    this.initiliazePlugins();
                });
            },
            expandForm: function() {
                this.editMode = true;
                //this.setDefaultTags();
            },
            cancelForm: function() {
                this.deactivateForm();
                if (this.$route.name == 'tasks.edit') {
                    this.redirect(this.getRedirectPath());
                }
            },
            initiliazePlugins: function() {
                var that = this;
                that.taskFormPlugins.init(this.taskList);
                if (this.task.tags  && this.task.tags.length) {
                    this.repopulateTags();
                }
            },
            repopulateTags: function() {
                let tags = this.task.tags.map(function(tag){
                    return tag.name;
                });
                $('#task-tagsinput').tokenfield('setTokens', tags);
            },
            deactivateForm: function() {
                this.clearForm();
                this.editMode = false;
                this.$dispatch('taskFormDeactivated');
            },
            clearForm: function() {
                this.task = {
                    title: '',
                    next: false
                }
            },
            getRedirectPath: function() {
                if (this.sharedState.previousRoute.path) {
                    return this.sharedState.previousRoute.path;
                }
                return this.sharedState.defaultRoute.path;
            },
            redirect: function(path) {
                this.$route.router.go(path);
            },
            saveTask: function() {
                if (! this.task.title.length) {
                    return;
                }
                var that = this;
                this.store.saveTask(this.task, function(){
                    that.$dispatch('taskSaved', that.task);
                    that.deactivateForm();
                });
            },
            deleteTask: function(task) {
                let that = this;
                swal({
                    title: '"' + this.task.title + '"' + " will be deleted forever.",
                    text: "You will not be able to undo this action.",
                    imageUrl: "images/check-circle-outlined.png",
                    showCancelButton: true,
                    cancelButtonColor: '#DDDDDD',
                    confirmButtonColor: "#ff4f18",
                    confirmButtonText: "Yes, delete task!"
                }, function () {
                    that.store.deleteTask(task, function() {
                        that.$dispatch('taskDeleted', task);
                        that.deactivateForm();
                    });
                });
            },
            markComplete: function(task){
                task.complete = !task.complete;
                //var that = this;
                //that.saveTask(task);
                //playSound('ding');
            }
        },
        events: {
            taskSelected: function(task) {
                this.activateForm();
            },
            taskListUpdated: function() {
                this.deactivateForm();
            }
        }
    }
</script>