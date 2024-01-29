import { Collection } from 'backbone';
import Item from '../models/item';

const Items = Collection.extend({
    model: Item,
});

export default Items;
