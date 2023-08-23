// Helper function used in other functions
function getSettings({ shortcode, drstk }) {
    jQuery('#settings').html('<table />');

    shortcode.get('settings').models.forEach((setting) => {
        const settingView = new drstk.SettingView({
            model: setting,
        });
        jQuery('#settings table').append(settingView.el);
        jQuery('#settings table tr:last-of-type').addClass(setting.get('name'));
    });

    const type = shortcode.get('type');

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
        shortcode.get('colorsettings').models.forEach((colorsetting) => {
            const colorsettingView = new drstk.ColorSettingView({
                model: colorsetting,
            });
            jQuery('#settings .color-table').append(colorsettingView.el);
            jQuery(jQuery('#settings .color-table tr:last-of-type').addClass(colorsetting.get('name')));
        });
    }
}
