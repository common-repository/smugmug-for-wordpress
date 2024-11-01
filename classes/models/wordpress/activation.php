<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of activation
 *
 * @author anthony.humes
 */
class SMW_Models_Wordpress_Activation extends SMW_Models_Wordpress {
    private static $instance;
    private function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->galleries = SMW::getModel( 'service/gallery-comms' );
        $this->images = SMW::getModel( 'service/image-comms' );
        $this->categories = SMW::getModel( 'service/category-comms' );
        $this->wp_categories = $this->getDb( 'categories' );
        $this->wp_images = $this->getDb( 'images' );
        $this->wp_subcategories = $this->getDb( 'subcategories' );
        $this->wp_galleries = $this->getDb( 'galleries' );
        $this->subcategories = SMW::getModel( 'service/sub-category-comms' );
        
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    
    public function getServiceUrl() {
        $url = SMW::getHelper('url');
        $galleries = SMW::getModel('service/gallery-comms');
        $service = $galleries->getItems( NULL, false, array( 'Extras' => 'URL' ));
        
        $service_url = $url->parse($service[0]['URL'], 'no-http');
        return $service_url;
    }
    
    public function getDefault() {
        
        $service_url = $this->getServiceUrl();
        
        $default = array(
            'smw_service_url' => $service_url,
            'smw_default_service_url' => $service_url,
            'smw_lightbox_type' => 'prettyphoto',
            'smw_jquery_frontend' => 1,
            'smw_caption_frontend' => 1,
            'smw_default_video_size' => 'video640_url',
            'smw_default_viewable_size' => 'large_url',
            'smw_default_thumb_size' => 'thumb_url',
            'smw_default_images_number' => 12,
            'smw_default_galery_ajax' => 'ajax',
            'smw_lightbox_template' => 'pp_default',
            'smw_lightbox_overlay' => 1,
            'smw_lightbox_auto_slide' => 0,
            'smw_lightbox_keyboard_shortcuts' => 1,
            'smw_lightbox_allow_resize' => 1,
            'smw_lightbox_animation' => 'fast',
            'smw_lightbox_slideshow' => '5000',
            'smw_lightbox_show_title' => 1,
            'smw_lightbox_opacity' => '0.8',
            'smw_db_version' => SMW_DB_VERSION,
            'smw_post_type_slug' => 'galleries',
            'smw_post_type_name' => 'SmugMug Galleries',
            'smw_post_type_singular_name' => 'SmugMug Gallery',
            'smw_post_type_add_new' => 'Add Gallery',
            'smw_post_type_view_item' => 'View Gallery',
            'smw_post_type_search_items' => 'Search Galleries',
            'smw_post_type_add_new_items' => 'Add New Gallery',
            'smw_post_type_edit_item' => 'Edit Gallery',
            'smw_post_type_menu_item' => 'SM Galleries'
        );
        return $default;
    }
    
    public function activatePlugin() {
        $galleries = $this->galleries->getItems( NULL, true );
        $this->wp_categories->itemTable();
        $this->wp_subcategories->itemTable();
        $this->wp_galleries->itemTable( $galleries );
        $this->wp_images->itemTable( $galleries );
        $this->setDefault();
	
        $db_version = get_option( 'smw_db_version' );
        if(!$db_version) {
            add_option( "smw_db_version", SMW_DB_VERSION );
        } elseif( $db_version != SMW_DB_VERSION) {
            update_option( 'smw_db_version', SMW_DB_VERSION );
        }
        
    }
    public function upgradeDatabase() {
        
        $old_options = array('smp_default_smugmug_url' => 'smw_default_service_url',
                'smp_lightbox_type' => 'smw_lightbox_type',
                'smp_jquery_frontend' => 'smw_jquery_frontend',
                'smp_caption_frontend' => 'smw_caption_frontend',
                'smp_default_video_size' => 'smw_default_video_size',
                'smp_default_viewable_size' => 'smw_default_viewable_size',
                'smp_default_thumb_size' => 'smw_default_thumb_size',
                'smp_default_images_number' => 'smw_default_images_number',
                'smp_lightbox_template' => 'smw_lightbox_template',
                'smp_lightbox_overlay' => 'smw_lightbox_overlay',
                'smp_lightbox_auto_slide' => 'smw_lightbox_auto_slide',
                'smp_lightbox_keyboard_shortcuts' => 'smw_lightbox_keyboard_shortcuts',
                'smp_lightbox_allow_resize' => 'smw_lightbox_allow_resize',
                'smp_lightbox_animation' => 'smw_lightbox_animation',
                'smp_lightbox_slideshow' => 'smw_lightbox_slideshow',
                'smp_lightbox_show_title' => 'smw_lightbox_show_title',
                'smp_lightbox_opacity' => 'smw_lightbox_opacity',
                'smp_post_type_slug' => 'smw_post_type_slug',
                'smp_post_type_name' => 'smw_post_type_name',
                'smp_post_type_singular_name' => 'smw_post_type_singular_name',
                'smp_post_type_add_new' => 'smw_post_type_add_new',
                'smp_post_type_view_item' => 'smw_post_type_view_item',
                'smp_post_type_search_items' => 'smw_post_type_search_items',
                'smp_post_type_add_new_items' => 'smw_post_type_add_new_items',
                'smp_post_type_edit_item' => 'smw_post_type_edit_item',
                'smp_post_type_menu_item' => 'smw_post_type_menu_item'
        );
        
        $validate_var = get_option('smp_default_smugmug_url');
        
        $default_options = $this->getDefault();
        
        //if($validate_var) {
            foreach($old_options as $old_option => $new_option) {
                $current_option = get_option($old_option);
                $add_option = get_option($new_option);
                if(!$current_option) {
                    if( !add_option($new_option,$default_options[$new_option]) ) {
                        return false;
                    }
                } else {
                    if(!$add_option) {
                        if( !add_option($new_option,$current_option) ) {
                            return false;
                        }
                        if( !delete_option($old_option) ) {
                            return false;
                        }
                    }
                    
                }
                
            }
            return true;
        //}
        
        $db_version = get_option( 'smw_db_version' );
        if(!$db_version) {
            add_option( "smw_db_version", SMW_DB_VERSION );
        } elseif( $db_version != SMW_DB_VERSION) {
            update_option( 'smw_db_version', SMW_DB_VERSION );
        }
        
        return $response;
    }
    public function setDefault() {
        $default_options = $this->getDefault( $service_url );
        
        foreach($default_options as $name => $value) {
            $current_value = get_option($name);
            if(!$current_value) {
                add_option($name,$value);
            }
        }
    }
}
