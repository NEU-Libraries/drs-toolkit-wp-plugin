import Item from '../models/item';

const Items = Backbone.Collection.extend({
    model: Item,
});

export default Items;
