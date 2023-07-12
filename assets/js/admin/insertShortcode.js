const validMap = (items) => {
    const repos = ['drs', 'local', 'dpla'];
    let noMap = [];

    repos.forEach((repo) => {
        [...items.where({ repo })].filter((item) => !item.get('coords') || item.get('coords') === '').forEach((item) => noMap.push(item.get('title')));
    });

    return noMap.length ? noMap : true;
};

const validTime = () => {
    const repos = ['drs', 'local', 'dpla'];
    let keyDateList = [];
    let no_year = [];

    repos.forEach((repo) => {
        [...this.shortcode.items.where({ repo })].forEach((item) => {
            let key_date = item.get('key_date');
            key_date !== undefined && key_date !== '' && key_date !== []
                ? keyDateList.push({ year: key_date.split('/')[0], name: item.get('title') })
                : repo !== 'drs' && no_year.push(item.get('title'));
        });
    });

    // TODO: this is a mess

    const {
        shortcode: { get },
    } = this;
    const startDate = get('settings').where({ name: 'start-date' })[0].attributes.value[0];
    const endDate = get('settings').where({ name: 'end-date' })[0].attributes.value[0];

    const return_arr = keyDateList
        .filter(({ year }) => (Array.isArray(year) ? year[0] < startDate && year[1] > endDate : year < startDate || year > endDate))
        .map(({ name }) => name);

    return return_arr.length || no_year.length ? [...return_arr, ...no_year] : true;
};

function insertShortcodeController(e, { shortcode, validTime, closeModal, collectionId, currentTab, tabs }) {
    const { items } = shortcode;

    if (items == undefined) {
        alert('Please select items before inserting a shortcode');
        return;
    }

    // get the start and end date
    let startDate = shortcode.get('settings').find((element) => element.name === 'start-date');
    if (startDate !== undefined) {
        startDate = startDate.attributes.value[0];
    }

    let endDate = shortcode.get('settings').find((element) => element.name === 'end-date');
    if (endDate !== undefined) {
        endDate = endDate.attributes.value[0];
    }
    // FIX: OPTIMIZE: this conditional
    if (
        !(
            (currentTab == 6 && ((startDate != '' && startDate != undefined) || (endDate != '' && endDate != undefined)) && validTime() == true) ||
            (currentTab == 6 && startDate == undefined && endDate == undefined && validTime() == true) ||
            (currentTab == 5 && validMap(items) == true) ||
            currentTab == 1 ||
            (currentTab != 6 && currentTab != 1 && currentTab != 5)
        )
    ) {
        if (currentTab == 1 && shortcode.items.length > 1) {
            alert('There are more than 1 items selected for a single item shortcode.');
            return;
        }
        if (currentTab == 6) {
            titles = validTime();
            titles = titles.join('\n');
            alert('The following item(s) are outside the specified date range or do not have date values: \n' + titles);
            return;
        }
        if (currentTab == 5) {
            titles = validMap(items);
            titles = titles.join('\n');
            alert('The following item(s) may not have coordinate or location values: \n' + titles);
            return;
        }
    }
    // currentTab is actually not a tab -- it's the button for the type of shortcode

    let shortcodeStr = `<p>[drstk_${tabs[currentTab]}`;

    // If check box is checked then add collection_Id attribute to the shortcodeStr
    if (jQuery('#drs-select-all-item').prop('checked')) {
        shortcodeStr += ` collection_id="${collectionId}"`;
    } else {
        let ids = [];
        items.models.forEach((item) => {
            let pid;
            if (item.attributes.repo === 'dpla') {
                pid = 'dpla:' + item.attributes.pid;
            } else if (item.attributes.repo === 'drs') {
                pid = item.attributes.pid;
            } else if (item.attributes.repo === 'local') {
                pid = 'wp:' + item.attributes.pid;
            }
            ids.push(pid);
        });
        ids.join(',');
        shortcodeStr += ` id="${ids}"`;
    }

    const addToShortcode = (items, color) => {
        const arr = [];
        items.forEach((i) => {
            let pid;
            if (i.attributes.repo === 'dpla') {
                pid = 'dpla:' + i.attributes.pid;
            } else if (i.attributes.repo === 'drs') {
                pid = i.attributes.pid;
            } else if (i.attributes.repo === 'local') {
                pid = 'wp:' + i.attributes.pid;
            }
            arr.push(pid);
        });
        if (arr.length > 0) {
            const colorDesc = color.replace(' ', '_');
            shortcodeStr += ` ${colorDesc}_color_desc_id="${arr.join(',')}"`;
        }
    };

    if (currentTab === 5 || currentTab === 6) {
        shortcode.get('colorsettings').models.forEach((color) => {
            const items = shortcode.items.where({
                color: color.attributes.colorname,
            });
            addToShortcode(items, color.attributes.colorname);
        });
    }

    shortcode.get('settings').models.forEach((setting) => {
        let vals = setting.get('value');
        if (Array.isArray(vals) && vals.length > 0) {
            vals = vals.join(',');
            shortcodeStr += ` ${setting.get('name')}="${vals}"`;
        } else if (vals !== '') {
            shortcodeStr += ` ${setting.get('name')}="${vals}"`;
        }
    });

    const addToShortcodeColor = (color) => {
        const colorDesc = color.attributes.colorname.replace(' ', '_');
        const hexval = color.attributes.colorHex.substring(1, color.attributes.colorHex.length);
        shortcodeStr += `${colorDesc}_color_hex="${hexval}" `;
    };

    if (currentTab === 5 || currentTab === 6) {
        shortcode.get('colorsettings').models.forEach(addToShortcodeColor);
    }

    shortcodeStr += ']</p>';

    closeModal(e);
    window.wp.media.editor.insert(shortcodeStr);
}
