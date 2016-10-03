<template>

    <li class="sidebar-divider">
    </li>
    <li class="sidebar-menu-item">
        <a id="scheduled-today" v-link="{ name: 'tasks.list', params: { id: 'today' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">today</i> Today
            <span v-if="sharedState.scheduledTaskCounts.today" id="task-count-today" class="sidebar-menu-label label label-primary">{{ sharedState.scheduledTaskCounts.today }}</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a id="scheduled-tomorrow" v-link="{ name: 'tasks.list', params: { id: 'tomorrow' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">event</i> Tomorrow
            <span v-if="sharedState.scheduledTaskCounts.tomorrow" id="task-count-tomorrow" class="sidebar-menu-label label label-primary">{{ sharedState.scheduledTaskCounts.tomorrow }}</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a id="scheduled-this-week" v-link="{ name: 'tasks.list', params: { id: 'this-week' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">view_week</i> This Week
            <span v-if="sharedState.scheduledTaskCounts.thisWeek" id="task-count-this-week" class="sidebar-menu-label label label-primary">{{ sharedState.scheduledTaskCounts.thisWeek }}</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a id="scheduled-next-week" v-link="{ name: 'tasks.list', params: { id: 'next-week' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">next_week</i> Next Week
            <span v-if="sharedState.scheduledTaskCounts.nextWeek" id="task-count-next-week" class="sidebar-menu-label label label-primary">{{ sharedState.scheduledTaskCounts.nextWeek }}</span>
        </a>
    </li>
    <li class="sidebar-menu-item">
        <a id="scheduled-later" v-link="{ name: 'tasks.list', params: { id: 'later' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"class="sidebar-menu-button">
            <i class="sidebar-menu-icon material-icons">forward</i> Later
            <span v-if="sharedState.scheduledTaskCounts.later" id="task-count-later" class="sidebar-menu-label label label-primary">{{ sharedState.scheduledTaskCounts.later }}</span>
        </a>
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