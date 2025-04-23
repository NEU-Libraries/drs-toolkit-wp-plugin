function selectItemController(e, { shortcode, searchParams }) {
    const item = jQuery(e.currentTarget);
    let pid = item.val();
    const title = item.siblings('.title').text();
    const thumbnail = item.siblings('img').attr('src');
    const parent = item.parents('.pane').attr('id');
    const key_date = item.parents('li').find('.key_date').text();
    const coords = item.parents('li').find('.coords').text();
    const repo = parent == 'drs' ? 'drs' : parent == 'dpla' ? 'dpla' : 'local';

    // if the item is unchecked, remove it from the shortcode items
    if (!item.is(':checked')) {
        var remove = shortcode.items.where({
            pid: pid,
        });
        shortcode.items.remove(remove);

        return { shortcode, searchParams };
    }

    const newItem = new drstk.Item({
        title: title,
        pid: pid,
        thumbnail: thumbnail,
        repo: repo,
        key_date: key_date,
        coords: coords,
    });
    console.log('Calling select item');

    // const existingItem = shortcode.items === undefined ? [] : shortcode.items.filter((item) => item.get('pid') === pid);

    if (shortcode.items == undefined) {
        shortcode.items = new drstk.Items(newItem);
    } else if (
        shortcode.items.where({
            pid: pid,
        }).length == 0
    ) {
        shortcode.items.add(newItem);
    }

    if (shortcode.get('type') == 'single') {
        //single items can only have one items so we'll clear the rest out
        item.parents('ol')
            .find('input:checked')
            .not(item)
            .each(function () {
                jQuery(this).prop('checked', false);
                pid = jQuery(this).val();
                var remove = shortcode.items.where({
                    pid: pid,
                });
                shortcode.items.remove(remove);
            });
    }

    if (shortcode.get('type') == 'single' && parent == 'drs') {
        const settings = shortcode.get('settings');
        const choicesArr = ['Title', 'Abstract/Description', 'Creator', 'Date Created'];
        const choices = choicesArr.reduce((obj, choice) => {
            obj[choice] = choice;
            return obj;
        }, {});

        // TODO: this is a mess
        const oldmeta = settings.where({
            name: 'metadata',
        });
        settings.remove(oldmeta);
        settings.add({
            name: 'metadata',
            label: 'Metadata to Display',
            tag: 'checkbox',
            value: [],
            choices: choices,
        });
        shortcode.set('settings', settings);
    }

    if (shortcode.get('type') == 'single' && parent == 'dpla') {
        const oldSearch = searchParams;
        const localParams = searchParams;

        localParams.pid = pid;
        jQuery.post(
            dpla_ajax_obj.ajax_url,
            {
                _ajax_nonce: dpla_ajax_obj.dpla_ajax_nonce,
                action: 'get_dpla_code',
                params: local_params,
            },
            function (data) {
                data = jQuery.parseJSON(data);
                data = data.docs[0];
                const choices = {};
                const settings = shortcode.get('settings');

                const properties = {
                    title: 'Title',
                    description: 'Abstract/Description',
                    contributor: 'Creator',
                };

                Object.keys(properties).forEach((key) => {
                    if (data.sourceResource[key]) {
                        choices[properties[key]] = properties[key];
                    }
                });

                if (data.sourceResource.date?.displayDate) {
                    choices['Date Created'] = 'Date Created';
                }

                const oldmeta = settings.where({
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
                    shortcode.set('settings', settings);
                }
            }
        );
        searchParams = oldSearch;
    }

    return { shortcode, searchParams };
}
