<template>
    <li class="">
        <a href="Javascript:void(0)"><i class="icon-calendar"></i> <span>Due Date</span></a>
        <ul>
            <li><a v-link="{ name: 'tasks.list', params: { id: 'today' }, replace: true, exact: true }" v-link-active><span class="period-label">Today</span><span v-if="sharedState.scheduledTaskCounts.today" id="task-count-today" class="badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.scheduledTaskCounts.today }}</span></a></li>
            <li><a v-link="{ name: 'tasks.list', params: { id: 'tomorrow' }, replace: true, exact: true }" v-link-active><span>Tomorrow</span><span v-if="sharedState.scheduledTaskCounts.tomorrow" id="task-count-tomorrow" class="badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.scheduledTaskCounts.tomorrow }}</span></a></li>
            <li><a v-link="{ name: 'tasks.list', params: { id: 'this-week' }, replace: true, exact: true }" v-link-active><span>This Week</span><span v-if="sharedState.scheduledTaskCounts.thisWeek" id="task-count-this-week" class="badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.scheduledTaskCounts.thisWeek }}</span></a></li>
            <li><a v-link="{ name: 'tasks.list', params: { id: 'next-week' }, replace: true, exact: true }" v-link-active><span>Next Week</span><span v-if="sharedState.scheduledTaskCounts.nextWeek" id="task-count-next-week" class="badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.scheduledTaskCounts.nextWeek }}</span></a></li>
            <li><a v-link="{ name: 'tasks.list', params: { id: 'future' }, replace: true, exact: true }" v-link-active><span>Future</span><span v-if="sharedState.scheduledTaskCounts.future" id="task-count-future" class="badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.scheduledTaskCounts.future }}</span></a></li>
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