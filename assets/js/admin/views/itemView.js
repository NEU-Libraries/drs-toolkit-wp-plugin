const ItemView = Backbone.View.extend({
    tagName: 'li',
    itemTemplate: _.template(
        "<label for='tile-<%=pid%>'><img src='<%=thumbnail%>' /><br/><input id='tile-<%=pid%>' type='checkbox' class='tile <%=repo%>' value='<%=pid%>'/><span class='title'><%=title%></span></label>"
    ),
    itemNoImgTemplate: _.template(
        "<label for='tile-<%=pid%>'><span class='dashicons dashicons-format-image'></span><br/><input id='tile-<%=pid%>' type='checkbox' class='tile <%=repo%>' value='<%=pid%>'/><span class='title'><%=title%></span></label>"
    ),
    // initialize the view and render it
    initialize: function () {
        this.render();
    },
    // render the view
    render: function () {
        // Check if the model has a thumbnail attribute
        // Not using ternary operator because it's not as readable
        if (this.model.attributes.thumbnail === undefined) {
            // Use the template without an image if there's no thumbnail
            this.$el.html(this.itemNoImgTemplate(this.model.toJSON()));
        } else {
            // Use the template with an image if there's a thumbnail
            this.$el.html(this.itemTemplate(this.model.toJSON()));
        }
    },
});

export default ItemView;
