<template>
    <li class="">
        <a href="Javascript:void(0)"><i class="icon-calendar"></i> <span>Due Date</span></a>
        <ul>
            <li><a id="scheduled-today" v-link="{ name: 'tasks.list', params: { id: 'today' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"><span class="period-link">Today</span><span v-if="sharedState.scheduledTaskCounts.today" id="task-count-today" class="task-counts badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.scheduledTaskCounts.today }}</span></a></li>
            <li><a id="scheduled-tomorrow" v-link="{ name: 'tasks.list', params: { id: 'tomorrow' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"><span class="period-link">Tomorrow</span><span v-if="sharedState.scheduledTaskCounts.tomorrow" id="task-count-tomorrow" class="task-counts badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.scheduledTaskCounts.tomorrow }}</span></a></li>
            <li><a id="scheduled-this-week" v-link="{ name: 'tasks.list', params: { id: 'this-week' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"><span class="period-link">This Week</span><span v-if="sharedState.scheduledTaskCounts.thisWeek" id="task-count-this-week" class="task-counts badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.scheduledTaskCounts.thisWeek }}</span></a></li>
            <li><a id="scheduled-next-week" v-link="{ name: 'tasks.list', params: { id: 'next-week' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"><span class="period-link">Next Week</span><span v-if="sharedState.scheduledTaskCounts.nextWeek" id="task-count-next-week" class="task-counts badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.scheduledTaskCounts.nextWeek }}</span></a></li>
            <li><a id="scheduled-later" v-link="{ name: 'tasks.list', params: { id: 'later' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"><span class="period-link">Later</span><span v-if="sharedState.scheduledTaskCounts.later" id="task-count-later" class="task-counts badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.scheduledTaskCounts.later }}</span></a></li>
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