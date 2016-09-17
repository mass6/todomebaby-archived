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
    initTagsInput: function() {

        // Use Bloodhound engine
        var engine = new Bloodhound({
            local: [
                {value: '@home'},
                {value: '@office'},
                {value: '@work'} ,
                {value: '@travel'}
            ],
            datumTokenizer: function(d) {
                return Bloodhound.tokenizers.whitespace(d.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace
        });

        // Initialize engine
        engine.initialize();

        // Initialize tokenfield
        $('.tokenfield-typeahead').tokenfield({
            typeahead: [null, {
                displayKey: 'value',
                source: engine.ttAdapter()
            }]
        });
    },
    init: function() {
        this.initiDueDatePicker();
        this.initToolTips();
        this.initTagsInput();
    }
};