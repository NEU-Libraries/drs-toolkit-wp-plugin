== Think Up Themes ==

- By Think Up Themes, http://www.thinkupthemes.com/

Requires at least:	3.4.1
Tested up to:		3.9.2

Minamaze is a multi-purpose professional Premium WordPress Theme ideal for a business or blog website. The theme is responsive, HD retina ready and comes with 600+ Google Fonts which can easily be selected directly from the theme options panel.


-----------------------------------------------------------------------------
	Support
-----------------------------------------------------------------------------

- For support for Minamaze (free) please post a support ticket over at the https://wordpress.org/support/theme/minamaze.


-----------------------------------------------------------------------------
	Frequently Asked Questions
-----------------------------------------------------------------------------

- None Yet


-----------------------------------------------------------------------------
	Limitations
-----------------------------------------------------------------------------

- RTL support is yet to be added. This is planned for inclusion in v1.4.
- Multi-language support is yet to be added. This is planned for inclusion in v1.4.


-----------------------------------------------------------------------------
	Copyright, Sources, Credits & Licenses
-----------------------------------------------------------------------------

Minamaze WordPress Theme, Copyright 2014 Think Up Themes Ltd
Minamaze is distributed under the terms of the GNU GPL

Demo images are licensed under CC0 1.0 Universal (CC0 1.0) and available from http://unsplash.com/

The following opensource projects, graphics, fonts, API's or other files as listed have been used in developing this theme. Thanks to the author for the creative work they made. All creative works are licensed as being GPL or GPL compatible.

    [1.01] Item:        Underscores (_s) starter theme - Copyright: Automattic, automattic.com
           Item URL:    http://underscores.me/
           Licence:     Licensed under GPLv2 or later
           Licence URL: http://www.gnu.org/licenses/gpl.html

    [1.02] Item:        Redux Framework
           Item URL:    https://github.com/ReduxFramework/ReduxFramework
           Licence:     GPLv3
           Licence URL: http://www.gnu.org/licenses/gpl.html

    [1.03] Item:        TGM Plugin Activation
           Item URL:    http://tgmpluginactivation.com/#license
           Licence:     GPLv3
           Licence URL: http://www.gnu.org/licenses/gpl.html

    [1.04] Item:        html5shiv (jQuery file)
           Item URL:    http://code.google.com/p/html5shiv/
           Licence:     MIT
           Licence MIT: http://opensource.org/licenses/mit-license.html

    [1.05] Item:        Wordpress Sidebar Generator
           Item URL:    https://github.com/Smartik89/Wordpress-Sidebar-Generator
           Licence:     GPLv3
           Licence URL: http://www.gnu.org/licenses/gpl.html

    [1.06] Item:        Custom Metaboxes and Fields for WordPress
           Item URL:    https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress/blob/master/readme.md
           Licence:     GPLv2
           Licence URL: http://www.gnu.org/licenses/gpl-2.0.html

    [1.07] Item:        PrettyPhoto
           Item URL:    http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/
           Licence:     GPLv2
           Licence URL: http://www.gnu.org/licenses/gpl-2.0.html

    [1.09] Item:        ImagesLoaded
           Item URL:    https://github.com/desandro/imagesloaded
           Licence:     MIT
           Licence URL: http://opensource.org/licenses/mit-license.html

    [1.10] Item:        Sticky
           Item URL:    https://github.com/garand/sticky
           Licence:     MIT
           Licence URL: http://opensource.org/licenses/mit-license.html

    [1.11] Item:        Waypoints
           Item URL:    https://github.com/imakewebthings/jquery-waypoints
           Licence:     MIT
           Licence URL: http://opensource.org/licenses/mit-license.html

    [1.12] Item:        Retina js
           Item URL:    http://retinajs.com
           Licence:     MIT
           Licence URL: http://opensource.org/licenses/mit-license.html

    [1.13] Item:        ResponsiveSlides
           Item URL:    https://github.com/viljamis/ResponsiveSlides.js
           Licence:     MIT
           Licence URL: http://opensource.org/licenses/mit-license.html

    [1.14] Item:        Font Awesome
           Item URL:    http://fortawesome.github.io/Font-Awesome/#license
           Licence:     SIL Open Font &  MIT
           Licence OFL: http://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL
           Licence MIT: http://opensource.org/licenses/mit-license.html

    [1.15] Item:        Twitter Bootstrap
           Item URL:    https://github.com/twitter/bootstrap/wiki/License
           Licence:     Apache 2.0
           Licence URL: http://www.apache.org/licenses/LICENSE-2.0

    [1.16] Item:        Elegant Icons
           Item URL:    http://www.elegantthemes.com/blog/resources/elegant-themes-icon-pack-for-free
           Licence:     Dual GPL and MIT
           Licence URL: /licenses/license_(elegant_icons).txt

    [1.17] Item:        Elegant Media Icons
           Item URL:    https://www.iconfinder.com/search/?q=iconset:elegantmediaicons
           Licence:     GPL
           Licence URL: http://www.gnu.org/licenses/gpl.html


-----------------------------------------------------------------------------
	Changelog
-----------------------------------------------------------------------------

Version 1.1.8
- New:     Tag "three-columns" added to style.css.
- Fixed:   Fix jQuery code used to add tr tags in main-backend.js. Improves compatibility with 3rd party code.
- Removed: Unnecessary placeholder theme options removed.

Version 1.1.7
- New:     Variable $thinkup_general_fixedlayoutswitch used to assign responsive layout for default settings.
- Updated: Theme now displays responsive layout on default settings. 
- Removed: Variable $thinkup_general_responsiveswitch was used to set fixed layout by default.

