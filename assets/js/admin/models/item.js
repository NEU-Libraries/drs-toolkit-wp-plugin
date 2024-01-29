import { Model } from 'backbone';

const Item = Model.extend({
    title: '',
    pid: '',
    thumbnail: '',
    repo: '',
    color: '',
    key_date: '',
    coords: '',
});

export default Item;
