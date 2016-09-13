<template>
    <li class="">
        <a href="Javascript:void(0)"><i class="icon-stack"></i> <span>Projects</span></a>
        <ul>
            <li v-for="project in sharedState.projects">
                <a v-link="{ name: 'projects.show', params: { id: project.id }, replace: true, exact: true }" class="project-link" v-link-active>
                    {{ project.name }}<span id="project-{{project.id}}-task-count" class="badge badge-primary bg-blue-tdm border-blue-tdm">{{ project.taskCount }}</span>
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
                sharedState: store.state,
                store: store
            }
        }
    }
</script>