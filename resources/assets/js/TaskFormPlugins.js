export let taskFormPlugins = {

    initiDueDatePicker: function() {
        let $input = $('#task-due-date').pickadate({format: 'yyyy-mm-dd'});
        let picker = $input.pickadate('picker');
        if (picker.get() !== '') {
            picker.set('select', picker.get(), { format: 'yyyy-mm-dd' });
        }
    },
    initToolTips: function() {
        $('[data-popup="tooltip"]').tooltip({
            template: '<div class="tooltip"><div class="bg-teal"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div></div>'
        });
    },
    init: function() {
        this.initiDueDatePicker();
        this.initToolTips();
    }
};