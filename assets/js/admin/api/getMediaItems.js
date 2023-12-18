const getMediaItemsController = ({ shortcode, currentTab, drstk }) => {
    jQuery('#local').html("<a class='button' id='wp_media'>Add or Browse Local Items</a><br/>");
    const items = getItemsFromLocalRepo(shortcode);

    items.forEach((item) => {
        const itemDetails = prepareItemDetails(item, currentTab, drstk);

        if (needAjaxCall(currentTab, itemDetails)) {
            fetchCustomMeta(item, currentTab).then((data) => {
                updateItemWithCustomMeta(data, itemDetails, currentTab);
                updateDOM(itemDetails, currentTab, shortcode);
            });
        } else {
            updateDOM(itemDetails, currentTab, shortcode);
        }
    });
};

const getItemsFromLocalRepo = (shortcode) => {
    return (shortcode.items && shortcode.items.where({ repo: 'local' })) || [];
};

const prepareItemDetails = (item, currentTab, drstk) => {
    const pid = item.get('pid');
    const thumbnail = item.get('thumbnail');
    const repo = 'local';
    const title = item.get('title');

    let thisItem = new drstk.Item();
    thisItem.set('pid', pid).set('thumbnail', thumbnail).set('repo', repo).set('title', title).set('coords', item.get('coords')).set('key_date', item.get('key_date'));

    return thisItem;
};

const needAjaxCall = (currentTab, item) => {
    return (currentTab === 6 && item.get('key_date') === undefined) || (currentTab === 5 && item.get('coords') === undefined);
};

const fetchCustomMeta = async (item, currentTab) => {
    const response = await fetch(item_admin_obj.ajax_url, {
        method: 'POST',
        body: JSON.stringify({
            action: 'get_custom_meta',
            _ajax_nonce: item_admin_obj.item_admin_nonce,
            pid: item.get('pid'),
        }),
    });

    return response.json();
};

const updateItemWithCustomMeta = (data, item, currentTab) => {
    if (currentTab === 5) {
        item.set('coords', data._map_coords[0]);
    }

    if (currentTab === 6) {
        item.set('key_date', data._timeline_date[0]);
    }
};

const updateDOM = (item, currentTab, shortcode) => {
    const view = new drstk.ItemView({ model: item });
    const pid = item.get('pid');

    jQuery('#local').append(view.el);

    if (currentTab === 6) {
        appendItemDetail(jQuery('#local'), 'key_date', item.get('key_date'), 'Date');
    }

    if (currentTab === 5) {
        appendItemDetail(jQuery('#local'), 'coords', item.get('coords'), 'Map Info');
    }

    if (shortcode.items && shortcode.items.where({ pid: pid }).length > 0) {
        jQuery('#local').find('li:last-of-type input').prop('checked', true);
    }
};

const appendItemDetail = (container, className, data, label) => {
    container.find('li:last-of-type').append(`<p>${label}: <span class='${className}'>${data}</span></p>`);
};

// This is where you'd initiate your process

function getMediaitemsController({ shortcode, currentTab, drstk }) {
    jQuery('#local').html("<a class='button' id='wp_media'>Add or Browse Local Items</a><br/>");
    if (
        shortcode.items != undefined &&
        shortcode.items.where({
            repo: 'local',
        }).length > 0
    ) {
        _.each(
            shortcode.items.where({
                repo: 'local',
            }),
            function (item) {
                const pid = item.get('pid');
                const thumbnail = item.get('thumbnail');
                const repo = 'local';
                const title = item.get('title');
                let thisItem = new drstk.Item();
                thisItem.set('pid', pid).set('thumbnail', thumbnail).set('repo', repo).set('title', title).set('coords', item.get('coords')).set('key_date', item.get('key_date'));
                if ((currentTab == 6 && thisItem.get('key_date') == undefined) || (currentTab == 5 && this_item.get('coords') == undefined)) {
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
                            if (currentTab == 5) {
                                thisItem.set('coords', data._map_coords[0]);
                                item.set('coords', data._map_coords[0]);
                            }
                            if (currentTab == 6) {
                                thisItem.set('key_date', data._timeline_date[0]);
                                item.set('key_date', data._timeline_date[0]);
                            }
                        },
                    });
                }
                view = new drstk.ItemView({
                    model: thisItem,
                });
                jQuery('#local').append(view.el);
                if (currentTab == 6) {
                    jQuery('#local')
                        .find('li:last-of-type')
                        .append("<p>Date: <span class='key_date'>" + this_item.get('key_date') + '</span></p>');
                }
                if (currentTab == 5) {
                    jQuery('#local')
                        .find('li:last-of-type')
                        .append("<p>Map Info: <span class='coords'>" + this_item.get('coords') + '</span></p>');
                }
                if (
                    shortcode.items != undefined &&
                    shortcode.items.where({
                        pid: pid,
                    }).length > 0
                ) {
                    jQuery('#local').find('li:last-of-type input').prop('checked', true);
                }
            }
        );
    }
}
