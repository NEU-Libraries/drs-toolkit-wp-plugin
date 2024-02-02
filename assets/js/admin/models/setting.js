const Setting = Backbone.Model.extend({
    defaults: {
        name: '',
        value: [],
        choices: {},
        label: '',
        helper: '',
        tag: '',
        selectedId: '',
        colorHex: '',
        colorId: '',
    },
});

export default Setting;
