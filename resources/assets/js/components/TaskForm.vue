<template>



    <div id="task-form">

        <div class="m-b-1">
            <div class="input-group" style="width:100%; display:table;">
                    <span class="input-group-addon addon-sm addon-transparent">
                        <i class="sidebar-menu-icon material-icons md-24 text-danger" @click="markComplete(task)" v-if="task.id">{{ task.complete == true ? 'check_box' : 'check_box_outline_blank' }}</i>
                    </span>
                <input v-model="task.title" id="task-title" name="task-title" type="text" class="form-control" v-bind:class="{'p-l-0': task.id}" placeholder="Add a task..." v-on:keyup.enter="saveTask" @focus="activateForm"/>
                    <span class="input-group-addon addon-sm addon-transparent">
                        <i class="sidebar-menu-icon material-icons md-24 text-danger" @click="task.next = !task.next">{{ task.next == true ? 'star' : 'star_border' }}</i>
                    </span>
            </div>
        </div>

        <section v-if="editMode" transition="expand">

            <div class="row">

                <!-- Due Date -->
                <div id="task-due-date-div" class="col-md-6">
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
                            <option value="low" v-bind:selected="! task.priority">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>

                </div>
                <!-- / priority -->

            </div>
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
                                {{ project.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
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

            <div class="row form-actions m-y-1">
                <div class="col-md-12">
                    <button type="submit" id="save-task-button" class="btn btn-success-outline btn-sm" v-bind:class="{ 'disabled': !task.title.length }" @click.stop.prevent="saveTask">Save Task <i class="icon-checkmark3 position-right"></i></button>
                    <button class="btn btn-grey-outline btn-sm" @click="cancelForm">Cancel </button>
                    <button v-if="task.id" type="button" class="btn btn-primary-outline btn-sm" id="delete-task-button" @click="deleteTask(task)">Delete <i class="icon-bin position-right"></i></button>
                </div>
            </div>
        </section>
    </div>




</template>
<style scoped>
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
    @media (min-width: 768px) {
        .layout-content.simplebar {
            margin-left: 370px !important;
        }
        div#task-form {
            /*margin-bottom: 30px;*/
            padding: 20px;
        }
    }
    input#task-title {
        display: table-cell;
        border-right: none;
        border-left: none;
        padding-left: 0;
        background-color: #FDFAFA;
    }
    input#task-title:focus {
        border-color: #eceeef;
    }
    i#task-complete {
        color: #055D92;
        font-size: 2.5em;
        margin-left: -55px;
    }
    span.input-group-addon.addon-sm.addon-transparent {
        background: #FDFAFA;
    }
    i.icon-star-empty3, i.icon-star-full2 {
        color:green;
        font-size: 2.3em;
        line-height: 46px;
    }
    /*.form-control.input-group-facade {*/
        /*padding:0;*/
        /*width:auto;*/
        /*height: 46px;*/
        /*border: 1px solid green;*/
    /*}*/
    /*.transparent-input {*/
        /*border:none;*/
        /*background: none;*/
        /*min-height: 33px;*/
    /*}*/
    /*#task-title {*/
        /*width:80%;*/
        /*padding-left: 5px;*/
        /*margin-top: 2px;*/
    /*}*/
    /*#task-title::-webkit-input-placeholder { !* Chrome/Opera/Safari *!*/
        /*color: #4caf50;*/
    /*}*/
    /*#task-title:-moz-placeholder { !* Firefox 18- *!*/
        /*color: #4caf50;*/
    /*}*/
    /*#task-title::-moz-placeholder {  !* Firefox 19+ *!*/
        /*color: #4caf50;*/
    /*}*/
    /*#task-title:-ms-input-placeholder {*/
        /*color: #4caf50;*/
    /*}*/
    /*.task-next {*/
        /*cursor: pointer;*/
    /*}*/
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
</style>
<script>
    import { store } from '../store'
    import { service } from '../service'
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
                service.fetchTask(this.$route.params.id, function(result) {
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
                    window.scrollTo(0, 0);
                }
            },
            expandForm: function() {
                this.editMode = true;
                this.$nextTick(function(){
                    this.initiliazePlugins();
                });
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
                    this.repopulateTags(this.task.tags);
                } else if (this.$route.name == 'tags.show') {
                    this.repopulateTags([{name: this.taskList.listName}]);
                }
            },
            repopulateTags: function(tags) {
                let tagNames = tags.map(function(tag){
                    return tag.name;
                });
                $('#task-tagsinput').tokenfield('setTokens', tagNames);
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
                service.saveTask(this.task, function(){
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
                    service.deleteTask(task, function() {
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