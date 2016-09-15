<template>
    <!-- Simple panel -->
    <div class="panel panel-flat">

        <div class="panel-body">

            <div id="project-form-container">

                <h2>{{ isEditing() ? 'Edit Project' : 'New Project' }}</h2>

                <!-- Grid -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="project-name">Name</label>
                            <input v-model="project.name" id="project-name" name="project-name" type="text" class="form-control pull-left" placeholder="Project Name" />
                        </div>
                    </div>
                </div>
                <br/>

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
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-calendar3"></i></span>
                            <input v-model="project.due_date" id="project-due-date" type="text" class="form-control pickadate" placeholder="Due Date">
                        </div>
                    </div>
                </div>
                <br/>

                <div>
                    <button type="submit" class="btn btn-primary" @click="saveProject(project)">Save Project <i class="icon-checkmark3 position-right"></i></button>
                    <button class="btn btn-grey" @click="cancel">Cancel </button>
                </div>

            </div>

        </div>
    </div>
</template>
<style>

</style>
<script>
    import { store } from '../store'
    export default{
        data(){
            return{
                sharedState: store.state,
                store: store,
                project: {},
                showHeading: false
            }
        },
        route: {
            data: function(transition) {
                if ( !this.project.id && this.$route.name == 'projects.edit' ) {
                    var that = this;
                    this.store.fetchProject(this.$route.params.id, function(project) {
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
                let $input = $('#project-due-date').pickadate({format: 'yyyy-mm-dd'});
                let picker = $input.pickadate('picker');
                if (picker.get() !== '' && this.task.due_date !== 'undefined') {
                    picker.set('select', picker.get(), { format: 'yyyy-mm-dd' });
                }
            },
            saveProject: function(project) {
                if (project.name.length) {
                    var that = this;
                    this.store.saveProject(project, function(newProject){
                        that.goToProjectTasks(newProject);
                    });
                }
            },
            goToProjectTasks: function(newProject) {
                this.$route.router.go({name: 'projects.show', params: {id: newProject.id}});
            },
            cancel: function() {
                this.resetProjectForm();
                this.$route.router.go({path: this.sharedState.previousRoute.path});
            },
            resetProjectForm: function () {
                this.project = {};
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