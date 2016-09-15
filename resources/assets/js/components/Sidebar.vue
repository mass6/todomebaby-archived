<template>
    <div class="sidebar sidebar-main bg-blue-800">
        <div class="sidebar-content">

            <!-- Main navigation -->
            <div class="sidebar-category sidebar-category-visible">
                <div class="category-content no-padding">
                    <ul class="navigation navigation-main navigation-accordion">

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
    .navigation .navigation-header {
        color:#ffffff;
        font-size:14px;
        font-weight: 600;
        /*margin-bottom: 20px;*/
    }
    .navigation > li > ul li:first-child {
         padding-top: 0;
    }
    .navigation > li > a {
        color: #ffffff;
        font-weight: 300;
    }
    .navigation > li ul li a {
        line-height: 12px;
        min-height: 0;
        padding-left: 36px;
    }
    .navigation li a:focus {
        background-color: #4CAF50;
    }
    .v-link-active { background-color: #4CAF50;}
    .navigation > li > a:hover {
        background: none;
        cursor: default;
    }
    .navigation > li ul {
        background-color: #0277BD;
        /*margin-left: -24px;*/
    }
    span.period-link, a.project-link {
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
</style>
<script>
    import { store } from '../store'
    import Scheduled from './Scheduled.vue'
    import Projects from './Projects.vue'
    import Contexts from './Contexts.vue'
    export default{
        data(){
            return{
                sharedState: store.state,
                store: store
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
            }
        },
        events: {
            taskListWasSelected: function () {
                this.hideMobileSidebar();
            }
        }
    }
</script>