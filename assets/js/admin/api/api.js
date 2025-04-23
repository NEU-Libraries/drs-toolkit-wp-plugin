const ajaxConfig = {
    DRS: {
        url: drs_ajax_obj.ajax_url
        nonce: drs_ajax_obj.
    }
};

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