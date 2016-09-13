export let store = {
    // Shared state object
    state: {
        projects: [],
        scheduledTaskCounts: {},
        previousRoute: {},
        defaultRoute: {
            name: 'tasks.list',
            params: {'id': 'today'},
            path: '/lists/today'
        }
    },
    // Called by App when first run
    initialize: function() {
        this.refreshSidebarData();
    },
    // Fetches links and data in sidebar
    refreshSidebarData: function() {
        // fetch projects
        this.fetchProjects();
        // fetch scheduled counts
        this.fetchScheduledTaskCounts();
        // fetch context counts
    },
    // Fetches projects and stores in global state object
    fetchProjects: function () {
        Vue.http.get('/projects').then(function (response) {
            store.state.projects = (response.data);
        });
    },
    // Fetches counts for each scheduled task list (Today, Next Week, etc.)
    // and stores result it in global state object
    fetchScheduledTaskCounts: function() {
        Vue.http.get('/tasks/scheduled').then(function (response) {
            store.state.scheduledTaskCounts = (response.data);
        });
    },
    // Fetches tasks for the specified list
    fetchTaskList: function(id, listType, callback) {
        let basePath = this.getBasePath(listType);
        Vue.http.get(basePath + id).then((response) => {
            if (callback) {
                callback(response.data);
            }
        }, (response) => {
            console.log(response.statusText);
        });
    },
    // Determines the proper URL endpoint depending on the list type
    getBasePath: function (listType) {
        if (listType == 'scheduled') {
            return '/tasklists/';
        }
        if (listType == 'project') {
            return '/projects/';
        }
    },
    // Fetches a task by ID
    fetchTask: function(taskId, callback) {
        Vue.http.get('/tasks/' + taskId).then(function (response) {
            store.state.task = response.data;
            if (callback) {
                callback(response.data);
            }
        });
    },
    // Update or Adds a new task depending on whether task ID is present
    saveTask: function(task, callback) {
        if (task.id) {
            this.updateTask(task, callback);
        } else {
            this.addTask(task, callback);
        }
    },
    // Add a new tasks to the DB
    addTask: function(task, callback) {
        Vue.http.post('/tasks', task).then(function (response) {
            store.refreshSidebarData();
            if (callback) {
                callback();
            }
        });
    },
    // Updates an existing task to the DB
    updateTask: function(task, callback) {
        Vue.http.patch('/tasks/' + task.id, task).then(function (response) {
            store.refreshSidebarData();
            if (callback) {
                callback();
            }
        });
    }
};