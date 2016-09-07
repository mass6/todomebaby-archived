<template>
    <li class="">
        <a href="Javascript:void(0)"><i class="icon-calendar"></i> <span>Due Date</span></a>
        <ul>
            <li><a v-link="{ name: 'taskList', params: { listName: 'today' }, replace: true, exact: true }" v-link-active><span>Today</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm">3</span></a></li>
            <li><a v-link="{ name: 'taskList', params: { listName: 'tomorrow' }, replace: true, exact: true }" v-link-active><span>Tomorrow</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm">3</span></a></li>
            <li><a v-link="{ name: 'taskList', params: { listName: 'this-week' }, replace: true, exact: true }" v-link-active><span>This Week</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm">3</span></a></li>
            <li><a v-link="{ name: 'taskList', params: { listName: 'next-week' }, replace: true, exact: true }" v-link-active><span>Next Week</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm">3</span></a></li>
            <li><a v-link="{ name: 'taskList', params: { listName: 'future' }, replace: true, exact: true }" v-link-active><span>Future</span><span class="badge badge-primary bg-blue-tdm border-blue-tdm">3</span></a></li>
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