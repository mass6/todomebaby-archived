export let repo = {

    /*
     |--------------------------------------------------------------------------
     | Tasks
     |--------------------------------------------------------------------------
     |
     */

    // Fetches tasks for the specified list
    fetchTaskList: function(id, listType, completed, callback) {
        let url = this.getBasePath(id, listType, completed);
        Vue.http.get(url).then((response) => {
            return callback(response.json());
        }, (response) => {
            console.log(response.statusText);
        });
    },

    // Fetches a task by ID
    fetchTask: function(taskId, callback) {
        Vue.http.get('/tasks/' + taskId).then(function (response) {
            callback(response.json());
        });
    },

    // Fetches counts for each scheduled task list (Today, Next Week, etc.)
    // and stores the result it in global state object
    fetchScheduledTaskCounts: function(callback) {
        Vue.http.get('/tasks/scheduled').then(function (response) {
            callback(response.json());
        });
    },

    // Add a new tasks to the DB
    addTask: function(task, callback) {
        Vue.http.post('/tasks', task).then(function (response) {
            callback(response.json());
        });
    },

    // Updates an existing task to the DB
    updateTask: function(task, callback) {
        Vue.http.patch('/tasks/' + task.id, task).then(function (response) {
            callback(response.json());
        });
    },

    // Deletes a existing task from the DB
    deleteTask: function(task, callback) {
        Vue.http.delete('/tasks/' + task.id).then(function (response) {
            callback(response.json());
        });
    },

    /*
     |--------------------------------------------------------------------------
     | Projects
     |--------------------------------------------------------------------------
     |
     */

    // Fetches projects and repos in global state object
    fetchProjects: function (callback) {
        Vue.http.get('/projects').then(function (response) {
            callback(response.json());
        });
    },

    // Fetches a project by ID
    fetchProject: function(projectId, callback) {
        Vue.http.get('/projects/' + projectId).then(function (response) {
            callback(response.json());
        });
    },

    // Add a new project to the DB
    addProject: function(project, callback) {
        Vue.http.post('/projects', project).then(function (response) {
            callback(response.json());
        });
    },

    // Updates an existing project to the DB
    updateProject: function(project, callback) {
        Vue.http.patch('/projects/' + project.slug, project).then(function (response) {
            callback(response.json());
        });
    },

    // Deletes a project from the DB
    deleteProject: function(project, callback) {
        Vue.http.delete('/projects/' + project.slug).then(function (response) {
            callback(response.json());
        });
    },

    /*
     |--------------------------------------------------------------------------
     | Contexts
     |--------------------------------------------------------------------------
     |
     */

    // Fetches contexts and repos in global state object
    fetchContexts: function (callback) {
        Vue.http.get('/tags/contexts').then(function (response) {
            callback(response.json());
        });
    },

    /*
     |--------------------------------------------------------------------------
     | Helper Functions
     |--------------------------------------------------------------------------
     |
     */
    
    // Determines the proper URL endpoint depending on the list type
    getBasePath: function (id, listType, completed) {
        let queryString = '';
        if (completed == true) {
            queryString = '?with-completed=true';
        }
        if (listType == 'project') {
            return '/projects/' + id + '/tasks' + queryString;
        }
        if (listType == 'tag') {
            return '/tags/' + id + '/tasks' + queryString;
        }

        return '/tasklists/' + id + queryString;
    }
};