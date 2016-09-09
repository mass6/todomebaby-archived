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
                    <input v-model="task.title" id="task-title" name="task-title" type="text" class="inline-control transparent-input" v-bind:style="{ marginLeft: task.id ? '20px' : 0 }" placeholder="Task Title" @focus="expandForm">
                    <i class="pull-right task-next" :class="taskClassNext" @click="task.next = !task.next"><a href="javascript:void(0)" id="task-next">&nbsp;</a></i>
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
                        <span class="input-group-addon"><i class="icon-calendar5"></i></span>
                        <input v-model="task.due_date" id="task-due-date" name="task-due-date" type="text" class="form-control pickadate" placeholder="Date due">
                    </div>

                </div>

            </div>
            <br/>
            <!-- /due date-->

            <!--<div class="row">-->

                <!--<div class="col-md-12">-->

                    <!--&lt;!&ndash; Using typeahead &ndash;&gt;-->
                    <!--<div class="form-group">-->
                        <!--<label>Tags</label>-->
                        <!--<input v-model="task.tagsinput" id="task-tagsinput" type="text" class="form-control tokenfield-typeahead" value="">-->
                    <!--</div>-->
                    <!--&lt;!&ndash; /using typeahead &ndash;&gt;-->

                <!--</div>-->
            <!--</div>-->

            <!-- Project -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="task-project">Project</label>
                        <select v-model="task.project_id" id="task-project" name="task-project" class="form-control">
                            <option selected>None</option>
                            <option v-for="project in sharedState.projects" :value="project.id">
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
                <button type="submit" id="save-task-button" class="btn btn-primary" v-bind:class="{ 'disabled': !task.title.length }" @click="saveTask">Save Task <i class="icon-checkmark3 position-right"></i></button>
                <button class="btn btn-grey" @click="cancelForm">Cancel </button>
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
</style>
<script>
    import { store } from '../store'
    export default{
        props: {
            task: {
                type: Object,
                default: function () {
                    return {
                        title: '',
                        next: false
                    }
                }
            }
        },
        data(){
            return{
                sharedState: store.state,
                store: store,
                filter: {filterType: '', filterValue: ''},
                defaultTags: null,
                editMode: false
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
            if (!this.task.id && this.$route.name == 'tasks.edit') {
                var that = this;
                this.store.fetchTask(this.$route.params.taskId, function(result) {
                    that.activateForm();
                    that.task = result;
                });
            }
        },
        methods: {
            activateForm: function() {
                if (this.editMode == false) {
                    this.expandForm();
                }
            },
            expandForm: function() {
                this.editMode = true;
                this.$nextTick(function(){
                    $('.pickadate').pickadate({
                        format: 'yyyy-mm-dd'
                    });
                })

//                this.setDefaultTags();
            },
            cancelForm: function() {
                this.deactivateForm();
                if (this.$route.name == 'tasks.edit') {
                    this.redirect(this.getRedirectPath());
                }
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
                var that = this;
                this.store.saveTask(this.task, function(){
                    that.$dispatch('taskSaved', that.task);
                    that.deactivateForm();
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
                this.editMode = true;
            },
            taskListUpdated: function() {
                this.deactivateForm();
            }
        }
    }
</script>