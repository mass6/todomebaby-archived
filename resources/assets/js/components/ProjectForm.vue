<template>
    <!-- Simple panel -->
    <div class="panel panel-flat app-panel">

        <div class="row">
            <div class="col-md-12">
                <h3 class="list-heading">{{ isEditing() ? 'Edit Project' : 'New Project' }}</h3>
            </div>
        </div>

        <div class="panel-body">

            <div id="project-form-container">

                <!-- Grid -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="project-name">Name</label>
                            <input v-model="project.name" id="project-name" name="project-name" type="text" class="form-control pull-left" placeholder="Project Name" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="project-description">Description</label>
                            <textarea v-model="project.description" id="project-description" name="project-description" rows="4" cols="4" class="form-control" placeholder="Description"></textarea>
                        </div>
                        <!-- /vertical form -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 m-b-1">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                            <input v-model="project.due_date" id="project-due-date" type="text" class="form-control datepicker" placeholder="Due Date">
                        </div>
                    </div>
                </div>

                <div class="form-group m-t-1">
                    <button type="submit" class="btn btn-success-outline btn-sm" @click="saveProject(project)">Save Project <i class="icon-checkmark3 position-right"></i></button>
                    <button class="btn btn-grey-outline btn-sm" @click="cancel">Cancel </button>
                    <button v-if="project.id" type="button" class="btn btn-primary-outline btn-sm" id="delete-project-button" @click="deleteProject(project)">Delete <i class="icon-bin position-right"></i></button>
                </div>

            </div>

        </div>
    </div>
</template>
<style>
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
    export default{
        data(){
            return{
                sharedState: store.state,
                project: {},
                showHeading: false
            }
        },
        route: {
            data: function(transition) {
                if ( !this.project.id && this.$route.name == 'projects.edit' ) {
                    var that = this;
                    service.fetchProject(this.$route.params.id, function(project) {
                        that.project = project;
                        that.showHeading = true;
                    });
                } else {
                    this.showHeading = true;
                }
            }
        },
        ready: function () {
            this.initializeDueDatePicker();
        },
        methods: {
            isEditing: function () {
                return this.$route.name == 'projects.edit';
            },
            initializeDueDatePicker: function() {
                let $input = $('#project-due-date').datepicker({
                    format: "yyyy-mm-dd",
                    todayHighlight: true,
                    todayBtn: 'linked',
                    clearBtn: true,
                    calendarWeeks: true,
                    weekStart: 1,
                    autoclose: true
                });
//                let picker = $input.datepicker('picker');
//                if (picker.get() !== '' && this.task.due_date !== 'undefined') {
//                    picker.set('select', picker.get(), { format: 'yyyy-mm-dd' });
//                }
            },
            saveProject: function(project) {
                if (project.name.length) {
                    var that = this;
                    service.saveProject(project, function(newProject){
                        that.goToProjectTasks(newProject);
                    });
                }
            },
            goToProjectTasks: function(newProject) {
                this.$route.router.go({name: 'projects.show', params: {id: newProject.slug}});
            },
            cancel: function() {
                this.resetProjectForm();
                this.$route.router.go({path: this.sharedState.previousRoute.path});
            },
            resetProjectForm: function () {
                this.project = {};
            },
            deleteProject: function(project) {
                let that = this;
                swal({
                    title: '"' + this.project.name + '"' + " will be deleted forever.",
                    text: "You will not be able to undo this action.",
                    imageUrl: "images/check-circle-outlined.png",
                    showCancelButton: true,
                    cancelButtonColor: '#DDDDDD',
                    confirmButtonColor: "#ff4f18",
                    confirmButtonText: "Yes, delete project!"
                }, function () {
                    service.deleteProject(project, function() {
                        that.$route.router.go('/lists/today');
                    });
                });
            }
        },
        events: {
            'createProject': function(project) {
                this.resetProjectForm();
                return true;
            },
            'editProject': function(project) {
                this.project = Object.assign({}, project);
                return true;
            }
        }
    }
</script>