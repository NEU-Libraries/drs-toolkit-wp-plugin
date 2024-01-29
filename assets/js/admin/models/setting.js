import { Model } from 'backbone';

const Setting = Model.extend({
    name: '',
    value: [],
    choices: {},
    label: '',
    helper: '',
    tag: '',
    selectedId: '',
    colorHex: '',
    colorId: '',
});

export default Setting;
