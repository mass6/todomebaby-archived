export let store = {
    // Shared state object
    state: {
        projects: [],
        contexts: [],
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
        // fetch context counts
        this.fetchContexts();
        // fetch scheduled counts
        this.fetchScheduledTaskCounts();
    },
    // Fetches projects and stores in global state object
    fetchProjects: function () {
        let that = this;
        Vue.http.get('/projects').then(function (response) {
            store.state.projects = (that.normalizeResponse(response.data));
        });
    },
    // Fetches contexts and stores in global state object
    fetchContexts: function () {
        let that = this;
        Vue.http.get('/tags/contexts').then(function (response) {
            store.state.contexts = (that.normalizeResponse(response.data));
        });
    },
    // Fetches a project by ID
    fetchProject: function(projectId, callback) {
        let that = this;
        Vue.http.get('/projects/' + projectId).then(function (response) {
            if (callback) {
                callback(that.normalizeResponse(response.data));
            }
        });
    },
    // Fetches counts for each scheduled task list (Today, Next Week, etc.)
    // and stores result it in global state object
    fetchScheduledTaskCounts: function() {
        let that = this;
        Vue.http.get('/tasks/scheduled').then(function (response) {
            store.state.scheduledTaskCounts = (that.normalizeResponse(response.data));
        });
    },
    // Fetches tasks for the specified list
    fetchTaskList: function(id, listType, completed, callback) {
        let url = this.getBasePath(id, listType, completed);
        let that = this;
        Vue.http.get(url).then((response) => {
            if (callback) {
                callback(that.normalizeResponse(response.data));
            }
        }, (response) => {
            console.log(response.statusText);
        });
    },
    // Determines the proper URL endpoint depending on the list type
    getBasePath: function (id, listType, completed) {
        let queryString = '';
        if (completed == true) {
            queryString = '?with-completed=true';
        }

        if (listType == 'scheduled') {
            return '/tasklists/' + id + queryString;
        }
        if (listType == 'project') {
            return '/projects/' + id + '/tasks' + queryString;
        }
        if (listType == 'tag') {
            return '/tags/' + id + '/tasks' + queryString;
        }
    },
    // Fetches a task by ID
    fetchTask: function(taskId, callback) {
        let that = this;
        Vue.http.get('/tasks/' + taskId).then(function (response) {
            store.state.task = that.normalizeResponse(response.data);
            if (callback) {
                callback(that.normalizeResponse(response.data));
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
    },
    // Deletes a existing task from the DB
    deleteTask: function(task, callback) {
        Vue.http.delete('/tasks/' + task.id).then(function (response) {
            store.refreshSidebarData();
            if (callback) {
                callback();
            }
        });
    },
    saveProject: function(project, callback) {
        if (project.id) {
            this.updateProject(project, callback);
        } else {
            this.addProject(project, callback);
        }
    },
    // Add a new project to the DB
    addProject: function(project, callback) {
        let that = this;
        Vue.http.post('/projects', project).then(function (response) {
            store.fetchProjects();
            if (callback) {
                callback(that.normalizeResponse(response.data));
            }
        });
    },
    // Updates an existing project to the DB
    updateProject: function(project, callback) {
        let that = this;
        Vue.http.patch('/projects/' + project.id, project).then(function (response) {
            store.fetchProjects();
            if (callback) {
                callback(that.normalizeResponse(response.data));
            }
        });
    },
    // Deletes a project from the DB
    deleteProject: function(project, callback) {
        Vue.http.delete('/projects/' + project.id).then(function (response) {
            store.refreshSidebarData();
            if (callback) {
                callback();
            }
        });
    },
    // Method normalize inner response properties to json in case they are stings.
    // Workaround fix for issue where responses returned over https, the inner
    // response properties are being stringified.
    normalizeResponse(response) {
        return typeof (response) == 'string' ? JSON.parse(response) : response;
    },
    playSound: function(filename) {
        document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="multimedia/' + filename + '.mp3" type="audio/mpeg" /><source src="multimedia/' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="multimedia/' + filename +'.mp3" /></audio>';
    }
};