import ColorSetting from '../models/colorSetting';

const ColorSettings = Backbone.Collection.extend({
    model: ColorSetting,
});

export default ColorSettings;
