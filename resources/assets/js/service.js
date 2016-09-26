import { repo } from './repository'
import { store } from './store'

export let service = {

    /*
     |--------------------------------------------------------------------------
     | Initialization
     |--------------------------------------------------------------------------
     |
     */

    // Called by App when first run
    initialize: function() {
        this.refreshSidebarData();
    },
    refreshSidebarData: function() {
        this.fetchInbox();
        this.fetchProjects();
        this.fetchContexts();
        this.fetchScheduledTaskCounts();
    },

    /*
     |--------------------------------------------------------------------------
     | Task Services
     |--------------------------------------------------------------------------
     |
     */

    // Fetches tasks for the specified list
    fetchTaskList: function(id, listType, completed, callback) {
        repo.fetchTaskList(id,  listType, completed, callback);
    },

    // Fetches a task by ID
    fetchTask: function(taskId, callback) {
        repo.fetchTask(taskId,  function (response) {
            store.state.task = response;
            callback(response);
        });
    },

    // Fetches tasks and task count not assigned to any project from the repository
    // and stores the result in the global state object
    fetchInbox: function() {
        repo.fetchInbox(function (response) {
            store.state.inbox = response;
        });
    },

    // Fetches counts for each scheduled task list (Today, Next Week, etc.)
    // and stores result it in global state object
    fetchScheduledTaskCounts: function() {
        repo.fetchScheduledTaskCounts(function (response) {
            store.state.scheduledTaskCounts = response;
        });
    },

    // Update or adds a new task depending on whether task ID is present
    saveTask: function(task, callback) {
        let refresh = function (response) {
            service.refreshSidebarData();
            callback(response);
        }
        if (task.id) {
            this.updateTask(task, refresh);
        } else {
            this.addTask(task, refresh);
        }
    },

    // Add a new tasks to the DB
    addTask: function(task, callback) {
        repo.addTask(task, callback);
    },

    // Updates an existing task to the DB
    updateTask: function(task, callback) {
        repo.updateTask(task, callback);
    },

    // Deletes a existing task from the DB
    deleteTask: function(task, callback) {
        repo.deleteTask(task,  function (response) {
            service.refreshSidebarData();
            callback(response);
        });
    },

    /*
     |--------------------------------------------------------------------------
     | Project Services
     |--------------------------------------------------------------------------
     |
     */


    // Fetches projects and stores in global state object
    fetchProjects: function () {
        repo.fetchProjects(function (response) {
            store.state.projects = response;
        });
    },

    // Fetches a project by ID
    fetchProject: function(projectId, callback) {
        return repo.fetchProject(projectId,  callback);
    },

    // Update or adds a new project depending on whether task ID is present
    saveProject: function(project, callback) {
        let refresh = function (response) {
            service.fetchProjects();
            callback(response);
        }
        if (project.id) {
            this.updateProject(project, refresh);
        } else {
            this.addProject(project, refresh);
        }
    },

    // Add a new project to the DB
    addProject: function(project, callback) {
        repo.addProject(project,  callback);
    },

    // Updates an existing project to the DB
    updateProject: function(project, callback) {
        repo.updateProject(project,  callback);
    },

    // Deletes a project from the DB
    deleteProject: function(project, callback) {
        repo.deleteProject(project,  function (response) {
            this.fetchProjects();
            callback(response);
        });
    },

    /*
     |--------------------------------------------------------------------------
     | Context Services
     |--------------------------------------------------------------------------
     |
     */

    // Fetches contexts and stores in global state object
    fetchContexts: function () {
        repo.fetchContexts(function (response) {
            store.state.contexts = response;
        });
    },

    /*
     |--------------------------------------------------------------------------
     | Helper Functions
     |--------------------------------------------------------------------------
     |
     */

    playSound: function(filename) {
        document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="multimedia/' + filename + '.mp3" type="audio/mpeg" /><source src="multimedia/' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="multimedia/' + filename +'.mp3" /></audio>';
    }
};