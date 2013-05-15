Easy WordPress Settings Pages
=============================

Setting Things Up
--------

The magic happens in the easy_wordpress_settings_page class, which contains a static method create which accepts an configuration argument (in the form of an associative array).  

The associative array is to have this structure:  

* $options['parent'] -- the parent menu name for this menu to sit under, it defaults to settings, I've built in settings of settings, dashboard, posts, media, links, pages, comments, appearance, plugins, users, tools which correspond to their approriate $parent_slug (see http://codex.wordpress.org/Function_Reference/add_submenu_page for more information, feel free to use the parent's actual slug, e.g. options-general.php for settings)  
* $options['menu_text'] -- the menu item name for this settings page  
* $options['page_title'] -- the title and the heading of this settings page  
* $options['capability'] -- The capability required for this menu to be displayed to the user. (see http://codex.wordpress.org/Roles_and_Capabilities for more info.)  
* $options['slug'] -- The slug name to refer to this menu by (should be unique for this menu).  
* $options['sections'] is to be an array or an array of arrays of sections for this settings page, I have included a sample of how a section array should be built below, here is information about the elements of each of these array:  
  
Each array underneath sections should include:  
- section_name - subheading for the settings  
- items - an array of arrays of information to collect  
- Each array underneath item should include:  
- type - currently you can choose from text, textarea, checkbox, fileupload †, pulldown ††  
- id - a unique id for this information  
- title - the title of the information you are collecting  
- description (optional) - a description of this information you are collecting  
  
† fileupload will store the URL of the uploaded file. If you want to get the attachment ID, I have included a helper class that has a method (hat tip to Pippin's Plugins (http://pippinsplugins.com/retrieve-attachment-id-from-image-url/) for the basis for this method), to use it: `$attachment_id = easy_wordpress_settings_helper::get_attachment_id($url)`  
†† pulldown should have an additional key=>value with a key of 'choices' and its value is the array of choices, see below for how to construct this  
  
  
Retrieving The Settings  
--------

These settings will be stored in key=>value pairs in a wp_option with the id the same as the slug, so in the example below, to get the site's name, it would be:  
    `$option = get_option("about_the_site");  `
    `$site_name = $option['name'];`

