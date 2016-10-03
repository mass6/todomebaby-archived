<template>


    <div class="sidebar sidebar-left si-si-3 sidebar-visible-md-up sidebar-light ls-top-navbar-xs-up sidebar-transparent-md" id="sidebarLeft" data-scrollable>
        <ul class="sidebar-menu">
            <li class="sidebar-menu-item">
                <a id="inbox" v-link="{ name: 'tasks.list', params: { id: 'inbox' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected" class="sidebar-menu-button">
                    <i class="sidebar-menu-icon material-icons">home</i> Inbox
                    <span v-if="sharedState.inbox.tasks.length" id="task-count-inbox" class="sidebar-menu-label label label-primary">{{ sharedState.inbox.tasks.length }}</span>
                </a>
            </li>
            <li class="sidebar-menu-item">
                <a id="next" v-link="{ name: 'tasks.list', params: { id: 'next' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected" class="sidebar-menu-button">
                    <i class="sidebar-menu-icon material-icons">star</i> Next
                    <span v-if="sharedState.next.tasks.length" id="task-count-next" class="sidebar-menu-label label label-primary">{{ sharedState.next.tasks.length }}</span>
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
                $('body').toggleClass('sidebar-mobile-main').removeClass('sidebar-mobile-secondary sidebar-mobile-opposite sidebar-mobile-detached');
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