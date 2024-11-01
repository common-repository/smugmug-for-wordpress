<?php
/**
 * SMW_Helpers_AddAjaxOptions - Adds the 
 * 
 * @package SMW
 * @subpackage Helpers
 * @author  Anthony Humes
 **/
class SMW_Helpers_AddAjaxOptions {
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object|SMW_Controllers_Admin_Galleries_Edit
     * @see SMW::getHelper()
     */
    private static $instance;
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Helpers_AddAjaxOptions
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @see     SMW_Controllers_Items_Type_Galleries
     * @see     SMW_Controllers_Items_Type_SubCategories
     * @see     SMW_Controllers_Items_Type_Categories
     * @see     SMW_Controllers_Items_Type_Images
     * @see     SMW_Controllers_Admin_Settings
     * @return  void
     */
    private function __construct() {
        $images = SMW::getItem('images');
        $galleries = SMW::getItem('galleries');
        $categories = SMW::getItem('categories');
        $subcategories = SMW::getItem('sub-categories');
        $settings = SMW::getControl('admin/settings');
        $frontend = SMW::getFrontend('galleries');
        add_action('wp_ajax_remove_auth',array('SMW_Views_Admin_Settings','remove_auth_callback'));
        add_action('wp_ajax_settingsAjax',array($settings,'settingsAjax'));
        add_action('wp_ajax_subcategories_get',array(&$subcategories,'subcategories_get_callback'));
        add_action('wp_ajax_gallery_create',array('SMW_Controllers_Admin_Galleries_Create','gallery_create_callback'));
        add_action('wp_ajax_exclude_galleries',array(&$galleries,'excludeGalleries'));
        add_action('wp_ajax_caption_save',array(&$images,'caption_save_callback'));
        add_action('wp_ajax_delete_gallery',array(&$galleries,'delete_gallery_callback'));
        add_action('wp_ajax_add_gallery',array(&$galleries,'add_gallery_callback'));
        add_action('wp_ajax_syncAllGalleries',array(&$galleries,'syncAllGalleries_callback'));
        add_action('wp_ajax_addGalleries',array(&$galleries,'addGalleries'));
        add_action('wp_ajax_excludeAllGalleries',array(&$galleries,'excludeAllGalleries_callback'));
        add_action('wp_ajax_getGalleries',array(&$galleries,'getGallery_callback'));
        add_action('wp_ajax_imagesAjax',array(&$images,'imagesAjax'));
        add_action('wp_ajax_editAjax',array(&$images,'editAjax'));
        add_action('wp_ajax_hiddenAjax',array(&$images,'hiddenAjax'));
        add_action('wp_ajax_editGallery',array(&$galleries,'editGalleryAjax'));
        add_action('wp_ajax_imageHide',array(&$images,'imageHideAjax'));
        add_action('wp_ajax_imageEXIF',array(&$images,'imageEXIFAjax'));
        add_action('wp_ajax_categoryChange',array(&$categories,'categoryAdminChange'));
        add_action('wp_ajax_subCategoryChange',array(&$subcategories,'subCategoryAdminChange'));
        add_action('wp_ajax_subCategoryChange',array(&$subcategories,'subCategoryAdminChange'));
        add_action('wp_ajax_galleryGet',array(&$frontend,'galleryAjax'));
        add_action('wp_ajax_nopriv_galleryGet',array(&$frontend,'galleryAjax'));
        
    }
}
