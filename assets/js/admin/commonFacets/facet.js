function facetController(e, { getData, searchParams }) {
    e.preventDefault();
    link = jQuery(e.currentTarget);
    if (searchParams.facets[link.data('facet-name')] == undefined) {
        searchParams.facets[link.data('facet-name')] = link.data('facet-val');
    } else if (searchParams.facets[link.data('facet-name')].length > 0) {
        if (typeof orig_value == 'string') {
            searchParams.facets[link.data('facet-name')] = [orig_value, link.data('facet-val')];
        } else {
            searchParams.facets[link.data('facet-name')].push(link.data('facet-val'));
        }
    }

    getData();
    return searchParams;
}

function facetToggleController(e, { facetName }) {
    e.preventDefault();
    jQuery(`.${facetName}-facets`).toggleClass('hidden');
    jQuery(`#${facetName} ol`).toggleClass('fullwidth');
    if (!jQuery(`.${facetName}-facets`).hasClass('hidden')) {
        jQuery(`.${facetName}-facets-button`).addClass('hidden');
    } else {
        jQuery(`.${facetName}-facets-button`).removeClass('hidden');
    }
}

function facetRemoveController(e, { getData, searchParams }) {
    e.preventDefault();
    link = jQuery(e.currentTarget);
    values = searchParams.facets[link.data('facet-name')];
    newVals = [];
    if (typeof values != 'string') {
        _.each(values, function (val) {
            if (val != link.data('facet-val')) {
                newVals.push(val);
            }
        });
    }
    if (newVals.length == 0) {
        delete searchParams.facets[link.data('facet-name')];
    } else {
        searchParams.facets[link.data('facet-name')] = newVals;
    }
    getData();
    return searchParams;
}
