<template>
    <!-- Simple panel -->
    <div class="panel panel-flat">

        <div class="panel-body">

            <div id="project-form-container">

                <p>Path: {{ $route.path }}</p>
                <p>Params: {{ $route.params | json }}</p>
                <p>Query: {{ $route.query | json }}</p>
                <p>Matched: {{ $route.query | json }}</p>
                <p>Name: {{ $route.name }}</p>
                <br/>
                <h2>{{ project.id ? 'Edit Project' : 'New Project' }}</h2>

                <!-- Grid -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <input v-model="project.name" id="project-name" type="text" class="form-control pull-left" placeholder="Project Name" />
                        </div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea v-model="project.description" id="project-description" rows="4" cols="4" class="form-control" placeholder="Description"></textarea>
                        </div>
                        <!-- /vertical form -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-calendar5"></i></span>
                            <input v-model="project.due_date" id="project-due-date" type="text" class="form-control pickadate" placeholder="Due Date">
                        </div>
                    </div>
                </div>
                <br/>

                <div>
                    <button type="submit" class="btn btn-primary" @click="saveProject(project)">Save Project <i class="icon-checkmark3 position-right"></i></button>
                    <button class="btn btn-grey" @click="setDefaultScope">Cancel </button>
                </div>

            </div>

        </div>
    </div>
</template>
<style>

</style>
<script>
    export default{
        data(){
            return{
                project: {},
                basePath: '/api/projects/'
            }
        },
        methods: {
            saveProject: function(project) {
                this.projectSaved();
            },
            projectSaved: function() {
                this.$dispatch('projectSaved', true);
                this.resetProjectForm();
            },
            saveProjectFailed: function() {
                this.$dispatch('saveProjectFailed', true);
            },
            resetProjectForm: function() {
                this.project = {};
            },
            setDefaultScope: function() {
                this.$dispatch('setDefaultScope');
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