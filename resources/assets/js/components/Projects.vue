<template>
    <li class="sidebar-divider">
    </li>
    <li class="sidebar-menu-item">
        <div class="category-tabs">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link active" href="#projects-tab" data-toggle="tab">Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contexts-tab" data-toggle="tab">Contexts</a>
                </li>
            </ul>
            <div class="card-block tab-content">
                <div class="tab-pane active" id="projects-tab">
                    <ul class="list-unstyled">
                        <li v-for="project in sharedState.projects" class="submenu-item">
                            <a v-link="{ name: 'projects.show', params: { id: project.slug }, replace: true, exact: true }" class="sidebar-menu-button project-link" v-link-active @click.stop="taskListSelected">
                                {{ service.truncateText(project.name, 30) }}
                                <span id="project-{{project.id}}-task-count" class="sidebar-menu-label label label-primary" v-if="project.taskCount">{{ project.taskCount }}</span>
                            </a>
                        </li>
                        <li class="submenu-item">
                            <a v-link="{ name: 'projects.create' }" class="sidebar-menu-button project-link new-project" >+ New Project
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-pane" id="contexts-tab">
                    <ul class="list-unstyled">
                        <li v-for="context in sharedState.contexts" class="submenu-item">
                            <a v-link="{ name: 'tags.show', params: { id: context.slug }, replace: true, exact: true }" class="sidebar-menu-button context-link" v-link-active @click.stop="taskListSelected">
                                {{ context.name.length < 22 ? context.name : context.name.substring(0,25) + '...' }}
                                <span id="context-{{context.id}}-task-count" class="sidebar-menu-label label label-primary" v-if="context.taskCount">{{ context.taskCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </li>
</template>
<style>

</style>
<script>
    import { store } from '../store'
    import { service } from '../service'
    export default{
        data(){
            return{
                sharedState: store.state,
                service: service
            }
        },
        methods: {
            taskListSelected: function () {
                this.$dispatch('taskListWasSelected');
            },
            hideMobileSidebar: function() {
                $('body').toggleClass('sidebar-mobile-main').removeClass('sidebar-mobile-secondary sidebar-mobile-opposite sidebar-mobile-detached');
            }
        }
    }
</script>