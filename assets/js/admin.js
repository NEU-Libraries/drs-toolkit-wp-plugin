/**
 * Backbone Application File
 * @package drstk.backbone_modal
 */
var drstk = {
    backbone_modal: {
        __instance: undefined,
    },
};

/**
 * Backbone models
 * Backbone.Model.extend is used to create model classes in Backbone.
 *
 * @see https://backbonejs.org/#Model-extend
 */

// TODO: See if this can be moved to a seperate file
// TODO: key_date convert to camel case key_date
drstk.Item = Backbone.Model.extend({
    title: '',
    pid: '',
    thumbnail: '',
    repo: '',
    color: '',
    key_date: '',
    coords: '',
});

drstk.Setting = Backbone.Model.extend({
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

drstk.ColorSetting = Backbone.Model.extend({
    name: '',
    value: [],
    label: '',
    tag: '',
    colorname: '',
    colorHex: '',
});

/**
 * Backbone collections
 * Backbone.Collection.extend is used to create collection classes in Backbone.
 * @see https://backbonejs.org/#Collection-extend
 */
drstk.Items = Backbone.Collection.extend({
    model: drstk.Item,
});

drstk.Settings = Backbone.Collection.extend({
    model: drstk.Setting,
});

drstk.ColorSettings = Backbone.Collection.extend({
    model: drstk.ColorSetting,
});

// This is main model where all the magic is stored
drstk.Shortcode = Backbone.Model.extend({
    defaults: {
        type: '',
        items: new drstk.Items(),
        settings: new drstk.Settings(),
        colorsettings: new drstk.ColorSettings(),
    },
    // initialize the model
    initialize: function () {
        this.set('items', new drstk.Items());
        this.set('settings', new drstk.Settings());
        this.set('colorsettings', new drstk.ColorSettings());
    },
    // parse the response to get items and settings
    parse: function (response) {
        response.items = new drstk.Items(response.items);
        response.settings = new drstk.Settings(response.settings);
        response.colorsettings = new drstk.ColorSettings(response.colorsettings);
        return response;
    },
    // set the model
    set: function (attributes, options) {
        const mappings = {
            items: drstk.Items,
            settings: drstk.Settings,
            colorsettings: drstk.ColorSettings,
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

/**
 * Backbone views
 * Backbone.View.extend is used to create view classes in Backbone.
 *
 * @see https://backbonejs.org/#View-extend
 */
drstk.ItemView = Backbone.View.extend({
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
let clickCounter = 1;
let colorArray = [];

drstk.SettingView = Backbone.View.extend({
    // Define templates using a mapping for easier access
    templates: {
        checkbox: wp.template('drstk-setting-checkbox'),
        select: wp.template('drstk-setting-select'),
        text: wp.template('drstk-setting-text'),
        number: wp.template('drstk-setting-number'),
    },
    tagName: 'tr',

    // Initialize the view and render it
    initialize: function () {
        this.render();
    },

    // Render the view
    render: function () {
        // Get the appropriate template based on the model's tag attribute
        const template = this.templates[this.model.attributes.tag];

        // If a matching template is found, render it
        if (template) {
            this.$el.html(template(this.model.toJSON()));
        }
    },
});

drstk.ColorSettingView = Backbone.View.extend({
    color_row_template: wp.template('drstk-setting-colorinput'),
    tagName: 'tr',
    initialize: function () {
        this.render();
    },
    render: function () {
        if (this.model.attributes.tag == 'inputcolor') {
            this.$el.html(this.color_row_template(this.model.toJSON()));
        }
    },
    remove: function () {
        this.collection.remove(this.model);
    },
});

/**
 * Primary Modal Application Class
 */
drstk.backbone_modal.Application = Backbone.View.extend({
    id: 'backbone_modal_dialog',
    events: {
        'change #drs-select-all-item': 'selectAllItem',
        'click .backbone_modal-close': 'closeModal',
        'click #btn-cancel': 'closeModal',
        'click #btn-ok': 'insertShortcode',
        'click .navigation-bar a': 'navigate',
        'click .backbone_modal-main article table .button': 'navigate',
        'change .tile': 'selectItem',
        'click .tablenav-pages a': 'paginate',
        'click .nav-tab': 'navigateShortcode',
        'click .search-button': 'search',
        'change #settings input': 'settingsChange',
        'change #settings select': 'settingsChange',
        "change #selected select[name='color']": 'changeColor',
        'click #local #wp_media': 'addMediaItems',
        "change select[name='dpla-sort']": 'dplaSort',
        "change select[name='drs-sort']": 'drsSort',
        'click .dpla-facets-button': 'dplaFacetToggle',
        'click .drs-facets-button': 'drsFacetToggle',
        'click .dpla-close-facets': 'dplaFacetToggle',
        'click .drs-close-facets': 'drsFacetToggle',
        'click .dpla-facet-add': 'dplaFacet',
        'click .drs-facet-add': 'drsFacet',
        'click .dpla-update-date': 'dplaUpdateDate',
        'click .dpla-facet-remove': 'dplaFacetRemove',
        'click .drs-facet-remove': 'drsFacetRemove',
        'click .dpla-expand-facet': 'dplaFacetExpand',
        'click .drs-expand-facet': 'drsFacetExpand',
        'click #addcolorbutton': 'settingsAddColor',
        'click .delete-color-row': 'deleteColorRow',
    },

    /**
     * Simple object to store any UI elements we need to use over the life of the application.
     */
    ui: {
        nav: undefined,
        content: undefined,
    },

    /**
     * Container to store our compiled templates. Not strictly necessary in such a simple example
     * but might be useful in a larger one.
     */
    templates: {},

    shortcode: null,
    geoCount: 0,
    timeCount: 0,
    selectAll: false,
    oldShortcode: null,
    collectionId: drstk_backbone_modal_l10n.collection_id,
    options: {},
    resultCount: 0,
    searchParams: {
        q: '',
        page: 1,
        facets: {},
        sort: '',
    },
    // TODO: this currenttab is not a great way to track the current tab, use string or constant instead
    currentTab: 0, // store our current tab as a variable for easy lookup
    tabs: {
        // dictionary of key/value pairs for our tabs
        1: 'single',
        2: 'tile',
        3: 'slider',
        4: 'media',
        5: 'map',
        6: 'timeline',
    },

    /**
     * Instantiates the Template object and triggers load.
     */
    // TODO: refactor code
    initialize: function (options) {
        'use strict';
        this.options = options;

        _.bindAll(
            this,
            'render',
            'preserveFocus',
            'closeModal',
            'insertShortcode',
            'navigate',
            'showTab',
            'getDRSitems',
            'selectItem',
            'paginate',
            'navigateShortcode',
            'search',
            'setDefaultSettings',

            'selectAllItem',
            'settingsAddColor',
            'deleteColorRow'
        );
        this.initializeTemplates();
        this.render();
        this.shortcode = new drstk.Shortcode({});
        if (this.options && this.options.currentTab != '') {
            let e = {
                currentTarget: '',
            };
            var num = _.invert(this.tabs)[this.options.currentTab];
            var words = {
                1: 'one',
                2: 'two',
                3: 'three',
                4: 'four',
                5: 'five',
                6: 'six',
            };
            var word = words[num];
            e.currentTarget = "<a href='#" + word + "'></a>";
            this.searchParams.q = '';
            this.searchParams.page = 1;
            this.navigate(e);
            this.currentTab = num;
            this.shortcode.type = this.tabs[this.currentTab];
        } else {
            this.currentTab = 1;
        }
        var self = this;
        clickCounter = 1;
        if (this.options && this.options.items && this.options.items.length > 0) {
            _.each(this.options.items, function (item, i) {
                if (i == 0) {
                    self.shortcode.items = new drstk.Items(item);
                } else {
                    self.shortcode.items.add(item);
                }
            });
        } else if (this.options) {
            //starting with collectionId
            self.selectAll = true;
            jQuery('.backbone_modal-main #drs-select-all-item').prop('checked', true);
        }
        if (this.options && ((this.options.items && this.options.items.length > 0) || (this.options.collectionId && this.options.collectionId.length > 0))) {
            var settings = this.options.settings;
            _.each(this.options.settings, function (setting, setting_name) {
                if (setting_name.match(/([a-zA-Z_0-9-]*)_color_desc_id/)) {
                    var desc = setting_name.match(/([a-zA-Z_0-9-]*)_color_desc_id/)[1];
                    var code = '#' + settings[desc + '_color_hex'];
                    if (desc && code) {
                        var colorsettings = self.shortcode.get('colorsettings');
                        // TODO: Change this to use `` instead of string concatenation
                        var name = 'label-text-' + clickCounter + '_desc';
                        var value = 'label-' + clickCounter;
                        var label = 'label-' + clickCounter;
                        desc = desc.replace('_', ' ');
                        colorsettings.add({
                            name: name,
                            value: value,
                            label: label,
                            tag: 'inputcolor',
                            colorname: desc,
                            colorHex: code,
                        });
                        self.shortcode.set('colorsettings', colorsettings);
                        var colored_ids = setting.split(',');
                        for (var x = 0; x < colored_ids.length; x++) {
                            colored_ids[x] = colored_ids[x].trim();
                        }
                        _.each(colored_ids, function (id) {
                            repo = id.split(':')[0];
                            if (repo != 'neu') {
                                id = id.split(':')[1];
                            }
                            var item = self.shortcode.items.where({
                                pid: id,
                            });
                            item[0].attributes['color'] = desc;
                        });
                        clickCounter++;
                    }
                }
            });
            e.currentTarget = jQuery(".nav-tab[href='#selected']");
            this.navigateShortcode(e);
        }
        if (this.options && this.options.oldShortcode) {
            this.oldShortcode = this.options.oldShortcode;
        }
    },

    /**
     * Creates compiled implementations of the templates. These compiled versions are created using
     * the wp.template class supplied by WordPress in 'wp-util'. Each template name maps to the ID of a
     * script tag ( without the 'tmpl-' namespace ) created in template-data.php.
     */
    initializeTemplates: function () {
        this.templates.window = wp.template('drstk-modal-window');
        this.templates.backdrop = wp.template('drstk-modal-backdrop');
        this.templates.menuItem = wp.template('drstk-modal-menu-item');
        this.templates.menuItemSeperator = wp.template('drstk-modal-menu-item-separator');
        this.templates.tabMenu = wp.template('drstk-modal-tab-menu');
        this.templates.tabItem = wp.template('drstk-modal-tab-item');
        this.templates.tabContent = wp.template('drstk-modal-tab-content');
    },

    /**
     * Assembles the UI from loaded templates.
     * @internal Obviously, if the templates fail to load, our modal never launches.
     */
    render: function () {
        'use strict';

        // Build the base window and backdrop, attaching them to the $el.
        // Setting the tab index allows us to capture focus and redirect it in Application.preserveFocus
        this.$el.attr('tabindex', '0').append(this.templates.window()).append(this.templates.backdrop());

        // Save a reference to the navigation bar's unordered list and populate it with items.
        // This is here mostly to demonstrate the use of the template class.
        this.ui.nav = this.$('.navigation-bar nav ul')
            .append(
                this.templates.menuItem({
                    url: '#one',
                    name: 'Single Item',
                })
            )
            .append(
                this.templates.menuItem({
                    url: '#two',
                    name: 'Tile Gallery',
                })
            )
            .append(
                this.templates.menuItem({
                    url: '#three',
                    name: 'Gallery Slider',
                })
            )
            .append(this.templates.menuItemSeperator())
            .append(
                this.templates.menuItem({
                    url: '#four',
                    name: 'Media Playlist',
                })
            )
            .append(
                this.templates.menuItem({
                    url: '#five',
                    name: 'Map',
                })
            )
            .append(
                this.templates.menuItem({
                    url: '#six',
                    name: 'Timeline',
                })
            );

        // The l10n object generated by wp_localize_script() should be available, but check to be sure.
        // Again, this is a trivial example for demonstration.
        if (typeof drstk_backbone_modal_l10n === 'object') {
            this.ui.content = this.$('.backbone_modal-main article').append('<p>' + drstk_backbone_modal_l10n.replace_message + '</p>');
        }

        // Handle any attempt to move focus out of the modal.
        jQuery(document).on('focusin', this.preserveFocus);

        // set overflow to "hidden" on the body so that it ignores any scroll events while the modal is active
        // and append the modal to the body.
        // TODO: this might better be represented as a class "modal-open" rather than a direct style declaration.
        jQuery('body')
            .css({
                overflow: 'hidden',
            })
            .append(this.$el);

        // Set focus on the modal to prevent accidental actions in the underlying page
        // Not strictly necessary, but nice to do.
        this.$el.focus();
    },

    /**
     * Ensures that keyboard focus remains within the Modal dialog.
     * @param e {object} A jQuery-normalized event object.
     */
    preserveFocus: function (e) {
        'use strict';
        if (this.$el[0] !== e.target && !this.$el.has(e.target).length) {
            this.$el.focus();
        }
    },

    /* close the modal */
    closeModal: function (e) {
        'use strict';
        clickCounter = 1;
        e.preventDefault;
        this.undelegateEvents();
        jQuery(document).off('focusin');
        jQuery('body').css({
            overflow: 'auto',
        });
        this.remove();
        if (this.oldShortcode && jQuery(e.currentTarget).attr('id') != 'btn-ok') {
            window.wp.media.editor.insert(this.oldShortcode);
        }
        drstk.backbone_modal.__instance = undefined;
    },

    /* select all items when 'Select All' checkbox is enabled */
    selectAllItem: function (e) {
        'use strict';
        e.preventDefault;
        if (jQuery('#drs-select-all-item').prop('checked')) {
            jQuery('#sortable-' + this.tabs[this.currentTab] + '-list')
                .find('li input')
                .prop('checked', true);
            jQuery('#sortable-' + this.tabs[this.currentTab] + '-list')
                .find('li input')
                .prop('disabled', true);
            jQuery('.tile').trigger('change'); //This will call the selectItem function for all the selected items.
            if (jQuery('.drs-pagination .tablenav-pages').children().length > 1) {
                this.loopThroughPages();
            }
            this.selectAll = true;
            return;
        }
        jQuery('#sortable-' + this.tabs[this.currentTab] + '-list')
            .find('li input')
            .prop('checked', false);
        jQuery('#sortable-' + this.tabs[this.currentTab] + '-list')
            .find('li input')
            .prop('disabled', false);
        this.shortcode.items.models.length = 0; //When the "Select All" checkbox is enabled, all the shortcodes should become null.
    },

    loopThroughPages: function () {
        var cur = parseInt(jQuery('.drs-pagination .tablenav-pages .current-page').text());
        var next = jQuery('.drs-pagination .tablenav-pages .current-page').next('a');
        if (parseInt(next.text()) != cur + 1) {
            //if it doesn't need to paginate anymore we just send it back to the first page
            jQuery('.drs-pagination .tablenav-pages .prev-page').next('a').trigger('click');
            return;
        }

        jQuery(next.trigger('click')); //trigger paginate
        jQuery('.drs-pagination .tablenav-pages .current-page').removeClass('current-page');
        next.addClass('current-page');
        this.loopThroughPages();
    },

    /* insert shortcode and close modal */
    insertShortcode: function (e) {
        insertShortcodeController(e, {
            shortcode: this.shortcode,
            closeModal: this.closeModal,
            currentTab: this.currentTab,
            tabs: this.tabs,
            collectionId: this.collectionId,
        });
    },

    setDefaultSettings: function () {
        this.shortcode = setDefaultSettingsController({ shortcode: this.shortcode, options: this.options });
    },

    settingsAddColor: function (e) {
        type = this.shortcode.get('type');
        colorsettings = this.shortcode.get('colorsettings');
        name = 'label-text-' + clickCounter + '_desc';
        value = 'label-' + clickCounter;
        label = 'label-' + clickCounter;
        colorname = 'label-color-' + clickCounter;
        colorsettings.add({
            name: name,
            value: value,
            label: label,
            tag: 'inputcolor',
            colorname: colorname,
            colorHex: '#0080ff',
        });
        this.shortcode.set('colorsettings', colorsettings);
        this.getSettings();
        clickCounter = clickCounter + 1;
    },

    // DONE
    // To small to be a controller
    deleteColorRow: function (e) {
        e.preventDefault();
        var id = '';
        var index_val = -1;
        id = jQuery(e.currentTarget).attr('id');
        type = this.shortcode.get('type');
        class_name_original = id.substr(7, id.length);
        class_name = '#settings > table.color-table > tbody > tr.' + class_name_original;
        colorsettings = this.shortcode.get('colorsettings');
        jQuery.each(colorsettings.models, function (index, value) {
            if (value.attributes['name'].toString() == class_name_original) {
                index_val = index;
            }
        });
        colorsettings.models.splice(index_val, 1);
        this.shortcode.set('colorsettings', colorsettings);
        this.getSettings();
        jQuery(class_name).remove();
    },

    /* navigation between shortcode types */
    // DONE; to small to be a controller;
    navigate: function (e) {
        'use strict';
        this.searchParams.page = 1;
        this.geoCount = 0;
        this.timeCount = 0;
        this.shortcode.set('settings', new drstk.Settings());
        this.shortcode.set('colorsettings', new drstk.ColorSettings());
        if (this.shortcode.items) {
            this.selectAll = false;
            this.shortcode.items = new drstk.Items();
        }
        jQuery('.navigation-bar a').removeClass('active');
        this.showTab(jQuery(e.currentTarget).attr('href'));
    },

    /* navigate tabs within a chosen shortcode type */
    // there are too many dependencies to make this a controller -> Not doing it
    navigateShortcode: function (e) {
        var path = jQuery(e.currentTarget).attr('href');
        jQuery('.nav-tab').removeClass('nav-tab-active');
        jQuery(e.currentTarget).addClass('nav-tab-active');
        this.searchParams.page = 1;
        this.searchParams.q = '';
        jQuery('.pane').hide();
        // @TODO this looks like switch is more appropriate
        if (path == '#drs') {
            jQuery('#drs').show();
            jQuery("#drs input[name='search']").val(this.searchParams.q);
            this.getDRSitems();
            return;
        }
        if (path == '#dpla') {
            jQuery("#dpla input[name='search']").val(this.searchParams.q);
            jQuery('#dpla').show();
            if (this.currentTab == 4) {
                jQuery('#dpla').html(
                    "<div class='notice notice-warning'><p>DPLA items cannot be used in embedded media. If you would like to use a media item from the DPLA, consider downloading it and upload it using the 'Local Items' tab.</p></div>"
                );
                return;
            }
            jQuery('#dpla ol').children('li').remove();
            // PMJ putting in an empty thing until the messages can be refactored
            // because of the new direct insert of URL code as of e814f1d8bf930512a8e4d079a7fdc15456932d59
            jQuery('.dpla-items').html("<div class='notice notice-info'></div>");
        }
        if (path == '#local') {
            jQuery('#local').show();
            this.getMediaitems();
            return;
        }
        if (path == '#selected') {
            jQuery('#selected').show();
            this.getSelecteditems();
            tab_name = this.tabs[this.currentTab];
            var self = this;

            //Display items as disabled after switching tab between DRSItems and Selected items if the select-all
            //checkbox is enables
            if (jQuery('#drs-select-all-item').prop('checked')) {
                jQuery('#selected #sortable-' + tab_name + '-list')
                    .find('li input')
                    .prop('disabled', true);
            }
            jQuery('#selected #sortable-' + tab_name + '-list').sortable({
                update: function (event, ui) {
                    _.clone(shortcode.items.models).forEach((model) => model.destroy());

                    Array.from(event.target.children).forEach((item) => {
                        const pid = jQuery(item).find('input').val();
                        const title = jQuery(item).find('.title').text();
                        const thumbnail = jQuery(item).find('img').attr('src');
                        const repo = jQuery(item).find('input').attr('class').split(' ')[1];

                        if (shortcode.items.length === 0) {
                            shortcode.items = new drstk.Items({
                                title,
                                pid,
                                thumbnail,
                                repo,
                            });
                        } else {
                            shortcode.items.add({
                                title,
                                pid,
                                thumbnail,
                                repo,
                            });
                        }
                    });
                },
            });
            return;
        }
        if (path == '#settings') {
            jQuery('#settings').show();
            this.getSettings();
        }
    },

    // Not doing it, too small
    showTab: function (id) {
        jQuery('.backbone_modal-main article').html('');
        var title = '';
        switch (id) {
            case '#one':
                this.currentTab = 1;
                title = 'Single Item';
                //clear items if there are more than one at this point
                if (this.shortcode.items != undefined && this.shortcode.items.length > 1) {
                    var self = this;
                    _.each(_.clone(this.shortcode.items.models), function (item) {
                        item.destroy();
                    });
                }
                break;
            case '#two':
                this.currentTab = 2;
                title = 'Tile Gallery';
                break;
            case '#three':
                this.currentTab = 3;
                title = 'Gallery Slider';
                break;
            case '#four':
                this.currentTab = 4;
                title = 'Media Playlist';
                break;
            case '#five':
                this.currentTab = 5;
                title = 'Map';
                break;
            case '#six':
                this.currentTab = 6;
                title = 'Timeline';
                break;
        }
        jQuery('.backbone_modal-main article').append(
            this.templates.tabContent({
                title: title,
                type: this.tabs[this.currentTab],
            })
        );
        jQuery('.navigation-bar a[href=' + id + ']').addClass('active');
        jQuery('#drs').show();
        if (!this.selectAll) {
            this.getDRSitems();
        }
        this.shortcode.set({
            type: this.tabs[this.currentTab],
        });
        this.setDefaultSettings();
    },

    getDRSitems: function () {
        const data = getDRSItemsController({
            currentTab: this.currentTab,
            searchParams: this.searchParams,
            tabs: this.tabs,
            geoCount: this.geoCount,
            timeCount: this.timeCount,
            shortcodeItems: this.shortcode.items,
            selectAll: this.selectAll,
            drstk: drstk,
        });
        this.geoCount = data.geoCount;
        this.timeCount = data.timeCount;
    },

    selectItem: function (e) {
        const data = selectItemController(e, { shortcode: this.shortcode, searchParams: this.searchParams });
        this.shortcode = data.shortcode;
        this.searchParams = data.searchParams;
    },

    // lets reduce the code
    // done, not much in there though
    paginate: function (e) {
        let val = jQuery.trim(jQuery(e.currentTarget).html());
        const type = jQuery(e.currentTarget).parents('.pane').attr('id');
        const currentPage = jQuery(`#${type} .tablenav-pages .current-page`).html();

        if (val === '&lt;&lt;') val = parseInt(current_page) - 1;
        if (val === '&gt;&gt;') {
            val = parseInt(currentPage) + 1;
            const isLastPage = jQuery(`#${type} .tablenav-pages .current-page`).next('a').html() === '&gt;&gt;';
            if (isLastPage) val = 0;
        }
        if (jQuery.isNumeric(val) && val != 0) {
            this.searchParams.page = val;
            if (type == 'drs') {
                this.getDRSitems();
            } else if (type == 'dpla') {
                this.getDPLAitems();
            }
        }
    },

    // TODO: Move it to a controller get confirmation from Patrick though
    getDPLAitems: function () {
        if (this.currentTab == 4) {
            this.searchParams.avfilter = true;
        } else {
            delete this.searchParams.avfilter;
        }
        if (this.currentTab == 5) {
            this.searchParams.spatialfilter = true;
        } else {
            delete this.searchParams.spatialfilter;
        }
        if (this.currentTab == 6) {
            this.searchParams.timefilter = true;
        } else {
            delete this.searchParams.timefilter;
        }
        var self = this;
        tab_name = this.tabs[this.currentTab];
        jQuery.post(
            dpla_ajax_obj.ajax_url,
            {
                _ajax_nonce: dpla_ajax_obj.dpla_ajax_nonce,
                action: 'get_dpla_code',
                params: this.searchParams,
            },
            function (data) {
                var data = jQuery.parseJSON(data);
                jQuery('#dpla #sortable-' + tab_name + '-list')
                    .children('li')
                    .remove();
                if (data.count > 0) {
                    jQuery('.dpla-items').html('');
                    jQuery.each(data.docs, function (id, item) {
                        if (self.currentTab == 6) {
                            date = getDateFromSourceResource(item.sourceResource);
                        } else {
                            date = '';
                        }
                        if (self.currentTab == 5) {
                            coords = item.sourceResource.spatial[0].name;
                            if (item.sourceResource.spatial[0].coordinates != '' && item.sourceResource.spatial[0].coordinates != undefined) {
                                coords = item.sourceResource.spatial[0].coordinates;
                            }
                        } else {
                            coords = '';
                        }
                        if ((self.currentTab == 6 && date != '') || (self.currentTab == 5 && coords != '') || (self.currentTab != 5 && self.currentTab != 6)) {
                            this_item = new drstk.Item();
                            var title = item.sourceResource.title;
                            if (Array.isArray(title)) {
                                title = title[0];
                            }
                            this_item.set('pid', item.id).set('thumbnail', item.object).set('repo', 'dpla').set('title', title);
                            if (self.currentTab == 6) {
                                this_item.set('key_date', date);
                            }
                            if (self.currentTab == 5) {
                                this_item.set('coords', coords);
                            }
                            view = new drstk.ItemView({
                                model: this_item,
                            });
                            jQuery('#dpla #sortable-' + tab_name + '-list').append(view.el);
                            if (self.currentTab == 6) {
                                jQuery('#dpla #sortable-' + tab_name + '-list')
                                    .find('li:last-of-type')
                                    .append("<p>Date: <span class='key_date hidden'>" + date.join('-') + '</span>' + item.sourceResource.date.displayDate + '</p>');
                            }
                            if (self.currentTab == 5) {
                                jQuery('#dpla #sortable-' + tab_name + '-list')
                                    .find('li:last-of-type')
                                    .append("<p>Map Info: <span class='coords hidden'>" + this_item.get('coords') + '</span>' + this_item.get('coords') + '</p>');
                            }
                            if (
                                self.shortcode.items != undefined &&
                                self.shortcode.items.where({
                                    pid: item.id,
                                }).length > 0
                            ) {
                                jQuery('#dpla #sortable-' + tab_name + '-list')
                                    .find('li:last-of-type input')
                                    .prop('checked', true);
                                short_item = self.shortcode.items.where({
                                    pid: item.id,
                                })[0];
                                if (!short_item.get('title')) {
                                    short_item.set('title', title);
                                }
                                if (!short_item.get('thumbnail')) {
                                    short_item.set('thumbnail', item.object);
                                }
                                if (
                                    (!short_item.get('key_date') ||
                                        short_item.get('key_date') == '' ||
                                        short_item.get('key_date') == undefined ||
                                        short_item.get('key_date') == []) &&
                                    self.currentTab == 6
                                ) {
                                    short_item.set('key_date', date);
                                }
                                if ((!short_item.get('coords') || short_item.get('coords') == '' || short_item.get('coords') == undefined) && self.currentTab == 5) {
                                    short_item.set('coords', coords);
                                }
                            }
                        }
                    });
                    if (self.searchParams.q != '') {
                        //too much pagination if there isn't a query
                        self.updateDPLAPagination(data);
                    }
                    if (self.searchParams.facets != {}) {
                        jQuery('.dpla-type, .dpla-subject').html('');
                        _.each(data.facets, function (facet, facet_name) {
                            if (facet_name == 'sourceResource.contributor' || facet_name == 'sourceResource.subject.name' || facet_name == 'sourceResource.type') {
                                if (facet_name == 'sourceResource.contributor') {
                                    this_facet = 'creator';
                                }
                                if (facet_name == 'sourceResource.subject.name') {
                                    this_facet = 'subject';
                                }
                                if (facet_name == 'sourceResource.type') {
                                    this_facet = 'type';
                                }
                                jQuery('.dpla-' + this_facet).html('<b>' + this_facet.charAt(0).toUpperCase() + this_facet.slice(1) + '</b>');
                                if (facet.terms != undefined) {
                                    if (facet.terms.length > 0) {
                                        for (var i = 0; i <= 4; i++) {
                                            if (facet.terms[i] != undefined) {
                                                facet_val = facet.terms[i].term;
                                                facet_count = facet.terms[i].count;
                                                facet_html =
                                                    "<tr><td><a href='' data-facet-val='" +
                                                    facet_val +
                                                    "' data-facet-name='" +
                                                    this_facet +
                                                    "' class='dpla-facet-add'>" +
                                                    facet_val +
                                                    "</a></td><td><a href=''>" +
                                                    facet_count +
                                                    '</a></td></tr>';
                                                jQuery('.dpla-' + this_facet).append(facet_html);
                                            }
                                        }
                                        if (facet.terms.length > 5) {
                                            facet_html =
                                                "<a href='' class='dpla-expand-facet' data-facet-name='" +
                                                this_facet +
                                                "'>View More</a><div class='dpla-expanded-facet-" +
                                                this_facet +
                                                " hidden'><table>";
                                            _.each(facet.terms, function (facet_obj, i) {
                                                if (i > 4) {
                                                    //don't repeat already displayed facets
                                                    facet_html +=
                                                        "<tr><td><a href='' data-facet-val='" +
                                                        facet_obj.term +
                                                        "' data-facet-name='" +
                                                        this_facet +
                                                        "' class='dpla-facet-add'>" +
                                                        facet_obj.term +
                                                        "</a></td><td><a href=''>" +
                                                        facet_obj.count +
                                                        '</a></td></tr>';
                                                }
                                            });
                                            facet_html += '</table></div>';
                                            jQuery('.dpla-' + this_facet).append(facet_html);
                                        }
                                    }
                                }
                            }
                        });
                        jQuery('.dpla-date').html(
                            "<b>Date Created</b><br/><div class='dpla-date-slider'></div><span class='start'></span> - <span class='end'> </span><a class='button dpla-update-date'>Update</a>"
                        );
                        dates = [1000, new Date().getFullYear()];
                        var min = 1000;
                        var max = new Date().getFullYear();
                        if (self.searchParams.facets.date != undefined) {
                            dates = self.searchParams.facets.date;
                        }
                        jQuery('.dpla-date-slider').slider({
                            range: true,
                            min: parseInt(min),
                            max: parseInt(max),
                            values: dates,
                            slide: function (event, ui) {
                                self.searchParams.facets.date = [ui.values[0], ui.values[1]];
                                jQuery('.dpla-date .start').text(ui.values[0]);
                                jQuery('.dpla-date .end').text(ui.values[1]);
                            },
                            create: function (event) {
                                if (self.searchParams.facets.date != undefined) {
                                    jQuery('.dpla-date .start').text(self.searchParams.facets.date[0]);
                                    jQuery('.dpla-date .end').text(self.searchParams.facets.date[1]);
                                } else {
                                    jQuery('.dpla-date .start').text(parseInt(min));
                                    jQuery('.dpla-date .end').text(parseInt(max));
                                }
                            },
                        });
                        facet_buttons = '';
                        _.each(self.searchParams.facets, function (facet_val, facet_name) {
                            if (facet_name != 'date') {
                                if (typeof facet_val == 'string') {
                                    facet_buttons +=
                                        "<a href='' data-facet-name='" +
                                        facet_name +
                                        "' data-facet-val='" +
                                        facet_val +
                                        "' class='button dpla-facet-remove'>" +
                                        facet_name.charAt(0).toUpperCase() +
                                        facet_name.slice(1) +
                                        ' : ' +
                                        facet_val +
                                        " <span class='dashicons dashicons-trash'> </span></a>";
                                } else {
                                    _.each(facet_val, function (facet_value) {
                                        facet_buttons +=
                                            "<a href='' data-facet-name='" +
                                            facet_name +
                                            "' data-facet-val='" +
                                            facet_value +
                                            "' class='button dpla-facet-remove'>" +
                                            facet_name.charAt(0).toUpperCase() +
                                            facet_name.slice(1) +
                                            ' : ' +
                                            facet_value +
                                            " <span class='dashicons dashicons-trash'> </span></a>";
                                    });
                                }
                            }
                        });
                        jQuery('.dpla-chosen').html(facet_buttons);
                    }
                } else {
                    jQuery('.dpla-items').html("<div class='notice notice-warning'><p>No results were retrieved for your query. Please try a different query.</p></div>");
                    jQuery('#dpla-pagination').html('');
                }
            }
        );
        if (jQuery('.dpla-facets-button').hasClass('hidden') && jQuery('.dpla-facets').hasClass('hidden')) {
            jQuery('.dpla-facets-button').removeClass('hidden');
        }
    },

    updateDPLAPagination: function (data) {
        num_pages = Math.round(data.count / data.limit);
        current_page = parseInt(this.searchParams.page);
        if (num_pages > 1) {
            var pagination = '';
            if (current_page > 1) {
                pagination += "<a href='#' class='prev-page' data-val='" + parseInt(current_page - 1) + "'>&lt;&lt;</a>";
            }
            if (current_page >= 3) {
                pagination += "<a href='#' class=''>" + parseInt(current_page - 2) + '</a>';
            }
            if (current_page >= 2) {
                pagination += "<a href='#' class=''>" + parseInt(current_page - 1) + '</a>';
            }
            pagination += "<a href='#' class='current-page active'>" + current_page + '</a>';
            if (current_page + 1 < num_pages) {
                pagination += "<a href='#' class=''>" + parseInt(current_page + 1) + '</a>';
            }
            if (current_page + 2 < num_pages) {
                pagination += "<a href='#' class=''>" + parseInt(current_page + 2) + '</a>';
            }
            if (current_page + 1 != num_pages) {
                pagination += "<a href='#' class='next-page' data-val='" + parseInt(current_page + 1) + "'>&gt;&gt;</a>";
            }
            jQuery('#dpla-pagination').html("<span class='tablenav'><span class='tablenav-pages'>" + pagination + '</span></span>');
        } else {
            jQuery('#dpla-pagination').html('');
        }
    },

    search: function (e) {
        this.searchParams.q = jQuery(e.currentTarget).siblings('input.drstk-search-input').val();
        parent = jQuery(e.currentTarget).parents('.pane').attr('id');
        if (parent == 'drs') {
            this.getDRSitems();
        } else if (parent == 'dpla') {
            this.getDPLAitems();
        }
    },

    getSelecteditems: function () {
        getSelecteditemsController({
            tabs: this.tabs,
            currentTab: this.currentTab,
            shortcode: this.shortcode,
            selectAll: this.selectAll,
            drstk: drstk,
            options: this.options,
        });
    },

    // common for 2 functions, likely to group all the three into one file
    // was not able to move it to a seperate file as it was not working as expected
    getSettings: function () {
        jQuery('#settings').html('<table />');
        _.each(this.shortcode.get('settings').models, function (setting, i) {
            var settingView = new drstk.SettingView({
                model: setting,
            });
            jQuery('#settings table').append(settingView.el);
            jQuery('#settings table tr:last-of-type').addClass(setting.get('name'));
        });
        type = this.shortcode.get('type');
        if (type == 'map' || type == 'timeline') {
            jQuery('#settings').append(
                "<div class='toolkitcolors'><h4>Color Settings</h4><button type='button' id ='addcolorbutton'>Add Color</button><br/><table class ='color-table striped'>" +
                    '<tbody>' +
                    "<tr class='colorheader'>" +
                    '<td><h5>Description</h5></td>' +
                    '<td><h5>Color Value</h5></td>' +
                    '</tr></tbody>' +
                    '</table></div>'
            );
            _.each(this.shortcode.get('colorsettings').models, function (colorsetting, i) {
                var colorsettingView = new drstk.ColorSettingView({
                    model: colorsetting,
                });
                jQuery('#settings .color-table').append(colorsettingView.el);
                jQuery(jQuery('#settings .color-table tr:last-of-type').addClass(colorsetting.get('name')));
            });
        }
    },

    // settings, feel like there can be a common settings file that handles all the settings related functions
    settingsChange: function (e) {
        this.shortcode = settingsChangeController(e, { shortcode: this.shortcode });
    },

    // this is too small to be a controller leaving it here for now
    changeColor: function (e) {
        color = jQuery(e.currentTarget).val();
        if (color != '') {
            pid = jQuery(e.currentTarget).siblings('.tile').val();
            item = this.shortcode.items.where({
                pid: pid,
            });
            item[0].set({
                color: color,
            });
        }
    },

    // TODO: this can be a controller
    // NOTES: ajax call is there, need to move it into ajax files
    getMediaitems: function () {
        jQuery('#local').html("<a class='button' id='wp_media'>Add or Browse Local Items</a><br/>");
        if (
            this.shortcode.items != undefined &&
            this.shortcode.items.where({
                repo: 'local',
            }).length > 0
        ) {
            var self = this;
            _.each(
                this.shortcode.items.where({
                    repo: 'local',
                }),
                function (item) {
                    pid = item.get('pid');
                    thumbnail = item.get('thumbnail');
                    repo = 'local';
                    title = item.get('title');
                    this_item = new drstk.Item();
                    this_item
                        .set('pid', pid)
                        .set('thumbnail', thumbnail)
                        .set('repo', repo)
                        .set('title', title)
                        .set('coords', item.get('coords'))
                        .set('key_date', item.get('key_date'));
                    if ((self.currentTab == 6 && this_item.get('key_date') == undefined) || (self.currentTab == 5 && this_item.get('coords') == undefined)) {
                        jQuery.ajax({
                            url: item_admin_obj.ajax_url,
                            type: 'POST',
                            async: false,
                            data: {
                                action: 'get_custom_meta',
                                _ajax_nonce: item_admin_obj.item_admin_nonce,
                                pid: item.get('pid'),
                            },
                            success: function (data) {
                                if (self.currentTab == 5) {
                                    this_item.set('coords', data._map_coords[0]);
                                    item.set('coords', data._map_coords[0]);
                                }
                                if (self.currentTab == 6) {
                                    this_item.set('key_date', data._timeline_date[0]);
                                    item.set('key_date', data._timeline_date[0]);
                                }
                            },
                        });
                    }
                    view = new drstk.ItemView({
                        model: this_item,
                    });
                    jQuery('#local').append(view.el);
                    if (self.currentTab == 6) {
                        jQuery('#local')
                            .find('li:last-of-type')
                            .append("<p>Date: <span class='key_date'>" + this_item.get('key_date') + '</span></p>');
                    }
                    if (self.currentTab == 5) {
                        jQuery('#local')
                            .find('li:last-of-type')
                            .append("<p>Map Info: <span class='coords'>" + this_item.get('coords') + '</span></p>');
                    }
                    if (
                        self.shortcode.items != undefined &&
                        self.shortcode.items.where({
                            pid: pid,
                        }).length > 0
                    ) {
                        jQuery('#local').find('li:last-of-type input').prop('checked', true);
                    }
                }
            );
        }
    },

    addMediaItems: function (e) {
        if (typeof frame !== 'undefined') frame.close();
        if (this.currentTab == 1) {
            multiple = false;
        } else {
            multiple = true;
        }
        var self = this;
        frame = wp.media.frames.drstk_frame = wp.media({
            title: 'Select Images',
            button: {
                text: 'Add Selected Images',
            },
            multiple: multiple,
        });
        frame
            .on('select', function () {
                var files = frame.state().get('selection').toJSON();
                jQuery.each(files, function (i) {
                    pid = this.id.toString();
                    title = this.title;
                    thumbnail = this.sizes != undefined ? this.sizes.thumbnail.url : this.image.src;
                    repo = 'local';
                    if (
                        self.shortcode.items === undefined ||
                        self.shortcode.items.where({
                            pid: pid,
                        }).length == 0
                    ) {
                        this_item = new drstk.Item();
                        this_item.set('pid', pid).set('thumbnail', thumbnail).set('repo', repo).set('title', title);
                        if (self.currentTab == 5 || self.currentTab == 6) {
                            jQuery.ajax({
                                url: item_admin_obj.ajax_url,
                                type: 'POST',
                                async: false,
                                data: {
                                    action: 'get_custom_meta',
                                    _ajax_nonce: item_admin_obj.item_admin_nonce,
                                    pid: this_item.get('pid'),
                                },
                                success: function (data) {
                                    if (self.currentTab == 6) {
                                        this_item.set('key_date', data._timeline_date[0]);
                                    }
                                    if (self.currentTab == 5) {
                                        this_item.set('coords', data._map_coords[0]);
                                    }
                                },
                            });
                        }
                        if (self.shortcode.items === undefined) {
                            self.shortcode.items = new drstk.Items(this_item);
                        } else if (
                            self.shortcode.items.where({
                                pid: pid,
                            }).length == 0
                        ) {
                            self.shortcode.items.add(this_item);
                        }
                        view = new drstk.ItemView({
                            model: this_item,
                        });
                        jQuery('#local').append(view.el);
                        jQuery('#local').find('li:last-of-type input').prop('checked', true);
                        if (self.currentTab == 6) {
                            jQuery('#local')
                                .find('li:last-of-type')
                                .append("<p>Date: <span class='key_date'>" + this_item.get('key_date') + '</span></p>');
                        }
                        if (self.currentTab == 5) {
                            jQuery('#local')
                                .find('li:last-of-type')
                                .append("<p>Map Info: <span class='coords'>" + this_item.get('coords') + '</span></p>');
                        }
                    }
                    if (self.currentTab == 1) {
                        jQuery.ajax({
                            url: item_admin_obj.ajax_url,
                            type: 'POST',
                            data: {
                                action: 'get_post_meta',
                                _ajax_nonce: item_admin_obj.item_admin_nonce,
                                pid: pid,
                            },
                            success: function (data) {
                                choices = {};
                                settings = self.shortcode.get('settings');
                                if (data.post_title) {
                                    choices['title'] = 'Title';
                                }
                                if (data.post_excerpt) {
                                    choices['caption'] = 'Caption';
                                }
                                oldmeta = settings.where({
                                    name: 'metadata',
                                });
                                settings.remove(oldmeta);
                                if (Object.keys(choices).length > 0) {
                                    settings.add({
                                        name: 'metadata',
                                        label: 'Metadata to Display',
                                        tag: 'checkbox',
                                        value: [],
                                        choices: choices,
                                    });
                                    self.shortcode.set('settings', settings);
                                }
                            },
                        });
                    }
                });
            })
            .open();
    },

    //TODO: facet files can have a common controller file, lot of similarity between dpla, and drs
    dplaSort: function (e) {
        e.preventDefault();
        this.searchParams.sort = jQuery("select[name='dpla-sort']").val();
        this.getDPLAitems();
    },

    dplaFacet: function (e) {
        e.preventDefault();
        link = jQuery(e.currentTarget);
        if (this.searchParams.facets[link.data('facet-name')] == undefined) {
            this.searchParams.facets[link.data('facet-name')] = link.data('facet-val');
        } else if (this.searchParams.facets[link.data('facet-name')].length > 0) {
            orig_value = this.searchParams.facets[link.data('facet-name')];
            if (typeof orig_value == 'string') {
                this.searchParams.facets[link.data('facet-name')] = [orig_value, link.data('facet-val')];
            } else {
                this.searchParams.facets[link.data('facet-name')].push(link.data('facet-val'));
            }
        }
        this.getDPLAitems();
    },

    dplaFacetToggle: function (e) {
        e.preventDefault();
        jQuery('.dpla-facets').toggleClass('hidden');
        jQuery('#dpla ol').toggleClass('fullwidth');
        if (!jQuery('.dpla-facets').hasClass('hidden')) {
            jQuery('.dpla-facets-button').addClass('hidden');
        } else {
            jQuery('.dpla-facets-button').removeClass('hidden');
        }
    },

    dplaUpdateDate: function (e) {
        e.preventDefault();
        this.getDPLAitems();
    },

    dplaFacetRemove: function (e) {
        e.preventDefault();
        link = jQuery(e.currentTarget);
        values = this.searchParams.facets[link.data('facet-name')];
        if (link.data('facet-name') != 'date') {
            new_values = [];
            if (typeof values != 'string') {
                _.each(values, function (val) {
                    if (val != link.data('facet-val')) {
                        new_values.push(val);
                    }
                });
            }
            if (new_values.length == 0) {
                delete this.searchParams.facets[link.data('facet-name')];
            } else {
                this.searchParams.facets[link.data('facet-name')] = new_values;
            }
            this.getDPLAitems();
        }
    },

    dplaFacetExpand: function (e) {
        e.preventDefault();
        link = jQuery(e.currentTarget);
        facet_name = link.data('facet-name');
        jQuery('.dpla-expanded-facet-' + facet_name).toggleClass('hidden');
        if (!jQuery('.dpla-expanded-facet-' + facet_name).hasClass('hidden')) {
            link.text('View Less');
        } else {
            link.text('View More');
        }
    },

    // This is not required to be changed from this file

    drsSort: function (e) {
        e.preventDefault();
        this.searchParams.sort = jQuery("select[name='drs-sort']").val();
        this.getDRSitems();
    },

    drsFacet: function (e) {
        e.preventDefault();
        link = jQuery(e.currentTarget);
        if (this.searchParams.facets[link.data('facet-name')] == undefined) {
            this.searchParams.facets[link.data('facet-name')] = link.data('facet-val');
        } else if (this.searchParams.facets[link.data('facet-name')].length > 0) {
            orig_value = this.searchParams.facets[link.data('facet-name')];
            if (typeof orig_value == 'string') {
                this.searchParams.facets[link.data('facet-name')] = [orig_value, link.data('facet-val')];
            } else {
                this.searchParams.facets[link.data('facet-name')].push(link.data('facet-val'));
            }
        }
        this.getDRSitems();
    },

    drsFacetToggle: function (e) {
        e.preventDefault();
        jQuery('.drs-facets').toggleClass('hidden');
        jQuery('#drs ol').toggleClass('fullwidth');
        if (!jQuery('.drs-facets').hasClass('hidden')) {
            jQuery('.drs-facets-button').addClass('hidden');
        } else {
            jQuery('.drs-facets-button').removeClass('hidden');
        }
    },

    drsFacetRemove: function (e) {
        e.preventDefault();
        link = jQuery(e.currentTarget);
        values = this.searchParams.facets[link.data('facet-name')];
        new_values = [];
        if (typeof values != 'string') {
            _.each(values, function (val) {
                if (val != link.data('facet-val')) {
                    new_values.push(val);
                }
            });
        }
        if (new_values.length == 0) {
            delete this.searchParams.facets[link.data('facet-name')];
        } else {
            this.searchParams.facets[link.data('facet-name')] = new_values;
        }
        this.getDRSitems();
    },

    drsFacetExpand: function (e) {
        e.preventDefault();
        link = jQuery(e.currentTarget);
        facet_name = link.data('facet-name');
        jQuery('.drs-expanded-facet-' + facet_name).toggleClass('hidden');
        if (!jQuery('.drs-expanded-facet-' + facet_name).hasClass('hidden')) {
            link.text('View Less');
        } else {
            link.text('View More');
        }
    },
});

jQuery(function ($) {
    'use strict';
    /**
     * Attach a click event to the meta-box button that instantiates the Application object, if it's not already open.
     */
    $('body').on('click', '#drs-backbone_modal', function (e) {
        e.preventDefault();
        if (drstk.backbone_modal.__instance === undefined) {
            drstk.backbone_modal.__instance = new drstk.backbone_modal.Application();
        }
    });
});
