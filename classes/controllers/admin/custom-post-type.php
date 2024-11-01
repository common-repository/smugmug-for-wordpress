<?php

/**
 * SMW_Controllers_Admin_CustomPostType - Controls the Custom Post Type for the main galleries
 *
 * @package SMW_Controllers
 * @subpackage Admin
 * @author Anthony Humes
 **/
class SMW_Controllers_Admin_CustomPostType extends SMW_Controllers_Abstract {
    /**
    * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
    * 
    * @access private
    * @var Object|SMW_Controllers_Admin_CustomPostType
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
     * @return  Object|SMW_Controllers_Admin_CustomPostType
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Calls the function which creates the custom post type and flushes the rewrite rules
     * 
     * @access  public
     */
    public function rewriteFlush() {
        $this->create_post_type();
        flush_rewrite_rules();
    }
    /**
     * Creates the custom post type
     * 
     * @access  public
     */
    public function createPostType() {
        $post_form = SMW::getControl('admin/custom-post-type/forms');
        $forms = SMW::getHelper('forms');

        $meta_boxes = $post_form->galleryPostFields();

        $array_keys = array_keys($meta_boxes);

        if($_POST[$meta_boxes[$array_keys[0]]['name']]) {
            $forms->saveForm( $meta_boxes, true );
        }
        $labels = array(
            'name' => __( get_option('smw_post_type_name') ),
            'singular_name' => __( get_option('smw_post_type_singular_name') ),
            'add_new' => __( get_option('smw_post_type_add_new') ),
            'view_item' => __( get_option('smw_post_type_view_item') ),
            'search_items' => __( get_option('smw_post_type_search_items') ),
            'add_new_item' => __( get_option('smw_post_type_add_new_item') ),
            'edit_item' => __( get_option('smw_post_type_edit_item') ),
            'menu_name' => __( get_option('smw_post_type_menu_item') ),
            );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'capability_type' => 'page',
            'show-in-menu' => true,

            'supports' => array(
                'title',
                'editor',
                'page-attributes'
                ),
            'taxonomies' => array( 'post_tag','category'),
            'rewrite' => array(
                'slug' => get_option('smw_post_type_slug'),
                'with_front' => true
                ),
            );
        register_post_type( 'smw-gallery',$args );
    }
    /**
     * Creates the options that show up on the custom post type
     * 
     * @access  public
     */
    public function addOptionsToPage() {
            $forms = SMW::getControl('admin/custom-post-type/forms');
            //print_r($this->templates);
            add_meta_box( 'smugpress-meta-boxes', __('SmugMug Options'), array(&$forms,'pageMetaBoxes'), 'smw-gallery', 'normal', 'default' );
    }
    /**
     * Sets the custom post type page to default if nothing is selected
     * 
     * @access  public
     */
    public function defaultTemplate() {
        global $post;

        $gallery_template = get_post_meta($post->ID,'smw_custom_post_template',true);

        if ( $gallery_template == '' )
                add_post_meta( $post->ID, 'smw_custom_post_template', 'default', true );

    }
        
        
}
