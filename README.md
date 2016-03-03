Plugin that provides functionality and templates for bringing DRS data into WP for the DRS Toolkit.

Should be cloned into wp-content/plugins with
```
  git clone https://github.com/dharammaniar/drs-toolkit-wp-plugin.git drs-tk
```
Relies on Javascript being enabled and AJAX - Should work in IE10 and up, Chrome, Firefox, and Safari

[Corresponding theme](https://github.com/NEU-Libraries/drs-toolkit-wp-theme)

### Setup

1. Ask Karl for clean install of WP on correct server (will need to provide a directory name)

2. Log in to new Wordpress site as admin user

3. Install Quest theme (go to /wp-admin/theme-install.php and search for quest then click install)

4. SSH into correct directory on server and git clone this repo into wp-content/plugins

  ```
    git clone https://github.com/NEU-Libraries/drs-toolkit-wp-plugin.git drs-tk
  ```

5. Also clone the theme into wp-content/themes

  ```
    git clone https://github.com/NEU-Libraries/drs-toolkit-wp-theme.git quest-child
  ```

6. Register your site on google analytics and add your code to a git-ignored file called analytics.php

  ```
    cd /wp-content/themes/quest-child
    vi analytics.php
  ```

  Example file contents:

  ```
    <?php
      echo "<script>[YOUR GOOGLE ANALYTICS CODE]
      function add_google_tracking(){
        jQuery('.button').on('click', function() {
          ga('send', 'event', jQuery(this).data('label'), 'click', jQuery(this).data('pid'));
          console.log('send', 'event', jQuery(this).data('label'), 'click', jQuery(this).data('pid'));
        });
      }
      </script>
      ";
  ```

7. If the project is going to need to override styles, add a overrides.css file (which is gitignored so changes won't be overwritten)

  ```
    cd /wp-content/themes/quest-child
    touch overrides.css
  ```

8. Delete extra themes to avoid user confusion

  ```
    cd wp-content/themes
    rm -rf twentyfifteen
    rm -rf twentyfourteen
    rm -rf twentythirteen
  ```

9. Go to /wp-admin/plugins.php in your browser. Install dependent plugins: Relevanssi, Page Builder by SiteOrigin, Black Studio TinyMCE Widget, and Widget Context and activate them.

10. Go to /wp-admin/plugins.php in your browser. Activate DRS Toolkit Plugin

11. Go to Settings > Reading and set Front Page Displays to a static page then choose a static page.

12. Go to Settings > Discussion and uncheck the box that says 'Allow people to post comments on new articles' to disable comments by default

13. Go to Settings > DRS Toolkit and enter collection URL and click Update

14. Go to Appearance > Themes and activate DRS Toolkit (Quest Child Theme)

15. Go to Appearance > Customize > Layout > Search Results. Change sidebar to none.

16. Go to Appearance > Customize > Colors > Global. Change Accent Color and  Accent Shade Color to #c00

17. Click the '<' arrow then Header. Change Secondary Header > Background Color to #494949, Text Color and Social Icon Color to #AFAFAF, Top Border Color to #3C3C3C and Social Icon Hover Color to #EFEFEF

18. Click the '<' arrow then Main Menu. Change Menu Items > Text Hover/Focus Color and SubMenu Items Hover/Focus Text Color to #c00.

19. Click the '<' arrow then Footer. Change Social Icon Hover Color and Social Icon Hover background color to #c00. Change Secondary Footer Background Color to #3C3C3C.

20. Go to Pages > Delete the 'Sample Page'. Go to Posts > Delete the 'Hello World'.

---
If you would like breadcrumbs on single pages/posts (not drs items) that reflect hierarchy, simply drag and drop the pages in the wp-admin pages screen to nest.

---
Optional Steps for Updating

1. Install [Composer](https://getcomposer.org) ([Install Directions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx))

2. Add a `composer.json` file to the root directory of your Wordpress install with the following content:

  ```
      {
    	"repositories":[
    		{
    			"type":"vcs",
    			"url":"https://github.com/NEU-Libraries/drs-toolkit-wp-plugin"
    		}
    	],
    	"require": {
    		"drs-tk/drs-tk":"dev-master"
    	},
    	"extra" : {
            	"installer-paths" : {
                		"wp-content/plugins/{$name}/": ["type:wordpress-plugin"]
            	}
        	}
    }
  ```
3. Update the plugin by running `php composer.phar update` in the root directory of your wordpress install.
