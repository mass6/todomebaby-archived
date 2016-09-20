
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.moment = require('moment');

var VueRouter = require('vue-router');
Vue.use(VueRouter);


var router = new VueRouter({
    hashbang: false
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

var App = Vue.component('app', require('./components/App.vue'));
var TaskList = Vue.component('tasklist', require('./components/TaskList.vue'));
var ProjectForm = Vue.component('projectform', require('./components/ProjectForm.vue'));
import { store } from './store'

router.map({
    '/lists/:id': {
        name: 'tasks.list',
        listType: 'scheduled',
        canRedirectTo: true,
        component: TaskList
    },
    '/tasks/:id/edit': {
        name: 'tasks.edit',
        component: TaskList
    },
    '/projects/create': {
        name: 'projects.create',
        component: ProjectForm
    },
    '/projects/:id/edit': {
        name: 'projects.edit',
        component: ProjectForm
    },
    '/projects/:id': {
        name: 'projects.show',
        listType: 'project',
        canRedirectTo: true,
        component: TaskList
    },
    '/tags/:id': {
        name: 'tags.show',
        listType: 'tag',
        canRedirectTo: true,
        component: TaskList
    }
});
// Any invalid route will redirect to home
router.redirect({
    '*': '/lists/today'
});

// save previous route for rerouting back to previous viewed task list
router.beforeEach(function ({ to, next }) {
    if (router.app.$route.canRedirectTo == true) {
        store.state.previousRoute = router.app.$route;
    }
    next();
})
// Start router
window.onload = function () {
    router.start(App, '#app');
}
