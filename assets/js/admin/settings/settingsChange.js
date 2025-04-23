function settingsChangeController(e, { shortcode }) {
    e.preventDefault();
    field_name = jQuery(e.currentTarget).attr('name');

    if (jQuery(e.currentTarget).attr('type') == 'checkbox') {
        let name = jQuery(e.currentTarget).parents('tr').attr('class');
        let setting = shortcode.get('settings').where({ name })[0];
        let vals = [];
        jQuery(e.currentTarget)
            .parents('td')
            .find("input[type='checkbox']")
            .each(function () {
                if (jQuery(this).is(':checked')) {
                    vals.push(jQuery(this).attr('name'));
                }
            });
        setting.set('value', vals);
        shortcode.set('settings', shortcode.get('settings'));
        return shortcode;
    }
    if (jQuery(e.currentTarget).attr('type') == 'color') {
        let color = jQuery(e.currentTarget).val();
        let name = jQuery(e.currentTarget).parents('td').prev('td').find('input').attr('name');
        let colorsetting = shortcode.get('colorsettings').where({ name })[0];
        colorsetting.set('colorHex', color);
        shortcode.set('colorsettings', shortcode.get('colorsettings'));
        return shortcode;
    }

    if (field_name.indexOf('label-text-') != -1) {
        let name = jQuery(e.currentTarget).attr('name');
        let colorsetting = shortcode.get('colorsettings').where({ name })[0];
        let val = jQuery(e.currentTarget).val();
        colorsetting.set('value', val);
        colorsetting.set('colorname', val);
        shortcode.set('colorsettings', shortcode.get('colorsettings'));
        return shortcode;
    }

    let name = jQuery(e.currentTarget).attr('name');
    let setting = shortcode.get('settings').where({ name })[0];
    let val = jQuery(e.currentTarget).val();
    if (field_name == 'end-date' || field_name == 'start-date') {
        if (val == '') {
            val = null;
        }
    }
    setting.set('value', [val]);
    shortcode.set('settings', shortcode.get('settings'));
    return shortcode;
}
