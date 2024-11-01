<?php

/**
 * SMW_Controllers_Admin_Settings - Controls the admin settings page
 * 
 * @package SMW_Controllers
 * @subpackage Admin
 * @author Anthony Humes
 **/
class SMW_Controllers_Admin_Settings extends SMW_Controllers_Abstract {
    /**
     * This variable is set in __construct and provides access to the form helper
     * 
     * @access private
     * @var Object|SMW_Helpers_Forms
     */
    protected $forms;
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object|SMW_Controllers_Admin_Settings
     */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @return  void
     */
    private function __construct() {
        $this->forms = SMW::getHelper('forms');
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Admin_Settings
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Renders the settings block for the type of settings discussed
     * 
     * @param string $type Options are 'general', 'lightbox', 'custom-post'. Determines which block to render.
     * @return html
     */
    function getSettings( $type ) {
        switch($type) {
            case 'general':
                return $this->generalBoxes();
                break;
            case 'lightbox':
                return $this->lightboxBoxes();
                break;
            case 'custom-post':
                return $this->customPostBoxes();
                break;
        }
    }
    
    /**
     * Returns an array of form fields to be used in the Custom Post settings section
     * 
     * @return array Form fields to be used in the Custom Post settings section 
     */
    
    function customPostBoxes() {
        $meta_boxes = array(
            'post_type_slug' => array( 'name' => 'smw_post_type_slug', 'title' => __('Slug', 'smw'), 'type' => 'text', 'description' => 'Prepend pages with this slug (ex. http://www.domain.com/slug/gallery-name).', 'size' => 40 ),
            'post_type_name' => array( 'name' => 'smw_post_type_name', 'title' => __('Name', 'smw'), 'type' => 'text', 'description' => 'General name for the post type, usually plural.', 'size' => 40 ),
            'post_type_singular_name' => array( 'name' => 'smw_post_type_singular_name', 'title' => __('Singular Name', 'smw'), 'type' => 'text', 'description' => 'name for one object of this post type.', 'size' => 40 ),
            'post_type_add_new' => array( 'name' => 'smw_post_type_add_new', 'title' => __('Add New Text', 'smw'), 'type' => 'text', 'description' => 'The add new text.', 'size' => 40 ),
            'post_type_view_item' => array( 'name' => 'smw_post_type_view_item', 'title' => __('View Item Text', 'smw'), 'type' => 'text', 'description' => 'The all items text used in the menu.', 'size' => 40 ),
            'post_type_search_items' => array( 'name' => 'smw_post_type_search_items', 'title' => __('Search Items Text', 'smw'), 'type' => 'text', 'description' => 'The search items text.', 'size' => 40 ),
            'post_type_add_new_item' => array( 'name' => 'smw_post_type_add_new_item', 'title' => __('Add New Item Text', 'smw'), 'type' => 'text', 'description' => 'The add new item text.', 'size' => 40 ),
            'post_type_edit_item' => array( 'name' => 'smw_post_type_edit_item', 'title' => __('Edit Item Text', 'smw'), 'type' => 'text', 'description' => 'The edit item text.', 'size' => 40 ),
            'post_type_menu_item' => array( 'name' => 'smw_post_type_menu_item', 'title' => __('Menu Item Text', 'smw'), 'type' => 'text', 'description' => 'The menu name text.', 'size' => 40 ),
        );
        return apply_filters( 'smw_post_meta_boxes', $meta_boxes );
    }
    
    /**
     * Returns an array of form fields to be used in the General settings section
     * 
     * @return array Form fields to be used in the General settings section 
     */
    
    function generalBoxes() {
        $default_thumb_sizes = array(
            array( 'name' => 'Thumb', 'value' => 'thumb_url' ),
            array( 'name' => 'Tiny', 'value' => 'tiny_url' ),
            array( 'name' => 'Small', 'value' => 'small_url' ),
            array( 'name' => 'Medium', 'value' => 'medium_url' )
        );
        $default_viewable_sizes = array(
            array( 'name' => 'Large', 'value' => 'large_url' ),
            array( 'name' => 'Extra Large', 'value' => 'xlarge_url' ),
            array( 'name' => 'Extra Large 2', 'value' => 'x2large_url' ),
            array( 'name' => 'Extra Large 3', 'value' => 'x3large_url' ),
            array( 'name' => 'Original', 'value' => 'original_url' )
        );
        $default_video_sizes = array(
            array( 'name' => '320', 'value' => 'video320_url' ),
            array( 'name' => '640', 'value' => 'video640_url' ),
            array( 'name' => '960', 'value' => 'video960_url' ),
            array( 'name' => '1280', 'value' => 'video1280_url' ),
            array( 'name' => '1920', 'value' => 'video1920_url' )
        );
        $default_ajax = array(
            array( 'name' => 'Ajax', 'value' => 'ajax' ),
            array( 'name' => 'Static', 'value' => 'static' )
        );
        /* Array of the meta box options. */
        $meta_boxes = array(
            'default_smugmug_url' => array( 'name' => 'smw_default_service_url', 'title' => __('Default SmugMug URL', 'smw'), 'type' => 'text', 'description' => 'Change this ONLY if you want your photos to come from your SmugMug url (http://example.smugmug.com) instead of your custom SmugMug URL.', 'size' => 40 ),
            'default_images_number' => array( 'name' => 'smw_default_images_number', 'title' => __('Number of Images', 'smw'), 'type' => 'text', 'description' => 'Default number of images to show in your galleries.', 'size' => 10 ),
            'default_thumb_size' => array( 'name' => 'smw_default_thumb_size', 'options' => $default_thumb_sizes, 'title' => __('Default Thumbnail Size', 'smw'), 'type' => 'select' ),
            'default_viewable_size' => array( 'name' => 'smw_default_viewable_size', 'options' => $default_viewable_sizes, 'title' => __('Default Lightbox Size', 'smw'), 'type' => 'select' ),
            'default_gallery_ajax' => array( 'name' => 'smw_default_galery_ajax', 'options' => $default_ajax, 'title' => __('Default Gallery Ajax', 'smw'), 'type' => 'select', 'description' => 'Change this if you are having issues with the ajax on the frontend.' ),
            'default_video_size' => array( 'name' => 'smw_default_video_size', 'options' => $default_video_sizes, 'title' => __('Default Video Size', 'smw'), 'type' => 'select', 'description' => 'The number is the number of pixels to the longest side.' ),
            'jquery_frontend' => array( 'name' => 'smw_jquery_frontend', 'title' => __('Enable jQuery on Frontend', 'smw'), 'type' => 'boolean', 'description' => 'This is loads the jQuery script included in Wordpress on the Frontend. Disable if there are conflicts.' ),
            'caption_frontend' => array( 'name' => 'smw_caption_frontend', 'title' => __('Enable Image Captions', 'smw'), 'type' => 'boolean', 'description' => 'Set to NO if you need to disable your captions on the frontend.' ),
            

        );

        return apply_filters( 'smw_post_meta_boxes', $meta_boxes );
    }
    
    /**
     * Returns an array of form fields to be used in the Lightbox settings section
     * 
     * @return array Form fields to be used in the Lightbox settings section 
     */
    
    function lightboxBoxes() {
        $pretty_photo_styles = array(
            array( 'name' => 'Default', 'value' => 'pp_default' ),
            array( 'name' => 'Light Rounded', 'value' => 'light_rounded' ),
            //array( 'name' => 'Dark Rounded', 'value' => 'dark_rounded' ),
            array( 'name' => 'Light Square', 'value' => 'light_square' ),
            array( 'name' => 'Dark Square', 'value' => 'dark_square' ),
            array( 'name' => 'Facebook', 'value' => 'facebook' )
        );
        $animation_speeds = array(
            array( 'name' => 'Fast', 'value' => 'fast' ),
            array( 'name' => 'Normal', 'value' => 'normal' ),
            array( 'name' => 'Slow', 'value' => 'slow' )
        );
        $opacity = array(
            array( 'name' => '1.0', 'value' => '1.0' ),
            array( 'name' => '0.9', 'value' => '0.9' ),
            array( 'name' => '0.8', 'value' => '0.8' ),
            array( 'name' => '0.7', 'value' => '0.7' ),
            array( 'name' => '0.6', 'value' => '0.6' ),
            array( 'name' => '0.5', 'value' => '0.5' ),
            array( 'name' => '0.4', 'value' => '0.4' ),
            array( 'name' => '0.3', 'value' => '0.3' ),
            array( 'name' => '0.2', 'value' => '0.2' ),
            array( 'name' => '0.1', 'value' => '0.1' ),
            array( 'name' => '0.0', 'value' => '0.0' )
        );
        $lightbox_types = array(
            array( 'name' => 'prettyPhoto', 'value' => 'prettyphoto', 'description' => 'This is the default lightbox. You can see examples of this lightbox <a href="http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/" target="_blank">HERE</a>. Supports Video and Images.' ),
            array( 'name' => 'clearbox', 'value' => 'clearbox', 'description' => 'This is an alternative lightbox. You can see examples of this lightbox <a href="http://www.clearbox.hu/index_en.html" target="_blank">HERE</a>. Supports Video and Images.' ),
        );
        /* Array of the meta box options. */
        $meta_boxes = array(
           'lightbox_type' => array( 'name' => 'smw_lightbox_type', 'options' => $lightbox_types, 'title' => __('Lightbox Type', 'smw'), 'type' => 'radio' ),
           'pretty_photo_template' => array( 'name' => 'smw_lightbox_template', 'options' => $pretty_photo_styles, 'title' => __('Lightbox Template', 'smw'), 'type' => 'select', 'section_class' => 'prettyPhoto_settings' ),
           'pretty_photo_deeplinking' => array( 'name' => 'smw_lightbox_deeplinking', 'title' => __('Enable Deep Links', 'smw'), 'type' => 'boolean', 'description' => 'If set to yes, you will be able to directly link to the lightbox version of images.', 'section_class' => 'prettyPhoto_settings' ),
           'pretty_photo_overlay' => array( 'name' => 'smw_lightbox_overlay', 'title' => __('Overlay Gallery', 'smw'), 'type' => 'boolean', 'description' => 'If set to true, a gallery will overlay the fullscreen image on mouse over.', 'section_class' => 'prettyPhoto_settings' ),
           'pretty_photo_autoplay_slideshow' => array( 'name' => 'smw_lightbox_auto_slide', 'title' => __('Autoplay Slideshow', 'smw'), 'type' => 'boolean', 'section_class' => 'prettyPhoto_settings' ),
            'pretty_photo_keyboard_shortcuts' => array( 'name' => 'smw_lightbox_keyboard_shortcuts', 'title' => __('Keyboard Shortcuts', 'smw'), 'type' => 'boolean', 'section_class' => 'prettyPhoto_settings' ),
           'pretty_photo_allow_resize' => array( 'name' => 'smw_lightbox_allow_resize', 'title' => __('Allow Resize', 'smw'), 'type' => 'boolean', 'description' => 'Resize the photos bigger than viewport.', 'section_class' => 'prettyPhoto_settings' ),
           'pretty_photo_animation' => array( 'name' => 'smw_lightbox_animation', 'options' => $animation_speeds, 'title' => __('Animation Speed', 'smw'), 'type' => 'select', 'section_class' => 'prettyPhoto_settings' ),
           'pretty_photo_slideshow' => array( 'name' => 'smw_lightbox_slideshow', 'title' => __('Slideshow Speed', 'smw'), 'type' => 'text', 'description' => 'Amount of time between pictures in slideshow in miliseconds.', 'section_class' => 'prettyPhoto_settings' ),
           'pretty_photo_show_title' => array( 'name' => 'smw_lightbox_show_title', 'title' => __('Show Title', 'smw'), 'type' => 'boolean', 'section_class' => 'prettyPhoto_settings' ),
           'pretty_photo_opacity' => array( 'name' => 'smw_lightbox_opacity', 'options' => $opacity, 'title' => __('Slideshow Speed', 'smw'), 'type' => 'select', 'section_class' => 'prettyPhoto_settings' ),
            //'album' => array( 'name' => 'smw_album_id_key', 'options' => $this->albums, 'title' => __('Album', 'smw'), 'type' => 'select' ),
            //'album_template' => array( 'name' => 'smw_album_template', 'options' => $this->templates, 'title' => __('Album Template', 'smw'), 'type' => 'select' ),

        );

        return apply_filters( 'smw_post_meta_boxes', $meta_boxes );
    }
    
    /**
     * Ajax function for any of the settings section 
     * 
     * @return void
     */
    
    function settingsAjax() {
        
        $posted_data = explode("&", $_POST['form_data']);
        
        $this->forms->save_form_ajax($posted_data);
        
        //if($form_save) {
            //echo '<div id="message" class="error"><p><strong>Update Didn\'t Work</strong></p><p>Your settings weren\'t saved.</p></div>';
        //} else {
            echo '<div id="message" class="updated"><p><strong>Success!</strong></p><p>Your '.$_POST['type'].' settings have been saved.</p></div>';
        //}
        
        die();
    }
}
