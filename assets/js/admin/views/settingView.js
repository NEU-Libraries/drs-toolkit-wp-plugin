const SettingView = Backbone.View.extend({
    // Define templates using a mapping for easier access
    templates: {
        checkbox: wp.template('drstk-setting-checkbox'),
        select: wp.template('drstk-setting-select'),
        text: wp.template('drstk-setting-text'),
        number: wp.template('drstk-setting-number'),
    },
    tagName: 'tr',

    // Initialize the view and render it
    initialize: function () {
        this.render();
    },

    // Render the view
    render: function () {
        // Get the appropriate template based on the model's tag attribute
        const template = this.templates[this.model.attributes.tag];

        // If a matching template is found, render it
        if (template) {
            this.$el.html(template(this.model.toJSON()));
        }
    },
});

export default SettingView;
