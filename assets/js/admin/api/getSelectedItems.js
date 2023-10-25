function appendSingleItem(item, { currentTab, tabs, shortcode, drstk, options }) {
    const tabName = tabs[this.currentTab];

    // Create a new item view and append it to the list
    const itemView = new drstk.ItemView({ model: item });
    jQuery(`#selected #sortable-${tabName}-list`).append(itemView.el);

    // Additional logic for tabs 5 and 6
    if ([5, 6].includes(currentTab)) {
        let colors = '';

        // Iterate through color settings and build color options
        shortcode.get('colorsettings').models.forEach((colorModel) => {
            let color = colorModel.attributes.colorname;
            let presetColors;

            if (options?.settings) {
                presetColors = options.settings[`${color}_color_desc_id`];
            } else {
                presetColors = options[`${color}_id`] || this.options[color];
            }

            // Convert presetColors to array
            presetColors = presetColors?.split(',').map((str) => str.trim());

            if (presetColors?.includes(item.attributes.pid)) {
                item.set('color', color);
            }

            colors += `<option value="${color}"${item.attributes.color === color ? ' selected' : ''}>${color.charAt(0).toUpperCase() + color.slice(1)}</option>`;
        });

        // Append color options to the last item in the list
        jQuery(`#selected #sortable-${tabName}-list li:last-of-type label`).append(`
            <br/>Color label (see Settings tab):<br/> 
            <select name="color"><option value="">Color Label</option>${colors}</select>
        `);
    }

    // Check the item if it exists in the shortcode items
    if (shortcode.items.where({ pid: item.attributes.pid }).length > 0) {
        jQuery(`#selected #sortable-${tabName}-list li:last-of-type input`).prop('checked', true);
    }
}

/**
 * Controller to handle and display selected items.
 *
 * @param {Object} params - An object containing:
 *      tabs: Array of tab names,
 *      currentTab: Index of the current tab,
 *      shortcode: Object with information on selected items,
 *      selectAll: Boolean indicating if "select all" option is checked,
 *      drstk: DRSTK object,
 *      options: Various options.
 */
