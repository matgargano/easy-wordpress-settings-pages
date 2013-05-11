<?php
/**
 * @package Easy WordPress Settings pages
 * @version 0.1
 */

/**
 * Static function to create a submenu, primarily used by create method of easy_wordpress_settings_page class 
 *
 * @author Matthew Gargano <mgargano @ gmail.com>
 * 
 */

class easy_wordpress_submenu {

    /*
     *    Static method to get create submenus
     *    
     *    @param array $args array of arguments for submenu, explained in more detail in primary plugin file
     *     
     *    @access public
     */

    public static function create($args=null){
            if (!$args) return false;
            $parent = strtolower($args['parent']);
            if (!$parent) $parent = "settings";
            $title = $args['title'];
            $text = $args['text'];
            $capability = $args['capability'];
            $slug = $args['slug'];

            switch($parent){
                case 'dashboard': 
                    $name = "index.php";
                    break;
                case 'posts':
                    $name = 'edit.php';
                    break;
                case 'media':
                    $name='upload.php';
                    break;
                case 'links':
                    $name='link-manager.php';
                    break;
                case 'pages':
                    $name='edit.php?post_type=page';
                    break;
                case 'comments':
                    $name='edit-comments.php';
                    break;
                case 'appearance':
                    $name='themes.php';
                    break;
                case 'plugins':
                    $name='plugins.php';
                    break;  
                case 'users':
                    $name='users.php';
                    break;  
                case 'tools':
                    $name='tools.php';
                    break;  
                case 'settings':
                    $name='options-general.php';
                    break; 
                default:
                    $name = $parent;
                    break; 
                
            }
            add_action('admin_menu', function() use ($name, $title, $text, $capability, $slug) {

                    add_submenu_page(  
                        $name,                  
                        $title,          
                        $text,                  
                        $capability,            
                        $slug, function() use  ($name, $title, $text, $capability, $slug) {
                       ?>
                            <div class="wrap">  
                          
                                
                                <div id="icon-themes" class="icon32"></div>  
                                <h2><?php echo $title; ?></h2>  
                                <form method="post" action="options.php">  
                                    <?php settings_fields( $slug ); ?>  
                                    <?php do_settings_sections( $slug );?>    
                                    <?php submit_button(); ?>  
                                </form>  
                          
                            </div>

                       <?php
                        }
                    
                    );
                }
            );
        

    
        }
    }

  
    


?>