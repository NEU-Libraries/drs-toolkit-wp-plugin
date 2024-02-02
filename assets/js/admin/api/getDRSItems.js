function fetchDRSItems(searchParams, callback) {
    return jQuery
        .post(drs_ajax_obj.ajax_url, {
            _ajax_nonce: drs_ajax_obj.drs_ajax_nonce,
            action: 'get_drs_code',
            params: searchParams,
        })
        .done(function (response) {
            var data = jQuery.parseJSON(response);
            callback(data);
        });
}

function updateDRSPagination(data) {
    if (data.pagination.table.num_pages > 1) {
        this.result_count = data.pagination.table.total_count;
        var pagination = '';
        if (data.pagination.table.current_page > 1) {
            pagination += "<a href='#' class='prev-page'>&lt;&lt;</a>";
        } else {
            pagination += "<a href='#' class='prev-page disabled'>&lt;&lt;</a>";
        }
        for (var i = 1; i <= data.pagination.table.num_pages; i++) {
            if (data.pagination.table.current_page == i) {
                var pagination_class = 'current-page active';
            } else {
                var pagination_class = '';
            }
            pagination += "<a href='#' class='" + pagination_class + "'>" + i + '</a>';
        }
        if (data.pagination.table.current_page == data.pagination.table.num_pages) {
            pagination += "<a href='#' class='next-page' data-val='" + data.pagination.table.num_pages + "'>&gt;&gt;</a>";
        } else {
            pagination += "<a href='#' class='next-page disabled' data-val='" + data.pagination.table.num_pages + "'>&gt;&gt;</a>";
        }
        jQuery('.drs-pagination').html("<span class='tablenav'><span class='tablenav-pages'>" + pagination + '</span></span>');
    } else {
        jQuery('.drs-pagination').html('');
    }
}

