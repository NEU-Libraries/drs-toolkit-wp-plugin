function setDefaultSettingsController({ shortcode, options }) {
    let type = shortcode.get('type');
    let settings = shortcode.get('settings');
    let optionSettings = {};
    if (options && options.settings) {
        optionSettings = options.settings;
    } else if (options) {
        optionSettings = options;
    }

    if (type == 'tile') {
        var tile_type = optionSettings['tile-type'] ? optionSettings['tile-type'] : optionSettings['type'];
        settings.add({
            name: 'tile-type', //previously called type
            value: tile_type ? [tile_type] : ['pinterest-hover'],
            choices: {
                'pinterest-below': 'Pinterest style with caption below',
                'pinterest-hover': 'Pinterest style with caption on hover',
                'even-row': 'Even rows with caption on hover',
                square: 'Even Squares with caption on hover',
            },
            label: 'Layout Type',
            tag: 'select',
        });
        settings.add({
            name: 'text-align',
            value: optionSettings['text-align'] ? [optionSettings['text-align']] : ['left'],
            choices: {
                center: 'Center',
                left: 'Left',
                right: 'Right',
            },
            label: 'Caption Alignment',
            tag: 'select',
        });
        settings.add({
            name: 'cell-height',
            value: optionSettings['cell-height'] ? [optionSettings['cell-height']] : [200],
            label: 'Cell Height (auto for Pinterest style)',
            tag: 'number',
        });
        settings.add({
            name: 'cell-width',
            value: optionSettings['cell-width'] ? [optionSettings['cell-width']] : [200],
            label: 'Cell Width',
            tag: 'number',
            helper: 'Make the height and width the same for squares',
        });
        settings.add({
            name: 'image-size',
            value: optionSettings['image-size'] ? [optionSettings['image-size']] : [4],
            label: 'Image Size',
            tag: 'select',
            choices: {
                1: 'Largest side is 85px',
                2: 'Largest side is 170px',
                3: 'Largest side is 340px',
                4: 'Largest side is 500px',
                5: 'Largest side is 1000px',
            },
        });
        settings.add({
            name: 'metadata',
            label: 'Metadata for Captions',
            tag: 'checkbox',
            value: optionSettings['metadata'] ? optionSettings['metadata'] : ['full_title_ssi', 'creator_tesim'],
            choices: {
                full_title_ssi: 'Title',
                creator_tesim: 'Creator,creator',
                date_ssi: 'Date Created',
                abstract_tesim: 'Abstract/Description',
            },
        });
        shortcode.set('settings', settings);
    } else if (type == 'single') {
        settings.add({
            name: 'image-size',
            value: optionSettings['image-size'] ? [optionSettings['image-size']] : [4],
            label: 'Image Size',
            tag: 'select',
            choices: {
                1: 'Largest side is 85px',
                2: 'Largest side is 170px',
                3: 'Largest side is 340px',
                4: 'Largest side is 500px',
                5: 'Largest side is 1000px',
            },
        });
        settings.add({
            name: 'display-video',
            value: optionSettings['display-video'] ? [optionSettings['display-video']] : ['true'],
            label: 'Display Audio/Video',
            helper: 'Note: DPLA items cannot be used as embedded media',
            tag: 'checkbox',
            choices: {
                0: 'true',
            },
        });
        settings.add({
            name: 'display-issuu',
            value: optionSettings['display-issuu'] ? [optionSettings['display-issuu']] : ['true'],
            label: 'Display Embedded Page Turner',
            helper: 'Note: Only for DRS items. Requires special metadata.',
            tag: 'checkbox',
            choices: {
                0: 'true',
            },
        });
        settings.add({
            name: 'align',
            value: optionSettings['align'] ? [optionSettings['align']] : ['center'],
            label: 'Image Alignment',
            tag: 'select',
            choices: {
                center: 'Center',
                left: 'Left',
                right: 'Right',
            },
        });
        settings.add({
            name: 'float',
            value: optionSettings['float'] ? [optionSettings['float']] : ['none'],
            label: 'Image Flow',
            helper: 'Allow the text to float around the image by floating it to one side.',
            tag: 'select',
            choices: {
                none: 'None',
                left: 'Left',
                right: 'Right',
            },
        });
        settings.add({
            name: 'caption-align',
            value: optionSettings['caption-align'] ? [optionSettings['caption-align']] : ['left'],
            choices: {
                center: 'Center',
                left: 'Left',
                right: 'Right',
            },
            label: 'Caption Alignment',
            tag: 'select',
        });
        settings.add({
            name: 'caption-position',
            value: optionSettings['caption-position'] ? [optionSettings['caption-position']] : ['below'],
            label: 'Caption Position',
            choices: {
                below: 'Below',
                hover: 'Over Image on Hover',
            },
            tag: 'select',
        });
        settings.add({
            name: 'zoom',
            value: optionSettings['zoom'] ? [optionSettings['zoom']] : ['on'],
            label: 'Enable Zoom',
            choices: {
                0: 'on',
            },
            tag: 'checkbox',
        });
        settings.add({
            name: 'zoom-position',
            value: optionSettings['zoom-position'] ? [optionSettings['zoom-position']] : [1],
            label: 'Zoom Position',
            helper: 'Recommended and Default position:Top Right',
            choices: {
                1: 'Top Right',
                2: 'Middle Right',
                3: 'Bottom Right',
                4: 'Bottom Corner Right',
                5: 'Under Right',
                6: 'Under Middle',
                7: 'Under Left',
                8: 'Bottom Corner Left',
                9: 'Bottom Left',
                10: 'Middle Left',
                11: 'Top Left',
                12: 'Top Corner Left',
                13: 'Above Left',
                14: 'Above Middle',
                15: 'Above Right',
                16: 'Top Right Corner',
                inner: 'Over image itself',
            },
            tag: 'select',
        });
        if (optionSettings['metadata']) {
            var choices = {};
            _.each(optionSettings['metadata'], function (val) {
                choices[val] = val;
            });
            settings.add({
                name: 'metadata',
                label: 'Metadata',
                tag: 'checkbox',
                value: optionSettings['metadata'] ? optionSettings['metadata'] : [],
                choices: choices,
            });
        }
        shortcode.set('settings', settings);
    } else if (type == 'slider') {
        settings.add({
            name: 'image-size',
            value: optionSettings['image-size'] ? [optionSettings['image-size']] : [4],
            label: 'Image Size',
            tag: 'select',
            choices: {
                1: 'Largest side is 85px',
                2: 'Largest side is 170px',
                3: 'Largest side is 340px',
                4: 'Largest side is 500px',
                5: 'Largest side is 1000px',
            },
        });
        settings.add({
            name: 'auto',
            value: optionSettings['auto'] ? [optionSettings['auto']] : ['on'],
            label: 'Auto rotate',
            choices: {
                0: 'on',
            },
            tag: 'checkbox',
        });
        settings.add({
            name: 'nav',
            value: optionSettings['nav'] ? [optionSettings['nav']] : ['on'],
            label: 'Next/Prev Buttons',
            choices: {
                0: 'on',
            },
            tag: 'checkbox',
        });
        settings.add({
            name: 'pager',
            value: optionSettings['pager'] ? [optionSettings['pager']] : ['on'],
            label: 'Dot pager',
            choices: {
                0: 'on',
            },
            tag: 'checkbox',
        });
        settings.add({
            name: 'speed',
            value: optionSettings['speed'] ? [optionSettings['speed']] : [],
            label: 'Rotation Speed',
            tag: 'number',
            helper: 'Speed is in milliseconds. 5000 milliseconds = 5 seconds',
        });
        settings.add({
            name: 'max-height',
            value: optionSettings['max-height'] ? [optionSettings['max-height']] : [],
            label: 'Max Height',
            tag: 'number',
        });
        settings.add({
            name: 'max-width',
            value: optionSettings['max-width'] ? [optionSettings['max-width']] : [],
            label: 'Max Width',
            tag: 'number',
        });
        settings.add({
            name: 'caption',
            value: optionSettings['caption'] ? [optionSettings['caption']] : ['on'],
            label: 'Enable captions',
            choices: {
                0: 'on',
            },
            tag: 'checkbox',
        });
        settings.add({
            name: 'caption-align',
            value: optionSettings['caption-align'] ? [optionSettings['caption-align']] : ['center'],
            choices: {
                center: 'Center',
                left: 'Left',
                right: 'Right',
            },
            label: 'Caption Alignment',
            tag: 'select',
        });
        settings.add({
            name: 'caption-position',
            value: optionSettings['caption-position'] ? [optionSettings['caption-position']] : ['relative'],
            label: 'Caption Position',
            choices: {
                absolute: 'Over Image',
                relative: 'Below Image',
            },
            tag: 'select',
        });
        settings.add({
            name: 'caption-width',
            value: optionSettings['caption-width'] ? [optionSettings['caption-width']] : ['below'],
            label: 'Caption Width',
            choices: {
                '100%': 'Width of gallery',
                image: 'Width of image',
            },
            tag: 'select',
        });
        settings.add({
            name: 'transition',
            value: optionSettings['transition'] ? [optionSettings['transition']] : ['slide'],
            label: 'Transition Type',
            choices: {
                slide: 'Slide',
                fade: 'Fade',
            },
            tag: 'select',
        });
        settings.add({
            name: 'metadata',
            label: 'Metadata for Captions',
            tag: 'checkbox',
            value: optionSettings['metadata'] ? optionSettings['metadata'] : ['full_title_ssi', 'creator_tesim'],
            choices: {
                full_title_ssi: 'Title',
                creator_tesim: 'Creator,Contributor',
                date_ssi: 'Date Created',
                abstract_tesim: 'Abstract/Description',
            },
        });

        shortcode.set('settings', settings);
    } else if (type == 'timeline') {
        settings.add({
            name: 'start-date',
            value: optionSettings['start-date'] ? [optionSettings['start-date']] : [],
            label: 'Start Date Boundary',
            tag: 'number',
            helper: 'year eg:1960',
        });
        settings.add({
            name: 'end-date',
            value: optionSettings['end-date'] ? [optionSettings['end-date']] : [],
            label: 'End Date Boundary',
            tag: 'number',
            helper: 'year eg:1990',
        });
        settings.add({
            name: 'metadata',
            label: 'Metadata',
            tag: 'checkbox',
            value: optionSettings['metadata'] ? optionSettings['metadata'] : ['creator_tesim'],
            choices: {
                creator_tesim: 'Creator,Contributor',
                abstract_tesim: 'Abstract/Description',
            },
        });
        settings.add({
            name: 'increments',
            label: 'Scale Increments',
            tag: 'select',
            value: optionSettings['increments'] ? [optionSettings['increments']] : [5],
            choices: {
                0.5: 'Very Low',
                2: 'Low',
                5: 'Medium',
                8: 'High',
                13: 'Very High',
            },
            helper: 'Specifies the granularity to represent items on the timeline',
        });
        shortcode.set('settings', settings);
    } else if (type == 'media') {
        settings.add({
            name: 'height',
            value: optionSettings['height'] ? [optionSettings['height']] : ['270'],
            label: 'Height',
            helper: '(Enter in pixels or %, Default is 270)',
            tag: 'text',
        });
        settings.add({
            name: 'width',
            value: optionSettings['width'] ? [optionSettings['width']] : ['100%'],
            label: 'Width',
            tag: 'text',
            helper: '(Enter in pixels or %, Default is 100%)',
        });
        //we historically have not provided interface for aspectratio, skin, and listbarwidth, TODO - add these
        shortcode.set('settings', settings);
    } else if (type == 'map') {
        settings.add({
            name: 'story',
            value: optionSettings['story'] ? optionSettings['story'] : ['yes'],
            label: 'Story',
            tag: 'checkbox',
            choices: {
                0: 'yes',
            },
        });
        settings.add({
            name: 'metadata',
            label: 'Metadata',
            tag: 'checkbox',
            value: optionSettings['metadata'] ? optionSettings['metadata'] : ['creator_tesim'],
            choices: {
                creator_tesim: 'Creator,creator',
                date_ssi: 'Date Created',
                abstract_tesim: 'Abstract/Description',
            },
        });

        shortcode.set('settings', settings);
    } else {
        // @TODO presumably this should give something to the UI? But what? PMJ
        console.log('not a known shortcode type');
    }

    return shortcode;
}
