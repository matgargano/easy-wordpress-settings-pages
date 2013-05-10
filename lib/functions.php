<?php
/**
 * @package Easy WordPress Settings Pages
 * @version 0.1
 */


/**
 * Static helper functions for Easy WordPress Settings Pages
 *
 * @author Matthew Gargano <mgargano @ gmail.com>
 * 
 */

class easy_wordpress_settings_helper {
    

    /*
     *   Static function to get the attachment_id from a URL
     *   
     *   Usage:
     *      <code>
     *      $option = get_option("about_the_site");
     *      $site_image = $option['image_url'];
     *      $attachment_id = easy_wordpress_settings_helper::get_attachment_id($url)
     *      echo wp_get_attachment_image( $attachment_id, 'full' );
     *      </code>
     *    
     *    @param string $attachment_url URL of the attachment you wish to retrieve the attachment_id of
     *     
     *    @access public
     */

    public static function get_attachment_id($attachment_url) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $attachment_url )); 
            return $attachment[0]; 
    }
}