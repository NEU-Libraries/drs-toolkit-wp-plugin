import { Collection } from 'backbone';
import ColorSetting from '../models/colorSetting';

const ColorSettings = Collection.extend({
    model: ColorSetting,
});

export default ColorSettings;
