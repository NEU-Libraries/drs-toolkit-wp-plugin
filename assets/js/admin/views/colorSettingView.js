const ColorSettingView = Backbone.View.extend({
    color_row_template: wp.template('drstk-setting-colorinput'),
    tagName: 'tr',
    initialize: function () {
        this.render();
    },
    render: function () {
        if (this.model.attributes.tag == 'inputcolor') {
            this.$el.html(this.color_row_template(this.model.toJSON()));
        }
    },
    remove: function () {
        this.collection.remove(this.model);
    },
});

export default ColorSettingView;
