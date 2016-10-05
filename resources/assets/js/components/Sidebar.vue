<template>


    <div class="sidebar sidebar-left si-si-3 sidebar-visible-md-up sidebar-light ls-top-navbar-xs-up sidebar-transparent-md" id="sidebarLeft" data-scrollable>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item" v-link-active>
                <a id="next" v-link="{ name: 'tasks.list', activeClass: 'active', params: { id: 'next' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected" class="sidebar-menu-button">
                    <i class="sidebar-menu-icon material-icons">star</i> Next
                    <span v-if="sharedState.next.tasks.length" id="task-count-next" class="sidebar-menu-label label label-primary">{{ sharedState.next.tasks.length }}</span>
                </a>
            </li>
            <li class="sidebar-menu-item" v-link-active>
                <a id="all-tasks" v-link="{ name: 'tasks.list', activeClass: 'active', params: { id: 'all' }, replace: true, exact: true }" @click.stop="taskListSelected" class="sidebar-menu-button">
                    <i class="sidebar-menu-icon material-icons">home</i> All
                    <span v-if="sharedState.allTasks.tasks.length" id="task-count-all" class="sidebar-menu-label label label-primary">{{ sharedState.allTasks.tasks.length }}</span>
                </a>
            </li>
            <li class="sidebar-menu-item" v-link-active>
                <a id="inbox" v-link="{ name: 'tasks.list', activeClass: 'active', params: { id: 'inbox' }, replace: true, exact: true }" @click.stop="taskListSelected" class="sidebar-menu-button">
                    <i class="sidebar-menu-icon material-icons">inbox</i> Inbox
                    <span v-if="sharedState.inbox.tasks.length" id="task-count-inbox" class="sidebar-menu-label label label-primary">{{ sharedState.inbox.tasks.length }}</span>
                </a>
            </li>

            <scheduled></scheduled>

            <projects></projects>

        </ul>
    </div>


</template>
<style>
    .sidebar-light .sidebar-menu-icon {
        color: rgba(38, 38, 38, 0.68);
    }
    .sidebar-light .sidebar-menu-button {
        color: rgba(0, 0, 0, 0.76);
    }
    a.project-link.sidebar-menu-button, a.context-link.sidebar-menu-button {
        line-height: 30px;
        overflow: hidden;
        padding-right: 35px;
    }
</style>
<script>
    import { store } from '../store'
    import Scheduled from './Scheduled.vue'
    import Projects from './Projects.vue'
    export default{
        data(){
            return{
                sharedState: store.state
            }
        },
        components:{
            'scheduled':Scheduled,
            'projects':Projects
        },
        methods: {
            hideMobileSidebar: function() {
                $('#sidebarLeft').removeClass('sidebar-visible sidebar-transition');
            },
            taskListSelected: function () {
                this.$dispatch('taskListWasSelected');
            }
        },
        events: {
            taskListWasSelected: function () {
                this.hideMobileSidebar();
            }
        }
    }
</script>