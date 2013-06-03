<?php
/**
 * @package Easy WordPress Settings pages
 * @version 0.1
 */

/*
Plugin Name: Easy WordPress Settings
Plugin URI: http://matgargano.com
Author: Mat Gargano
Version: 0.1
Author URI: http://matgargano.com/
Description: A way to create settings pages on the fly!

***************************************************************************************************
***************************************************************************************************
**************************************   Setting Things Up   **************************************
***************************************************************************************************
***************************************************************************************************


The magic happens in the easy_wordpress_settings_page class, which contains a static method create which accepts an configuration argument (in the form of an associative array).

The associative array is to have this structure:

$options['parent'] -- the parent menu name for this menu to sit under, it defaults to settings, I've built in settings of settings, dashboard, posts, media, links, pages, comments, appearance, plugins, users, tools which correspond to their approriate $parent_slug (see http://codex.wordpress.org/Function_Reference/add_submenu_page for more information, feel free to use the parent's actual slug, e.g. options-general.php for settings)
$options['menu_text'] -- the menu item name for this settings page
$options['page_title'] -- the title and the heading of this settings page
$options['capability'] --  The capability required for this menu to be displayed to the user. (see http://codex.wordpress.org/Roles_and_Capabilities for more info.)
$options['slug'] --  The slug name to refer to this menu by (should be unique for this menu).
$options['sections'] is to be an array or an array of arrays of sections for this settings page, I have included a sample of how a section array should be built below, here is information about the elements of each of these array:
	Each array underneath sections should include:
		section_name -  subheading for the settings
		items - an array of arrays of information to collect
			Each array underneath item should include:
				type - currently you can choose from text, textarea, checkbox, richtext, fileupload †, pulldown ††
				id - a unique id for this information
				title - the title of the information you are collecting
				description (optional) - a description of this information you are collecting

† fileupload will store the URL of the uploaded file. If you want to get the attachment ID, I have included a helper class that has a method (hat tip to Pippin's Plugins (http://pippinsplugins.com/retrieve-attachment-id-from-image-url/) for the basis for this method), to use it: $attachment_id = easy_wordpress_settings_helper::get_attachment_id($url)
†† pulldown should have an additional key=>value with a key of 'choices' and its value is the array of choices, see below for how to construct this


***************************************************************************************************
***************************************************************************************************
**********************************   Retrieving these settings   **********************************
***************************************************************************************************
***************************************************************************************************

These settings will be stored in key=>value pairs in a wp_option with the id the same as the slug, so in the example below, to get the site's name, it would be:
	$option = get_option("about_the_site");
	$site_name = $option['name'];



*/

add_action('admin_menu', 
	function() {
		$options['parent'] = "settings";   
		$options['menu_text'] = "About the Site";
		$options['page_title'] = "About the Site";
		$options['capability'] = "manage_options";
		$options['slug'] = "about_the_site";
		$options['sections'] = 
			array(
				array(
					"section_name"=>"Information about this site",
					"items" => array(
						array(
							'type' => 'text',
							'id' => 'name',
							'title' => 'What\'s Your Site\'s Name?',
							'desc' => 'What do you go by?'
							),
						array(
							'type' => 'textarea',
							'id' => 'description',
							'title' => 'Biography',
							'desc' => 'Tell us a bit about yourself.'
							),
						array(
							'type' => 'richtext',
							'id' => 'rich',
							'title' => 'Biography with HTML',
							'desc' => 'Tell us a bit about yourself... with HTML!'
							),							
						array(
							'type' => 'checkbox',
							'id' => 'recommend',
							'title' => 'Store user information?',
							'desc' => 'Check if you want to store information about the users'
			 				),
						array(
							'type' => 'fileupload',
							'id' => 'image',
							'title' => 'Site Image',
							'desc' => 'Upload an image for the site', 
							),
						array(
							'type' => 'pulldown',
							'id' => 'whos_on_first',
							'title'=>'Who\'s on First?',
							'description'=>'Come on Costello!',
							'choices'=>array(
								'1'=>'Who',
								'2'=>'What',
								'3'=>'I Don\'t Know',
							)
						),
					)
				),
			);
		easy_wordpress_settings_page::create($options);
	}, 1);

/* DO NOT TOUCH BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING! */
foreach (glob(plugin_dir_path(__FILE__) . "lib/*.php") as $filename) include $filename;




