<?php
/**
 * @package Easy WordPress Settings pages
 * @version 0.1
 */

/**
 * Static function to create a settings page
 *
 * @author Matthew Gargano <mgargano @ gmail.com>
 * 
 */


class easy_wordpress_settings_page {
	/*
     *    Static method to get create settings page
     *    
     *    @param array $args array of arguments for settings page, explained in more detail in primary plugin file
     *     
     *    @access public
     */

	    public static function create($args=null){
		if (!$args) return false;
		add_action("admin_enqueue_scripts", function(){
			wp_register_script( 'easy_settings_script', plugin_dir_url(__FILE__) . 'js/settings.js', array('jquery', 'thickbox', 'media-upload'), null);  
		    wp_enqueue_style('thickbox');
		    wp_enqueue_script("easy_settings_script");
		});


		foreach($args['sections'] as $section){
			$section_name = $section['section_name'];
			
		    if( false == get_option( $args['slug'] ) ) {     
		        add_option( $args['slug'] );  
		    } 
		    $options = get_option($args['slug']);
			add_settings_section($args['slug'], $section_name, null, $args['slug']);
			foreach($section['items'] as $item) {
				$type = $item['type'];
				$main_id  = $item['id'];
				$description = $item['desc'];
				$title = $item['title'];
				$choices = $item['choices'];
				if ($description) $title = $title . '<br /><small class="italic">' . $description . '</small>';
				switch($type){
					case "checkbox":
						add_settings_field($main_id, $title, 
								function ($section) use ($options, $args) {
									$id = $section[0];
									$html = '<input type="checkbox" id="' . $args['slug'] . '[' . $id . ']" name="' . $args['slug'] . '[' . $id . ']" value="1" ' . checked(1, $options[$id], false) . '/>';  
									echo $html;  
								},
						$args['slug'], $args['slug'], array($main_id));
					break;
					case "text":
						add_settings_field($main_id, $title, 
								function ($section) use ($options, $args) {
									$id = $section[0];
									unset($html);
									$html .= '<input type="text" id="' . $args['slug'] . '[' . $id . ']" name="' . $args['slug'] . '[' . $id . ']" value="' . $options[$id] . '" />';  
								    echo $html;  
								},
						$args['slug'], $args['slug'], array($main_id));
					break;
					case 'fileupload':  
						add_settings_field($main_id, $title, 
								function ($section) use ($options, $args) {
									$id = $section[0];
									unset($html);
									$html .= '<div class="upload-image">';
									$html .= "<input class='upload-file' type='text' id='$id' name='" . $args['slug'] . "[$id]' value='$options[$id]' />";  
									$html .= '<input class="upload-image-button" type="button" value="Upload" />';
									$html .= '</div>';
								    echo $html;  
								},
						$args['slug'], $args['slug'], array($main_id));


					    
					break;
					case "textarea":
						add_settings_field($main_id, $title, 
								function ($section) use ($options, $args) {
									$id = $section[0];
									unset($html);
									$html .= '<textarea class="large-text" cols="50" rows="10" type="text" id="' . $args['slug'] . '[' . $id . ']" name="' . $args['slug'] . '[' . $id . ']">' . $options[$id] . '</textarea>';  
								    echo $html;  
								},
						$args['slug'], $args['slug'], array($main_id));
					break;
					case "pulldown":
						add_settings_field($main_id, $title, 
								function ($section) use ($options, $args) {
									$id = $section[0];
									$choices = $section[1];
									$value = $options[$id];
									unset($html);
									$html = '<select id="' . $args['slug'] . '[' . $id . ']" name="' . $args['slug'] . '[' . $id . ']">';
									$html .= '<option value=""> - Select - </option>';
									foreach($choices as $key=>$val){
										$selected = '';
										if ($value== $key) $selected = ' selected="selected" ';
										$html .= '<option value="' . $key . '"' . $selected . '>'.$val.'</option>';
									}
									$html .= '</select>';
									
								    echo $html;  
								},
						$args['slug'], $args['slug'], array($main_id, $choices));
					break;					
				}
			register_setting($args['slug'], $args['slug']);	
			}
			
		}
		easy_wordpress_submenu::create(array(
			"parent" => $args['parent'],
			"title"=>$args['page_title'],
			"text"=>$args['menu_text'],
			"capability"=>$args['capability'],
			"slug"=>$args['slug']

		));
	}
}