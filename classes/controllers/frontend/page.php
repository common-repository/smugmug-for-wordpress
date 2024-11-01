<?php

/**
 * SMW_Controllers_Frontend_Page - Controls the admin settings page
 * 
 * @package SMW_Controllers
 * @subpackage Frontend
 * @author  Anthony Humes
 **/
class SMW_Controllers_Frontend_Page {
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object|SMW_Controllers_Admin_Galleries_Edit
     * @see SMW::getHelper()
     */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @return  void
     */
    private function __construct() {

    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Admin_Galleries_Edit
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Loads the template in place of the default WP template
     * 
     * @access  public
     * @return  void
     */
    function templateRedirect() {
        global $post;

        $post_type = get_query_var('post_type');

        $gallery_template = get_post_meta($post->ID,'smw_custom_post_template',true);

        $default_template = SMW_TEMPLATE_DIR . 'page.php';

        //print_r($default_template);

        if ($post_type == 'smw-gallery') {  // check your post type
            //print_r(TEMPLATE_DIR);
            if($gallery_template != 'default') {
                    load_template(SMW_TEMPLATE_DIR . $gallery_template . '.php');					
            } else {
                if(file_exists($default_template)) {
                    load_template($default_template);
                } else {
                    $default_template = SMW_PLUGIN_DIR . 'templates/page.php';
                    load_template($default_template);
                }

            }

            exit;

        }

    }
}