<?php

/**
 * SMW_Controllers_Admin_CustomPostType_Forms - Controls the Forms which show on the custom post type
 *
 * @package SMW_Controllers_Admin
 * @subpackage CustomPostType
 * @author Anthony Humes
 **/
class SMW_Controllers_Admin_CustomPostType_Forms {
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object|SMW_Controllers_Admin_CustomPostType_Forms
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
        $this->galleries = SMW::getItem('galleries');
        $this->templates = SMW::getHelper('templates');
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Admin_CustomPostType_Forms
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Echos the meta boxes for the page
     * 
     * @return void
     */
    function pageMetaBoxes() {
        global $post;
        $forms = SMW::getHelper('forms');
        $this->post = $post;

        $meta_boxes = $this->galleryPostFields();
        
        $forms->createFormTable($meta_boxes,2,true);
    }
    /**
     * Returns an array of form fields to be used in the Custom Post Type page meta options
     * 
     * @return array Form fields to be used in the Custom Post Type page meta options
     */
    function galleryPostFields() {
        global $post;
        $galleries = $this->galleries->getAllForms();
        if($galleries) {
            array_unshift($galleries,array('name' => 'Select Gallery', 'value' => ''));
        }
        
        $templates = $this->templates->getTemplates(SMW_TEMPLATE_DIR,'gallery','Gallery Template Name');
        array_unshift($templates,array('name' => 'Default', 'value' => 'default'));
        $page_templates = $this->templates->getTemplates(SMW_TEMPLATE_DIR,'page','SmugMug Template Name');
        array_unshift($page_templates,array('name' => 'Default', 'value' => 'default'));
        $thumb_sizes = array(
            array( 'name' => 'Thumb', 'value' => 'thumb_url' ),
            array( 'name' => 'Tiny', 'value' => 'tiny_url' ),
            array( 'name' => 'Small', 'value' => 'small_url' ),
            array( 'name' => 'Medium', 'value' => 'medium_url' ),
        );
        $viewable_sizes = array(
            array( 'name' => 'Large', 'value' => 'large_url' ),
            array( 'name' => 'Extra Large', 'value' => 'xlarge_url' ),
            array( 'name' => 'Extra Large 2', 'value' => 'x2large_url' ),
            array( 'name' => 'Extra Large 3', 'value' => 'x3large_url' ),
            array( 'name' => 'Original', 'value' => 'original_url' ),
        );
        $video_sizes = array(
            array( 'name' => '320', 'value' => 'video320_url' ),
            array( 'name' => '640', 'value' => 'video640_url' ),
            array( 'name' => '960', 'value' => 'video960_url' ),
            array( 'name' => '1280', 'value' => 'video1280_url' ),
            array( 'name' => '1920', 'value' => 'video1920_url' ),
        );
        $ajax = array(
            array( 'name' => 'Ajax', 'value' => 'ajax' ),
            array( 'name' => 'Static', 'value' => 'static' )
        );
        /* Array of the meta box options. */
        $meta_boxes = array(
            'page_template' => array( 'name' => 'smw_custom_post_template', 'options' => $page_templates, 'title' => __('Page Template', 'smw'), 'type' => 'select' ),
            'gallery' => array( 'name' => 'smw_gallery_id', 'options' => $galleries, 'title' => __('Gallery', 'smw'), 'type' => 'select' ),
            'gallery_template' => array( 'name' => 'smw_gallery_template', 'options' => $templates, 'title' => __('Gallery Template', 'smw'), 'type' => 'select' ),
            'thumb_size' => array( 'name' => 'smw_thumb_size', 'options' => $thumb_sizes, 'title' => __('Thumbnail Size', 'smw'), 'type' => 'select','default_value' => get_option('smw_default_thumb_size') ),
            'viewable_size' => array( 'name' => 'smw_viewable_size', 'options' => $viewable_sizes, 'title' => __('Lightbox Image Size', 'smw'), 'type' => 'select','default_value' => get_option('smw_default_viewable_size') ),
            'video_size' => array( 'name' => 'smw_video_size', 'options' => $video_sizes, 'title' => __('Video Size', 'smw'), 'type' => 'select','default_value' => get_option('smw_default_video_size') ),
            'ajax' => array( 'name' => 'smw_ajax', 'options' => $ajax, 'title' => __('Use Ajax', 'smw'), 'type' => 'select','default_value' => get_option('smw_default_galery_ajax') ),
            'number_of_images' => array( 'name' => 'smw_number_of_images', 'title' => __('Number of Images per Page', 'smw'), 'type' => 'text','default_value' => get_option('smw_default_images_number') ),
        );
        
        return apply_filters( 'smw_post_meta_boxes', $meta_boxes );
    }
}