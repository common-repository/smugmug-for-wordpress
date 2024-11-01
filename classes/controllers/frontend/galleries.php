<?php

/**
 * SMW_Controllers_Frontend_Page - Controls the admin settings page
 * 
 * @package SMW_Controllers
 * @subpackage Frontend
 * @author  Anthony Humes
 **/
class SMW_Controllers_Frontend_Galleries {
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object|SMW_Controllers_Frontend_Galleries
     * @see SMW::getHelper()
     */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @see     SMW_Controllers_Items_Type_Images
     * @see     SMW_Controllers_Items_Type_Galleries
     * @return  void
     */
    private function __construct() {
        $this->imageDb = SMW::getItem('images');
        $this->galleryDb = SMW::getItem('galleries');
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Frontend_Galleries
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function galleryAjax() {
        $this->render($_POST['id'],$_POST['page_number']);
        die();
    }
    /**
     * Starts the render gallery
     * 
     * @access  public
     * @param   int $id The id of the gallery to post
     * @return  html
     */
    public function render( $id, $page = NULL ) {
        $post_custom = get_post_custom($id);
        
        $gallery_info['id']             = $post_custom['smw_gallery_id'][0];
        $gallery_info['template']       = $post_custom['smw_gallery_template'][0];
        $gallery_info['passworded']     = $this->galleryDb->isPassworded( $gallery_info['id'] );
        $gallery_info['password']       = $this->galleryDb->getPassword( $gallery_info['id'] );
        $gallery_info['password_hint']  = $this->galleryDb->getPasswordHint( $gallery_info['id'] );
        $gallery_info['post_id']        = $id;
        if($page)
            $gallery_info['current_page'] = $page;
        
        if(!empty($post_custom['smw_number_of_images'][0])) {
                $gallery_info['number_of_images'] = $post_custom['smw_number_of_images'][0];
        } else {
                $gallery_info['number_of_images'] = get_option('smw_default_images_number');
        }

        if(!empty($post_custom['smw_thumb_size'][0])) {
                $gallery_info['thumb_size'] = $post_custom['smw_thumb_size'][0];
        } else {
                $gallery_info['thumb_size'] = get_option('smw_default_thumb_size');
        }

        if(!empty($post_custom['smw_viewable_size'][0])) {
                $gallery_info['viewable_size'] = $post_custom['smw_viewable_size'][0];
        } else {
                $gallery_info['viewable_size'] = get_option('smw_default_viewable_size');
        }

        if(!empty($post_custom['smw_video_size'][0])) {
                $gallery_info['video_size'] = $post_custom['smw_video_size'][0];
        } else {
                $gallery_info['video_size'] = get_option('smw_default_video_size');
        }
        
        //print_r($gallery_info);
        
        $session_var = 'password_' . $gallery_info['id'];
        
        //print_r($_SESSION[$session_var]);
        
        if($_POST['password']) {
            if($_POST['password'] == $gallery_info['password']) {
                $_SESSION[$session_var] = $gallery_info['id'];
            } else {
                echo '<p>Your password didn\'t match the Album\'s password. Please enter the correct password.</p>';
            }
        }
        
        if($gallery_info['passworded'] == 1) {
            if($_SESSION[$session_var] == $gallery_info['id']) {
                $this->galleryRender( $gallery_info );
            } else {
                $this->hint = $gallery_info['password_hint'];
                SMW::getTemplate('password-fields');
            }
            
        } else {
            
            $this->galleryRender( $gallery_info );
        }
    }
    /**
     * Renders a gallery for the frontend
     * 
     * @access  public
     * @param   array $gallery_info The gallery to be rendered
     * @return  html
     */
    public function galleryRender( $gallery_info ) {
        global $post;
        $post->ID ? $gallery_info['post_id'] = $post->ID : $gallery_info['post_id'] = $gallery_info['post_id'];
        $buy_links = SMW::getHelper('buy-links');
        
        $image_count = $this->imageDb->getCount( $gallery_info['id'] );
        if($gallery_info['current_page']) {
            $current_page = $gallery_info['current_page'];
        } else {
            if(get_query_var('paged') != 0) {
                $current_page = get_query_var('paged');
            } else {
                $current_page = 1;
            }
        }
        
        $this->pagination = new SMW_Helpers_Pagination($current_page, $gallery_info['number_of_images'], $image_count);
        $image_info = array(
            'id' => $gallery_info['id'],
            'number' => $gallery_info['number_of_images'],
            'offset' => $this->pagination->offset(),
            'rows' => $gallery_info['thumb_size'] . ', ' . $gallery_info['viewable_size'] . ', ' . $gallery_info['video_size'] . ', caption, height, width, gallery_id, gallery_key, image_id, image_key'
        );
        
        $images_data = $this->imageDb->getPage( $image_info );
        
        $this->gallery_buy_url = $buy_links->gallery($images_data[0]['gallery_id'],$images_data[0]['gallery_key']);
        foreach($images_data as $key => $image_data) {
            $this->images[$key] = $this->imageDb->itemDisplay( $image_data, $gallery_info['thumb_size'], $gallery_info['viewable_size'], $gallery_info['video_size'] );
        }
        
        $gallery_info['template'] == 'default' ? $gallery_info['template'] = 'gallery' : '';
        
        print_r($this->images);
        
        if($gallery_info['shortcode']) {
            return SMW::getTemplate('galleries/' . $gallery_info['template'], true);
        } else {
            echo '<div id="smw_gallery_id" post_id="'.$gallery_info['post_id'].'">&nbsp;</div>';
            SMW::getTemplate('galleries/' . $gallery_info['template']);
        }
        
        
        
    }
}