Version 1.1.6
- Updated: Change blog-style class to blog-article.
- Updated: Link to support forum updated in readme file.
- Updated: Change readme file to ensure support article link is correct.
- Updated: Coments in functions.php now applied using single line exclusions to make debugging easier.
- Updated: Add class="clearboth" after #slider so that it works correctly with all 3rd party shortcodes.

Version 1.1.5
- New:     Page title is now output using add_theme_support( 'title-tag' ) to ensure compliance with WordPress 4.1.1 guidelines.
- New:     Add WooCommerce compatibility to allow users to customize WooCommerce from theme folder.

Version 1.1.4
- Fixed:   Function thinkup_input_wptitle() removed as it causes issues with page title when posts are assigned to homepage.

Version 1.1.3
- Updated: Functions used to add additional image sizes are now child theme compatible. Allows user to override to fix rare http error.

Version 1.1.2
- Updated: URL Placeholder text added to dlider url field.
- Updated: blog article css updated to ensure white background - makes it easier for user to customize site.

Version 1.1.1
- New:     is_thinkuphome() function added back to theme. Renamed to thinkup_check_ishome.

Version 1.1.0
- Removed: All Think Up Themes widgets removed. Emergency update to correct "unexpected T_FUNCTION" error. Widgets will be added back in later version.

Version 1.0.9
- Fixed:   WordPress core checked() and selected() functions used where required.
- Updated: post_class() added to to content.php.
- Updated: Screenshot increased to 880x660 pixels.
- Updated: All functions preixed with redux_ in admin/main/options.php.
- Updated: Text domain added to translation functin thinkup_googlefonts_url().
- Updated: English text made translation ready in comments.php, content-single.php, image.php, sidebar.php, template-archive.php, template-sitemap.php, template-tags.php and extras.php.
- Removed: $more and $post globals removed from thinkup_input_blogtext()
- Removed: Typography field removed from Redux framework as it's not being used.
- Removed: require_once(ABSPATH .'/wp-admin/includes/file.php') removed from admin/main/options.php.
- Removed: home.php removed as index.php contains the same code, as such is no longer required.
- Removed: HTML removed from the_content(). Read more button html is now added using function thinkup_modify_read_more_link().

Version 1.0.8
- Updated: post_class() Added to relevant templates.
- Updated: Custom css gets sanitized using wp_kses_post.
- Updated: All custom functions now prefixed with thinkup_.
- Updated: Renamed to Browser class to thinkup_redux_Browser.
- Updated: home.php updated to include same code as archive.php.
- Updated: Custom css gets sanitized using wp_filter_nohtml_kses.
- Updated: All instances of user input data is escaped on output.
- Updated: index.php updated to include same code as archive.php.
- Updated: Translation text-domain added to all translation functions.
- Updated: Open Sans Google Font added using Frank Klein recommened method. (http://themeshaper.com/2014/08/13/how-to-add-google-fonts-to-wordpress-themes/)
- Updated: Function Walker_Nav_Menu_Responsive renamed to thinkup_Walker_Nav_Menu_Responsive.
- Updated: Renamed to thinkup_get_comments_popup_link as the function is used in the popular posts widget.
- Updated: All translatable strings checked and wrapped in appropriate translation function (i.e. __, _e, _n).
- Updated: Renamed to thinkup_get_comments_number_str as the function is used in the thefunction get_comments_popup_link function. 
- Removed: front-page.php removed.
- Removed: All empty PHP files removed.
- Removed: Simple_System_Info removed from redux framework.
- Removed: TGM plugin removed as no plugins are being used.
- Removed: Unnecessary instances of wp_reset_query() removed.
- Removed: taxonomies_for_pages() removed as this is plugin territory.
- Removed: Recent comments widget removed. Removed from current version.
- Removed: is_thinkuphome() function removed as it dulicated core functionality.
- Removed: All instances of create_function  removed. Function was present in framework.php and widgets files.
- Removed: thinkup_input_readmore custom functions removed. The excerpt read more text is now controlled using the new function thinkup_new_excerpt_more().

Version 1.0.7
- Fixed:   Blog posts now allow for custom date format to be used. Picks up fromat from Settings -> General.
- Updated: Language file updated to include new translatable strings added in v1.6.0.

Version 1.0.6
- Fixed:   Sanitization of Site Title and Site Description in theme options panel changed to allow non-english alphabet charachters.

Version 1.0.5
- Fixed:   Function thinkup_input_sliderhome() updated to correct "Uninitialized string offset" error.

Version 1.0.4
- Removed: Recommended plugins removed due to plugin errors with WordPress4.0 update.

Version 1.0.3
- Updated: Font Awesome files v4.2.0 added instead of hotlinking.
- Removed: Masonry script removed.

Version 1.0.2
- Fixed:   Read More text in featured homepage sections is now translation ready.
- Fixed:   get_page_link changed to get_permalink to ensure page links work correctly.
- Updated: Font Awesome v4.2.0 added.
- Updated: Translation .mo and .po files updated.

Version 1.0.1
- Fixed:   Header image code added to header.php.
- Fixed:   Translation issue corrected. Language folder location changed from /lib/languages to /languages.
- Updated: Function blog_favicon() renamed to thinkup_custom_favicon().
- Updated: Featured section image sizes changed from column3-2/3 to column3-1/3.
- Updated: Slider button changes from slider title to be Read More (translatable string).
- Updated: Responsive js only displays when HD ready logo is set. Prevents images 404 error on HD devices.
- Updated: Responsive layout class changed from fixed to layout-fixed. Allows more developer control with layout-responsive class.

Version 1.0.0
- New:     Initial release.