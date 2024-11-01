<?php

/**
 * SMW_Controllers_Admin_ShortCodes - A class that provides shortcodes for SmugMug for Wordpress
 * 
 * @package SMW_Controllers
 * @subpackage Admin
 * @author Anthony Humes
 **/
class SMW_Controllers_Admin_ShortCodes {
    /**
     * This variable is set in __construct and provides access to the templates helper
     * 
     * @access private
     * @var Object
     */
    private $templates;
    /**
     * This variable is set in __construct and provides access to the templates helper
     * 
     * @access private
     * @var Object
     */
    private $galleries;
    /**
     * This variable is set in __construct and provides access to the image item model
     * 
     * @access private
     * @var Object
     */
    private $imageDb;
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object
     */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @return  void
     */
    private function __construct() {
        $this->templates = SMW::getHelper('templates');
        $this->galleries = SMW::getFrontend('galleries');
        $this->imageDb = SMW::getItem('images');
    }
    /**
     * Used with the Singleton design pattern to return a 
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Admin_ShortCodes
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Runs the functions to create Wordpress shortcodes
     * 
     * @access public
     * @return void 
     */
    public function createShortcodes() {
        add_shortcode('smugmug_gallery',array($this,'smw_gallery_handler'));
        add_shortcode('smugmug_image',array($this,'smw_image_handler'));
        add_shortcode('smugmug_buy',array($this,'smw_buy_handler'));
    }
    /**
     * Function that creates the shortcode [smugmug_gallery]
     * 
     * @param array $atts Passes attributes to the shortcode function
     * @return void 
     */
    function smw_gallery_handler( $atts ) {
        
        extract( shortcode_atts(array(
            'id' => '',
            'number_of_images' => 12,
            'template' => 'default',
            'thumb_size'=> 'thumb',
            'lightbox_size' => 'large',
            'video_size' => '640'
        ), $atts) );
        if($id != '') {
            $thumb_size = strtolower($thumb_size);
            $lightbox_size = strtolower($lightbox_size);
            $gallery_info = array(
                'id' => $id,
                'template' => $template,
                'number_of_images' => $number_of_images,
                'thumb_size' => $this->image_size($thumb_size),
                'viewable_size' => $this->image_size($lightbox_size),
                'video_size' => $this->video_size($video_size),
                'shortcode' => true
            );
            return $this->galleries->galleryRender( $gallery_info );
        } else {
            return '<p>No album seletected.</p>';
        }
    }
    /**
     * Parser that takes a number and converts it to a database field name for video size
     * 
     * @param integer $video_size Passes attributes to the shortcode function
     * @return string The database field name for the video size 
     */
    function video_size($video_size) {
        switch($video_size) {
            case '320':
                $video_size = 'video320_url';
                break;
            case '640':
                $video_size = 'video640_url';
                break;
            case '960':
                $video_size = 'video920_url';
                break;
            case '1280':
                $video_size = 'video1280_url';
                break;
            case '1920':
                $video_size = 'video1920_url';
                break;
        }
        return $video_size;
    }
    /**
     * Parser that takes a size and converts it to a database field name for image size
     * 
     * @param string $image_size
     * @return string
     */
    function image_size($image_size) {
        switch($image_size) {
            case 'thumb':
                $image_size = 'thumb_url';
                break;
            case 'tiny':
                $image_size = 'tiny_url';
                break;
            case 'small':
                $image_size = 'small_url';
                break;
            case 'medium':
                $image_size = 'medium_url';
                break;
            case 'large':
                $image_size = 'large_url';
                break;
            case 'xlarge':
                $image_size = 'xlarge_url';
                break;
            case 'x2large':
                $image_size = 'x2large_url';
                break;
            case 'x3large':
                $image_size = 'x3large_url';
                break;
            case 'original':
                $image_size = 'original_url';
                break;
        }
        return $image_size;
    }
    /**
     * Function that creates the shortcode [smugmug_image]
     * 
     * @param array $atts Passes attributes to the shortcode function
     * @return void 
     */
    function smw_image_handler( $atts ) {
        extract( shortcode_atts(array(
            'id' => '',
            'lightbox' => 'no',
            'image_size'=> 'small',
            'video_size'=> '640',
            'lightbox_size' => 'large',
            'return_url' => 'no'
        ), $atts) );
        
        $image_size = strtolower($image_size);
        $lightbox_size = strtolower($lightbox_size);
        
        $image_size = $this->image_size($image_size);
        $video_size = $this->video_size($video_size);
        $lightbox_size = $this->image_size($lightbox_size);
        
        $image = $this->imageDb->getItem( $id );
        $image = $this->imageDb->itemDisplay( $image, $image_size, $lightbox_size, $video_size );
        //$caption = $this->get_caption($image['Caption']);
        $lightbox = strtolower($lightbox);
        $return_url = strtolower($return_url);
        
        if($return_url == 'no') {
            if($lightbox == 'no') {
                return '<img src="'.$image['ThumbURL'].'" alt="' . $caption .'" />';
            } else {
                return '<a href="'.$image['UsableURL'].'" '.$image['Lightbox'].'><img src="'.$image['ThumbURL'].'" /></a>';
            }
        } else {
            return $image['ThumbURL'];
        }
    }
    /**
     * Function that creates the shortcode [smugmug_buy]
     * 
     * @param array $atts Passes attributes to the shortcode function
     * @return void 
     */
    function smw_buy_handler( $atts ) {
        $buy_links = SMW::getHelper('buy-links');
        extract( shortcode_atts(array(
            'id' => '',
            'type' => 'image',
            'return_url' => 'no',
            'link_text' => 'Buy at SmugMug'
        ), $atts) );
        
        $return_url = strtolower($return_url);
        
        if($return_url == 'no') {
            if($type == 'image') {
                $image = $image = $this->imageDb->getItem( $id );
                $buy_link = $buy_links->image( $image );
                return '<a href="'.$buy_link.'" title="'.$link_text.'" target="_blank">'.$link_text.'</a>';
            } elseif($type == 'gallery') {
                $buy_link = $buy_links->gallery( $id );
                return '<a href="'.$buy_link.'" title="'.$link_text.'" target="_blank">'.$link_text.'</a>'; 
            } else {
                return '<p><strong>Type isn\'t allowed! Only use "image" or "gallery"</strong></p>';
            }
        } else {
            if($type == 'image') {
                $image = $image = $this->imageDb->getItem( $id );
                $buy_link = $buy_links->image($image);
                return $buy_link;
            } elseif($type == 'gallery') {
                $buy_link = $buy_links->gallery( $id );
                return $buy_link;
            } else {
                return '<p><strong>Type isn\'t allowed! Only use "image" or "album"</strong></p>';
            }
        }
    }
    
}