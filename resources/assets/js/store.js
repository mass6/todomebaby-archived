export let store = {
    // Shared state object
    state: {
        projects: [],
        contexts: [],
        inbox: {
            tasks: []
        },
        scheduledTaskCounts: {},
        previousRoute: {},
        defaultRoute: {
            name: 'tasks.list',
            params: {'id': 'today'},
            path: '/lists/today'
        }
    }
};