function getDRSItemsController({ currentTab, searchParams, tabs, geoCount, timeCount, shortcodeItems, selectAll, drstk }) {
    const tabName = tabs[currentTab];

    // deleting previous search param filters
    delete searchParams.avfilter;
    delete searchParams.spatialfilter;
    delete searchParams.timefilter;

    if (tabName === 'media') {
        searchParams.avfilter = true;
    }
    if (tabName === 'map') {
        searchParams.spatialfilter = true;
    }
    if (tabName === 'timeline') {
        searchParams.timefilter = true;
    }

    // resetting time/geo counts when we're on the first page
    if (searchParams.page === 1) {
        geoCount = 0;
        timeCount = 0;
    }

    fetchDRSItems(searchParams, function (data) {
        jQuery('#drs #sortable-' + tabName + '-list')
            .children('li')
            .remove();
        jQuery('.drs-pagination').html('');

        // if there are no results
        if (jQuery.type(data) === 'string' || data.response == undefined || data.response.response.numFound === 0) {
            jQuery('.drs-items').html("<div class='notice notice-warning'><p>No results were retrieved for your query. Please try a different query.</p></div>");
            return { geoCount, timeCount };
        }

        data.response.response.docs.forEach((item, id) => {
            // terinary operator: to check if this is the last item
            let last = id === 19;
            if (item.active_fedora_model_ssi == 'CoreFile') {
                let newItem = new drstk.Item();
                let thumb = 'https://repository.library.northeastern.edu' + item.thumbnail_list_tesim[0];

                newItem.set('pid', item.id).set('thumbnail', thumb).set('repo', 'drs').set('title', item.full_title_ssi);
                if (item.key_date_ssi) {
                    newItem.set('key_date', item.key_date_ssi);
                }
                if (item.subject_geographic_tesim) {
                    newItem.set('coords', item.subject_geographic_tesim[0]);
                }
                if (item.subject_cartographics_coordinates_tesim) {
                    newItem.set('coords', item.subject_cartographics_coordinates_tesim);
                }
                let view = new drstk.ItemView({
                    model: newItem,
                });
                jQuery('#drs #sortable-' + tabName + '-list').append(view.el);
                if (tabName === 'timeline') {
                    jQuery('#drs #sortable-' + tabName + '-list')
                        .find('li:last-of-type')
                        .append("<p>Date: <span class='key_date'>" + item.key_date_ssi + '</span></p>');
                }
                if (tabName === 'map') {
                    jQuery('#drs #sortable-' + tabName + '-list')
                        .find('li:last-of-type')
                        .append("<p>Map Info: <span class='coords'>" + newItem.get('coords') + '</span></p>');
                }
                if (shortcodeItems != undefined && shortcodeItems.where({ pid: item.id }).length > 0) {
                    jQuery('#drs #sortable-' + tabName + '-list')
                        .find('li:last-of-type input')
                        .prop('checked', true);
                    if (selectAll == true) {
                        jQuery('#drs #sortable-' + tabName + '-list')
                            .find('li:last-of-type input')
                            .prop('disabled', true);
                    }
                } else if (selectAll == true) {
                    //if its a selectAll then we automatically do that selectAllItem
                    jQuery('#drs #sortable-' + tabName + '-list')
                        .find('li:last-of-type input')
                        .prop('checked', true);
                    jQuery('#drs #sortable-' + tabName + '-list')
                        .find('li:last-of-type input')
                        .prop('disabled', true);
                    jQuery('#drs #sortable-' + tabName + '-list')
                        .find('li:last-of-type .tile')
                        .trigger('change');
                }
                jQuery('.drs-items').html('');
            }
        });
        self.updateDRSPagination(data);
        if (searchParams.facets != {}) {
            jQuery('.drs-type, .drs-subject').html('');
            const FACETS = {
                creator_sim: 'creator',
                subject_sim: 'subject',
                type_sim: 'type',
                creation_year_sim: 'date',
            };
            Object.entries(data.response.facet_counts.facet_fields).forEach(([facetName, facetVals]) => {
                let currentFacet = FACETS[facetName];

                if (currentFacet && facetVals) {
                    const sorted = Object.entries(facetVals)
                        .map(([facet_val, facet_count]) => ({ [facet_val]: facet_count }))
                        .sort((a, b) => b[Object.keys(b)[0]] - a[Object.keys(a)[0]]);

                    let facetHtml = `<table class='facets-filter'><tbody>`;
                    sorted.slice(0, 5).forEach((sortedItem) => {
                        const key = Object.keys(sortedItem)[0];
                        facetHtml += `<tr>
                                    <td><a href="" data-facet-val="${key}" data-facet-name="${currentFacet}" class="drs-facet-add">${key.replace(/--/g, ' -- ')}</a></td>
                                    <td><span class="facet-value">${sortedItem[key]}</span></td>
                                </tr>`;
                    });
                    facetHtml += '</tbody></table>';
                    if (sorted.length > 5) {
                        facetHtml += `<a href="" class="drs-expand-facet" data-facet-name="${currentFacet}">View More</a>
                               <div class="drs-expanded-facet-${currentFacet} hidden">
                                  <table class='facets-filter'>`;
                        sorted.slice(5).forEach((sortedItem) => {
                            const key = Object.keys(sortedItem)[0];
                            facetHtml += `<tr>
                                    <td><a href="" data-facet-val="${key}" data-facet-name="${currentFacet}" class="drs-facet-add">${key.replace(/--/g, ' -- ')}</a></td>
                                    <td><span class="facet-value">${sortedItem[key]}</span></td>
                                </tr>`;
                        });
                        facetHtml += '</table>';
                    }
                    const container = jQuery(`.drs-${currentFacet}`);
                    container.html(`<b>${currentFacet.charAt(0).toUpperCase()}${currentFacet.slice(1)}</b>`);
                    container.append(facetHtml, '</div>');
                }
            });
            facetButtons = '';
            Object.entries(searchParams.facets).forEach(([facetName, facetVal]) => {
                if (typeof facetVal === 'string' || typeof facetVal === 'number') {
                    facetButtons += `<a href="" data-facet-name="${facetName}" data-facet-val="${facetVal}" class="button drs-facet-remove">
                                ${facetName.charAt(0).toUpperCase() + facetName.slice(1)} : ${facetVal} 
                                <span class='dashicons dashicons-trash'></span>
                            </a>`;
                } else {
                    facetVal.forEach((facetValue) => {
                        facetButtons += `<a href="" data-facet-name="${facetName}" data-facet-val="${facetValue}" class="button drs-facet-remove">
                                    ${facetName.charAt(0).toUpperCase() + facetName.slice(1)} : ${facetValue} 
                                    <span class='dashicons dashicons-trash'></span>
                                </a>`;
                    });
                }
            });
            jQuery('.drs-chosen').html(facetButtons);
        }
    });

    return { geoCount, timeCount };
}
