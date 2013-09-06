<?php
/**
 * @package Easy WordPress Settings pages
 * @version 0.2
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
public static function test($data){
	ob_start();
	var_dump($data);
	$data2=ob_get_clean();
	error_log("$data2", 3, '/tmp/bbb.log');
	return $data;
}
	    public static function create( $args=null ){
		if ( ! $args ) return false;
		add_action( 'admin_enqueue_scripts', function(){
			wp_register_script( 'easy_settings', easy_wordpress_settings_page::plugins_url('js/settings.js', __FILE__) , array( 'jquery', 'thickbox', 'media-upload' ), null );  
			wp_enqueue_style( 'thickbox' );
		    wp_enqueue_script( 'easy_settings' );
		    wp_enqueue_media();
		});

		
		foreach( $args['sections'] as $section ){
			$section_name = $section['section_name'];
			
		    if( false == get_option( $args['slug'] ) ) {     
		        add_option( $args['slug'] );  
		    } 
		    $options = get_option( $args['slug'] );
			add_settings_section( $args['slug'], $section_name, null, $args['slug'] );
			foreach( $section['items'] as $item ) {
				$type = array_key_exists( 'type', $item ) ? $item['type'] : null;
				$main_id  = array_key_exists( 'id', $item ) ? $type . "_" . $item['id'] : null;
				$description = array_key_exists( 'desc', $item) ? $item['desc'] : null;
				$title = array_key_exists( 'title', $item ) ? $item['title'] : null;
				$choices = array_key_exists('choices', $item) ? $item['choices'] : null;
				if ( $description ) $title = $title . '<br /><small class="italic">' . $description . '</small>';
				switch( $type ){
					case 'checkbox':
						add_settings_field( $main_id, $title, 
								function ( $section ) use ( $options, $args ) {
									$id = $section[0];
									if ( is_array( $options ) && array_key_exists( $id, $options ) ) $value = $options[$id];
									else $value = '';									
									$html = '<input type="checkbox" id="' . $args['slug'] . '[' . $id . ']" name="' . $args['slug'] . '[' . $id . ']" value="1" ' . checked(1, $value, false) . '/>';  
									echo $html;  
								},
						$args['slug'], $args['slug'], array( $main_id ) );
					break;
					case 'text':
						add_settings_field( "first" . $main_id, "second" . $title, 
								function ( $section ) use ( $options, $args ) {
									$id = $section[0];
									if ( ! is_array( $options ) ) $value = '';
									else $value = esc_html( $options[$id] );
									$html = '<input type="text" id="' . $args['slug'] . '[' . $id . ']" name="' . $args['slug'] . '[' . $id . ']" value="' . $value . '" />';  
								    echo $html;  
								},
						$args['slug'], $args['slug'], array( $main_id ) );
					break;
					case 'fileupload':  
						add_settings_field( $main_id, $title, 
								function ( $section ) use ( $options, $args ) {
									$id = $section[0];
									$html = '<div class="upload-file">';
									$value = '';
									$hidden = 'hidden';
									if ( array_key_exists( $id, $options ) )$value = esc_html( $options[$id] );
									$img = '<img />';
									$object = json_decode( html_entity_decode( $value ) );
									if ( gettype( $object ) === "object" ) {
										$img = '<img src="' . $object->sizes->thumbnail->url . '" />';
										$hidden = '';
									}	
									$remove = '<input class="clear-upload button ' . $hidden . '" type="button" value="Remove Upload" />';

									$html .= $img . '<input class="file" type="hidden" id="' . $id . '" name="' . $args["slug"] . '[' . $id . ']" value="' . $value . '" />';  

									$html .= '<br /><input class="upload-button button" type="button" value="Upload / Select" /> &nbsp;&nbsp;' . $remove;
									$html .= '</div>';
								    echo $html;  
								},
						$args['slug'], $args['slug'], array( $main_id ) );
					break;
					case 'textarea':
						add_settings_field( $main_id, $title, 
								function ( $section ) use ( $options, $args ) {
									$id = $section[0];
									if ( ! is_array( $options ) ) $value = '';
									else $value = esc_html( $options[$id] );
									$html = '<textarea class="large-text" cols="50" rows="10" type="text" id="' . $args['slug'] . '[' . $id . ']" name="' . $args['slug'] . '[' . $id . ']">' . $value . '</textarea>';  
								    echo $html;  
								},
						$args['slug'], $args['slug'], array( $main_id ) );
					break;
					case 'pulldown':
						add_settings_field( $main_id, $title, 
								function ( $section ) use ( $options, $args ) {
									
									$id = $section[0];
									$choices = $section[1];
									$value = $options[$id];
									$html = '<select id="' . $args['slug'] . '[' . $id . ']" name="' . $args['slug'] . '[' . $id . ']">';
									$html .= '<option value=""> - Select - </option>';
									foreach( $choices as $key=>$val ){
										$selected = '';
										if ( $value== $key ) $selected = ' selected="selected" ';
										$html .= '<option value="' . $key . '"' . $selected . '>'.$val.'</option>';
									}
									$html .= '</select>';
									
								    echo $html;  
								},
						$args['slug'], $args['slug'], array( $main_id, $choices ) );
					break;
					case "richtext":
						/**
						  * @todo add rich text :)
						  */

						break;	
				}
			register_setting($args['slug'], $args['slug'], array(__CLASS__, "test"));	
			}
			
		}
		easy_wordpress_submenu::create( array(
			'parent' => $args['parent'],
			'title'=>$args['page_title'],
			'text'=>$args['menu_text'],
			'capability'=>$args['capability'],
			'slug'=>$args['slug']

		) );
	}

	/**
	 * @method plugins_url 
	 * 
	 * Allows this plugin to be included in a theme or ran from plugins
	 * 
	 * @param type $relative_path
	 * @param type $plugin_path
	 * @author prettyboymp
	 * @return string
	 */
	public static function plugins_url( $relative_path, $plugin_path ) {
		$template_dir = get_template_directory();

		foreach (array( 'template_dir', 'plugin_path' ) as $var) {
			$$var = str_replace( '\\', '/', $$var ); // sanitize for Win32 installs
			$$var = preg_replace( '|/+|', '/', $$var );
		}
		if ( 0 === strpos( $plugin_path, $template_dir ) ) {
			$url = get_template_directory_uri();
			$folder = str_replace( $template_dir, '', dirname( $plugin_path ) );
			if ( '.' != $folder ) {
				$url .= '/' . ltrim( $folder, '/' );
			}
			if ( !empty( $relative_path ) && is_string( $relative_path ) && strpos( $relative_path, '..' ) === false ) {
				$url .= '/' . ltrim( $relative_path, '/' );
			}
			return $url;
		} else {
			return plugins_url( $relative_path, $plugin_path );
		}
	}
}