export let taskFormPlugins = {

    initiDueDatePicker: function(taskList) {
        let $input = $('#task-due-date').pickadate({format: 'yyyy-mm-dd'});
        let picker = $input.pickadate('picker');
        if (picker.get() !== '') {
            picker.set('select', picker.get(), { format: 'yyyy-mm-dd' });
        } else {
            if (taskList.listName === 'Today') {
                picker.set('select', moment().format('YYYY-MM-DD'), { format: 'yyyy-mm-dd' });
            } else if (taskList.listName === 'Tomorrow') {
                picker.set('select', moment().add(1, 'days').format('YYYY-MM-DD'), { format: 'yyyy-mm-dd' });
            }
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
            remote: {
                url: '/tags/typeahead/%QUERY',
                wildcard: '%QUERY',
            },
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

        // Prevent duplicate tokens from being created
        $('.tokenfield-typeahead').on('tokenfield:createtoken', function (event) {
            var existingTokens = $(this).tokenfield('getTokens');
            $.each(existingTokens, function(index, token) {
                if (token.value === event.attrs.value)
                    event.preventDefault();
            });
        });

        // Add class when token is created
        $('.tokenfield-typeahead').on('tokenfield:createdtoken', function (e) {
            if (e.attrs.value.charAt(0) === '@') {
                $(e.relatedTarget).addClass('bg-primary')
            } else {
                $(e.relatedTarget).addClass('bg-success')
            }
        });
    },
    init: function(taskList) {
        this.initiDueDatePicker(taskList);
        this.initToolTips();
        this.initTagsInput();
    }
};