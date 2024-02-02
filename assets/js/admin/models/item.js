const Item = Backbone.Model.extend({
    defaults: {
        title: '',
        pid: '',
        thumbnail: '',
        repo: '',
        color: '',
        key_date: '',
        coords: '',
    },
});

export default Item;
