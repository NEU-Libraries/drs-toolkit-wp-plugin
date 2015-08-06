Plugin that provides functionality and templates for bringing DRS data into WP for the DRS Toolkit.

Should be cloned into wp-content/plugins with
```
  git clone https://github.com/NEU-Libraries/drs-toolkit-wp-plugin.git drs-tk
```
Relies on Javascript being enabled and AJAX - Should work in IE10 and up, Chrome, Firefox, and Safari

[Corresponding theme](https://github.com/NEU-Libraries/drs-toolkit-wp-theme)

### Setup

1. Ask Karl for clean install of WP on correct server (will need to provide a directory name)

2. Log in to new Wordpress site as admin user

3. Install Minamaze theme (go to /wp-admin/theme-install.php and search for minamaze then click install)

4. SSH into correct directory on server and git clone this repo into wp-content/plugins

  ```
    git clone https://github.com/NEU-Libraries/drs-toolkit-wp-plugin.git drs-tk
  ```

5. Also clone the theme into wp-content/themes

  ```
    git clone https://github.com/NEU-Libraries/drs-toolkit-wp-theme.git minamaze-child
  ```

6. Register your site on google analytics and add your code to a git-ignored file called analytics.php

  ```
    cd /wp-content/themes/minamaze-child
    vi analytics.php
  ```

  Example file contents:

  ```
    <?php
      echo "<script type='text/javascript'>[YOUR GOOGLE ANALYTICS CODE]</script>";
  ```

7. If the project is going to need to override styles, add a overrides.css file (which is gitignored so changes won't be overwritten)

  ```
    cd /wp-content/themes/minamaze-child
    touch overrides.css
  ```

8. Delete extra themes to avoid user confusion

  ```
    cd wp-content/themes
    rm -rf twentyfifteen
    rm -rf twentyfourteen
    rm -rf twentythirteen
  ```

9. Go to /wp-admin/plugins.php in your browser. Install dependent plugins: Relevanssi, Page Builder by SiteOrigin, and Black Studio TinyMCE Widget and active them.

10. Go to /wp-admin/plugins.php in your browser. Activate DRS Toolkit Plugin

11. Go to Settings > Reading and set Front Page Displays to a static page then choose a static page.

12. Go to DRS Toolkit settings and enter collection URL and click Update

13. Go to Appearance > Themes and activate DSG (Minamaze Child Theme)

14. Go to Appearance > Theme Options > Header and enable search then save changes
