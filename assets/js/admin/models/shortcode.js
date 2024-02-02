import Items from '../collections/items';
import Settings from '../collections/settings';
import ColorSettings from '../collections/colorSettings';

const Shortcode = Backbone.Model.extend({
    defaults: {
        type: '',
        items: new Items(),
        settings: new Settings(),
        colorsettings: new ColorSettings(),
    },
    // initialize the model
    initialize: function () {
        this.set('items', new Items());
        this.set('settings', new Settings());
        this.set('colorsettings', new ColorSettings());
    },
    // parse the response to get items and settings
    parse: function (response) {
        response.items = new Items(response.items);
        response.settings = new Settings(response.settings);
        response.colorsettings = new ColorSettings(response.colorsettings);
        return response;
    },
    // set the model
    set: function (attributes, options) {
        const mappings = {
            items: Items,
            settings: Settings,
            colorsettings: ColorSettings,
        };

        // If any of the attributes are not already a model, convert them to one
        Object.entries(mappings).forEach(([key, Class]) => {
            if (attributes[key] && !(attributes[key] instanceof Class)) {
                attributes[key] = new Class(attributes[key]);
            }
        });

        return Backbone.Model.prototype.set.call(this, attributes, options);
    },
});

export default Shortcode;
