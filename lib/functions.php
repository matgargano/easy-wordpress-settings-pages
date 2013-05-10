<?php
/**
 * @package Easy WordPress Settings pages
 * @version 0.1
 */

class easy_settings_helper {

    function get_image_id($image_url) {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $image_url )); 
            return $attachment[0]; 
    }

    function get_image($post_id=null, $size=null){
        global $post;
        if (!$post_id) $post_id = get_the_ID();
        if (!$post_id) $post_id = $post->ID;
        if (!$post_id) return false;
        $image =  wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size); 
        $image = $image[0];
        return $image;
    }


}