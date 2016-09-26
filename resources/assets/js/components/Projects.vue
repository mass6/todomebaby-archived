<template>
    <li class="">
        <a href="Javascript:void(0)">
            <i class="icon-stack"></i> <span>Projects</span>
        </a>
        <ul>
            <a v-link="{ name: 'projects.create' }" id="add-project" class="add-project pull-right" alt="Add Project" @click="hideMobileSidebar"><i class="icon-plus-circle2"></i></a>
            <li v-for="project in sharedState.projects">
                <a v-link="{ name: 'projects.show', params: { id: project.slug }, replace: true, exact: true }" class="project-link" v-link-active @click.stop="taskListSelected">
                    {{ project.name }}<span id="project-{{project.id}}-task-count" class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="project.taskCount">{{ project.taskCount }}</span>
                </a>
            </li>
        </ul>
    </li>
</template>
<style>

</style>
<script>
    import { store } from '../store'
    export default{
        data(){
            return{
                sharedState: store.state
            }
        },
        methods: {
            taskListSelected: function () {
                this.$dispatch('taskListWasSelected');
            },
            hideMobileSidebar: function() {
                $('body').toggleClass('sidebar-mobile-main').removeClass('sidebar-mobile-secondary sidebar-mobile-opposite sidebar-mobile-detached');
            }
        }
    }
</script>