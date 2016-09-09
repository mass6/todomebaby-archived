export let store = {
    state: {
        projects: [],
        taskList: {
            listName: '',
            tasks: []
        },
        previousRoute: {},
        defaultRoute: {
            name: 'tasks.list',
            params: {'listName': 'today'},
            path: '/lists/today'
        }
    },
    initialize: function() {
        // fetch projects
        Vue.http.get('/projects').then(function (response) {
            store.state.projects = (response.data);
        });
        // fetch project counts
        // fetch context counts
    },
    setListName: function(name) {
        store.state.taskList.listName =  this.toTitleCase(this.hyphensToSpaces(name));
    },
    hyphensToSpaces: function(string) {
        return string.replace(/-/g, " ");
    },
    toTitleCase: function(string){
        return string.replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
    },
    fetchTaskList: function(listName, callback) {
        $.ajax({
            url: "/tasklists/" + listName,
            success: function(result){
                store.state.taskList.tasks = result;
                if (callback) {
                    callback();
                }
            },
            error: function(xhr,status,error) {
                console.log(error);
            }
        });
    },
    fetchTask: function(taskId, callback) {
        Vue.http.get('/tasks/' + taskId).then(function (response) {
            store.state.task = response.data;
            if (callback) {
                callback(response.data);
            }
        });
    },
    saveTask: function(task, callback) {
        if (task.id) {
            this.updateTask(task, callback);
        } else {
            this.addTask(task, callback);
        }
    },
    addTask: function(task, callback) {
        Vue.http.post('/tasks', task).then(function (response) {
            store.fetchTaskList(store.state.taskList.listId);
            if (callback) {
                callback();
            }
        });
    },
    updateTask: function(task, callback) {
        Vue.http.patch('/tasks/' + task.id, task).then(function (response) {
            if (callback) {
                callback();
            }
        });
    }
};