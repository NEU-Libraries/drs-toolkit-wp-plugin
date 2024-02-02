const ColorSetting = Backbone.Model.extend({
    defaults: {
        name: '',
        value: [],
        label: '',
        tag: '',
        colorname: '',
        colorHex: '',
    },
});

export default ColorSetting;