function getSelecteditemsController({ tabs, currentTab, shortcode, selectAll, drstk, options }) {
    // Get the name of the current tab
    let tabName = tabs[currentTab];

    // Check if shortcode items are undefined. If so, set count to 0, otherwise, get the length of items.
    let count = shortcode.items == undefined ? 0 : shortcode.items.length;

    // If no items are selected, select all is unchecked, or there are no items in the list, show a warning notice and exit.
    if (count == 0 && !selectAll) {
        jQuery('.selected-items').html("<div class='notice notice-warning'><p>You haven't selected any items yet.</p></div>");
        jQuery('#selected #sortable-' + tabName + '-list')
            .children('li')
            .remove();
        return;
    }

    // If select all is checked but no items are selected yet, show a loading notice and attempt a reload.
    if (count == 0 && selectAll) {
        jQuery('.selected-items').html("<div class='notice notice-warning'><p>Selected items are loading...</p></div>");

        // If there are no items, wait for 2 seconds before attempting to reload. This is a workaround.
        if (jQuery('#selected #sortable-' + tabName + '-list').children().length < 1) {
            var interval;
            interval = setInterval(function () {
                if (jQuery('#selected #sortable-' + tabName + '-list').children().length == 0) {
                    jQuery('#drs #drs-select-all-item').trigger('change'); // Attempt to select all items.
                    jQuery(".nav-tab[href='#selected']").trigger('click'); // Navigate to the shortcode.
                } else {
                    clearInterval(interval);
                }
            }, 2000);
        }

        return;
    }

    // Clear any existing items.
    jQuery('.selected-items').html('');

    // If tab is one of 'tile', 'slider', or 'media', show a notice that allows users to drag and drop to reorder.
    if (tabName == 'tile' || tabName == 'slider' || tabName == 'media') {
        jQuery('.selected-items').append("<div class='notice notice-info'><p>Drag and drop items to reorder.</p></div>");
    }

    // Remove all existing items from the list.
    jQuery('#selected #sortable-' + tabName + '-list')
        .children('li')
        .remove();

    // Placeholder for new items after fetching them.
    var newItems = [];

    // Loop through each item and fetch details if needed.
    jQuery.each(shortcode.items.models, function (i, item) {
        // If item doesn't have a title, it's still loading.
        if (!item.get('title')) {
            jQuery('.selected-items').html('Loading...');

            let repo = item.get('repo');

            // Handle different repositories separately.
            if (repo == 'drs') {
                jQuery.ajax({
                    url: item_admin_obj.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'get_item_solr_admin',
                        _ajax_nonce: item_admin_obj.item_admin_nonce,
                        pid: item.get('pid'),
                    },
                    complete: function (data) {
                        var data = jQuery.parseJSON(data.responseJSON);
                        data = data['_source'];
                        item.set('title', data.full_title_ssi);
                        if (!item.get('thumbnail')) {
                            item.set('thumbnail', 'https://repository.library.northeastern.edu' + data.fields_thumbnail_list_tesim[0]);
                        }
                        if (!item.get('key_date') || item.get('key_date') == '' || item.get('key_date') == undefined) {
                            item.set('key_date', data.key_date_ssi);
                        }
                        if (!item.get('coords') || item.get('coords') == '' || item.get('coords') == undefined) {
                            if (data.subject_geographic_tesim) {
                                item.set('coords', data.subject_geographic_tesim[0]);
                            }
                            if (data.subject_cartographics_coordinates_tesim) {
                                item.set('coords', data.subject_cartographics_coordinates_tesim);
                            }
                        }
                        newItems.push(item.get('pid'));
                    },
                });
            } else if (repo == 'dpla') {
                jQuery.post(
                    dpla_ajax_obj.ajax_url,
                    {
                        _ajax_nonce: dpla_ajax_obj.dpla_ajax_nonce,
                        action: 'get_dpla_code',
                        params: {
                            q: item.get('pid'),
                        },
                    },
                    function (data) {
                        var data = jQuery.parseJSON(data);
                        item.set('title', data.docs[0].sourceResource.title);
                        if (data.docs[0].object) {
                            item.set('thumbnail', data.docs[0].object);
                        }
                        if ((!item.get('key_date') || item.get('key_date') == '' || item.get('key_date') == undefined) && currentTab == 6) {
                            date = getDateFromSourceResource(data.docs[0].sourceResource);
                            item.set('key_date', date);
                        }
                        if ((!item.get('coords') || item.get('coords') == '' || item.get('coords') == undefined) && currentTab == 5) {
                            coords = data.docs[0].sourceResource.spatial[0].name;
                            if (data.docs[0].sourceResource.spatial[0].coordinates != '' && data.docs[0].sourceResource.spatial[0].coordinates != undefined) {
                                coords = data.docs[0].sourceResource.spatial[0].coordinates;
                            }
                            item.set('coords', coords);
                        }
                        newItems.push(item.get('pid'));
                    }
                );
            } else if (repo == 'local') {
                jQuery.ajax({
                    url: item_admin_obj.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'get_post_meta',
                        _ajax_nonce: item_admin_obj.item_admin_nonce,
                        pid: item.get('pid'),
                    },
                    success: function (data) {
                        item.set('title', data.post_title);
                        if (!data.post_mime_type.includes('audio') && !data.post_mime_type.includes('video')) {
                            item.set('thumbnail', data.guid);
                        }
                        if ((!item.get('key_date') || item.get('key_date') == '' || item.get('key_date') == undefined) && currentTab == 6) {
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
                                    item.set('key_date', data._timeline_date[0]);
                                },
                            });
                        }
                        if ((!item.get('coords') || item.get('coords') == '' || item.get('coords') == undefined) && currentTab == 5) {
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
                                    item.set('coords', data._map_coords[0]);
                                },
                            });
                        }
                        newItems.push(item.get('pid'));
                    },
                });
            }
        } else {
            // If the item already has a title, simply append it.
            appendSingleItem(item, { currentTab, tabs, shortcode, drstk, options });
        }

        // After fetching all items, check regularly (every 1 second) if they're all loaded, then display them.
        if (i === shortcode.items.models.length - 1) {
            let interval = setInterval(function () {
                if (newItems.length === shortcode.items.models.length) {
                    clearInterval(interval);
                    jQuery('.selected-items').html('');
                    jQuery('#selected #sortable-' + tabName + '-list')
                        .children('li')
                        .remove();

                    // Append each loaded item to the list.
                    _.each(shortcode.items.models, function (item) {
                        appendSingleItem(item, { currentTab, tabs, shortcode, drstk, options });
                    });
                }
            }, 1000);
        }
    });
}
