import { Model } from 'backbone';

const ColorSetting = Model.extend({
    name: '',
    value: [],
    label: '',
    tag: '',
    colorname: '',
    colorHex: '',
});

export default ColorSetting;
