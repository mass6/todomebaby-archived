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
    .navigation > li > a {
        color: #ffffff;
        font-weight: 400;
    }
    .navigation li a:focus {
        background-color: none;
    }
    .sidebar-active { background-color: #4CAF50;}
    .navigation > li > a:hover {
        background: none;
        cursor: default;
    }
    .navigation > li ul {
        background-color: #0277BD;
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