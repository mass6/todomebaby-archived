<template>
    <li class="">
        <a href="Javascript:void(0)"><i class="icon-question3"></i> <span>Contexts</span></a>
        <ul>
            <li v-for="context in sharedState.contexts">
                <a v-link="{ name: 'tags.show', params: { id: context.slug }, replace: true, exact: true }" class="context-link" v-link-active @click.stop="taskListSelected">
                    {{ context.name }}<span id="context-{{context.id}}-task-count" class="badge badge-primary bg-blue-tdm border-blue-tdm" v-if="context.taskCount">{{ context.taskCount }}</span>
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
            }
        }
    }
</script>