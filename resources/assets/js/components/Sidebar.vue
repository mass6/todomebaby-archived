<template>
    <div class="sidebar sidebar-main sidebar-fixed bg-blue-800">
        <div class="sidebar-content">

            <!-- Main navigation -->
            <div class="sidebar-category sidebar-category-visible">
                <div class="category-content no-padding">
                    <ul class="navigation navigation-main navigation-accordion">

                        <li class="">
                            <a id="inbox" v-link="{ name: 'tasks.list', params: { id: 'inbox' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"><i class="icon-inbox-alt"></i> <span class="inbox-link">Inbox</span><span v-if="sharedState.inbox.tasks.length" id="task-count-inbox" class="task-counts badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.inbox.tasks.length }}</span></a>
                            <a id="next" v-link="{ name: 'tasks.list', params: { id: 'next' }, replace: true, exact: true }" v-link-active @click.stop="taskListSelected"><i class="icon-star-full2"></i> <span class="next-link">Next</span><span v-if="sharedState.next.tasks.length" id="task-count-next" class="task-counts badge badge-primary bg-blue-tdm border-blue-tdm">{{ sharedState.next.tasks.length }}</span></a>
                        </li>

                        <!-- Scheduled Tasks -->
                        <scheduled></scheduled>
                        <!-- /scheduled tasks -->

                        <!-- Projects -->
                        <projects></projects>
                        <!-- /projects -->

                        <!-- Contexts -->
                        <contexts></contexts>
                        <!-- /contexts -->

                    </ul>
                </div>
            </div>
            <!-- /main navigation -->

        </div>
    </div>
</template>
<style>
    .navigation {
        padding-top:0;
    }
    .navigation .navigation-header {
        color:#ffffff;
        font-size:14px;
        font-weight: 600;
    }
    .navigation > li > ul li:first-child {
         padding-top: 0;
    }
    .navigation > li > a {
        color: #ffffff;
        font-weight: 300;
        background-color: #066fae;
    }
    .navigation > li ul li a {
        line-height: 12px;
        min-height: 0;
        padding-left: 36px;
    }
    .navigation li a:focus {
        background-color: #4CAF50;
    }
    .navigation-main > li > a:focus {
        background-color: #066fae;
    }
    .v-link-active { background-color: #4CAF50;}
    .navigation > li > a:hover {
        cursor: default;
    }
    .navigation > li ul {
        background-color: #0277BD;
    }
    span.period-link, a.project-link, a.context-link {
        font-size: 12px;
        font-weight: 300;
        color: #ffffff !important;
    }
    span.task-counts {
        margin-top: -3px;
    }
    a.add-project {
        margin-top: -32px;
        margin-right: 6px;
    }
    @media (min-width: 769px) {
        /* Override */
        .sidebar-separate .sidebar-category {
            background-color: #0277BD;  /* Override */
            border-radius: 3px;
            margin-bottom: 20px;
        }
        /* Override */
        .sidebar-xs .sidebar-main .navigation > li > a > span {
            background-color: #066fae; /* Override */
            border: 1px solid #066fae; /* Override */
        }
        /* Override */
        .sidebar-xs .sidebar-main .navigation > nli:hover > a > spa {
            background-color: #055D92; /* Override */
        }
        /* Override */
        .sidebar-xs .sidebar-main .navigation > li > ul {
            background-color: #0277BD; /* Override */
        }
    }
</style>
<script>
    import { store } from '../store'
    import Scheduled from './Scheduled.vue'
    import Projects from './Projects.vue'
    import Contexts from './Contexts.vue'
    export default{
        data(){
            return{
                sharedState: store.state
            }
        },
        components:{
            'scheduled':Scheduled,
            'projects':Projects,
            'contexts':Contexts
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