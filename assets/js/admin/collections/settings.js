import { Collection } from 'backbone';
import Setting from '../models/setting';

const Settings = Collection.extend({
    model: Setting,
});

export default Settings;
