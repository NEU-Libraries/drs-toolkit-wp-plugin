import Setting from '../models/setting';

const Settings = Backbone.Collection.extend({
    model: Setting,
});

export default Settings;
