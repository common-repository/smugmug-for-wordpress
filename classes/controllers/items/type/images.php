<?php

/**
 * SMW_Controllers_Items_Type_Images - Controls the images item type
 * 
 * @package SMW_Controllers_Items
 * @subpackage Type
 * @author Anthony Humes
 **/
class SMW_Controllers_Items_Type_Images extends SMW_Controllers_Items_Item {
    /**
     * This variable is set in imagesAjax() and provides the current page
     * 
     * @access  protected
     * @see     SMW_Controllers_Items_Type_Images::imagesAjax()
     * @var     int The current page
     */
    protected $currentPage;
    /**
     * This variable is set in imagesAjax() and provides access to the pagination helper
     * 
     * @access  protected
     * @see     SMW_Controllers_Items_Type_Images::imagesAjax()
     * @var     Object|SMW_Helpers_Pagination
     */
    protected $pagination;
    /**
     * This variable is set in imagesAjax() and provides the total number of pages
     * 
     * @access  protected
     * @see     SMW_Controllers_Items_Type_Images::imagesAjax()
     * @var     int The total number of pages
     */
    protected $totalPages;
    /**
     * This variable is set in imagesAjax() and provides the current offset
     * 
     * @access  protected
     * @see     SMW_Controllers_Items_Type_Images::imagesAjax()
     * @var     int The current offset
     */
    protected $offset;
    /**
     * This variable is set in imagesAjax() and provides the total number of pages
     * 
     * @access  protected
     * @see     SMW_Controllers_Items_Type_Images::imagesAjax()
     * @var     array The array of images to be used in the pagination
     */
    protected $paged_images;
    /**
     * This variable is set in __construct and provides access to the Images model
     * 
     * @access protected
     * @var Object|SMW_Models_Wordpress_Items_Images
     */
    protected $db;
    /**
     * This variable is set in __construct and provides access to the buy links helper
     * 
     * @access protected
     * @var Object|SMW_Helpers_BuyLinks
     */
    protected $buy_links;
    /**
     * This variable is set in __construct and provides access to the lightbox helper
     * 
     * @access protected
     * @var Object|SMW_Helpers_Lightbox
     */
    protected $lightbox;
    /**
     * This variable is set in __construct and provides access to the url helper
     * 
     * @access protected
     * @var Object|SMW_Helpers_Url
     */
    protected $url;
    /**
    * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
    * 
    * @access private
    * @var Object|SMW_Controllers_Items_Type_Images
    */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @see     SMW::getHelper()
     * @see     SMW_Controllers_Abstract::getItemDb()
     * @see     SMW_Models_Items_Type_Images
     * @see     SMW_Helpers_Url
     * @see     SMW_Helpers_Lightbox
     * @see     SMW_Helpers_BuyLinks
     * @return  void
     */
    private function __construct() {
        $this->db = $this->getItemDb('images');
        $this->buy_links = SMW::getHelper('buy-links');
        $this->lightbox = SMW::getHelper('lightbox');
        $this->url = SMW::getHelper('url');
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Items_Type_Images
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Returns an array which has all of the data needed to make an image show up
     * 
     * @param array $image The image array that comes from the WP database
     * @param string $thumb_size The size of the thumbnail for the image. Defaults to 'thumb_url'
     * @param string $viewable_size The size of the viewable(lightbox) version of the image. Defaults to 'large_url'
     * @param string $video_size The size of the video. Defaults to 'video640_url'
     * @see SMW_Controllers_Items_Type_Images::getCaption()
     * @see SMW_Controllers_Items_Type_Images::height_width()
     * @see SMW_Helpers_Url::finalURL()
     * @see SMW_Helpers_Url::videoURL()
     * @see SMW_Helpers_BuyLinks::image()
     * @see SMW_Helpers_Lightbox::code()
     * @return array An array which holds the right format for an image to be posted
     */
    public function itemDisplay( $image, $thumb_size = 'thumb_url', $viewable_size = 'large_url', $video_size = 'video640_url') {
        $new_image['Caption'] = $this->getCaption($image['caption']);
        $new_image['ThumbURL'] = $this->url->finalURL($image[$thumb_size]);
        $new_image['BuyLink'] = $this->buy_links->image( $image );
        
        if($image[$video_size]) {
            $wh = $this->height_width($image['width'],$image['height'],$video_size);
            $new_image['Height'] = $wh['height'];
            $new_image['Width'] = $wh['width'];
            $new_image['Type'] = 'video';
            $new_image['UsableURL'] = $this->url->videoURL($image,$wh['width'],$wh['height'],$video_size);
        } else {
            $new_image['Type'] = 'image';
            $new_image['UsableURL'] = $this->url->finalURL($image[$viewable_size]);
        }
        $new_image['Lightbox'] = $this->lightbox->code('images', $new_image );
        return $new_image;
    }
    /**
     * Returns a formatted caption
     * 
     * @param string $caption The caption from the database
     * @return string A string which holds the formated caption
     */
    public function getCaption( $caption ) {
        if(get_option('smw_caption_frontend') == 0) {
            $caption_final = '';
        } else {
            $caption_final = htmlspecialchars(stripcslashes($caption),ENT_QUOTES);
        }
        return $caption_final;
    }
    /**
     * Creates a ratio of the width and height based on the video size
     * 
     * @access public
     * @param int $width The video width from the database
     * @param int $height The video height from the database
     * @param string $video_size The size of the video
     * @return array An array that holds the height and width of the video
     */
    public function height_width($width, $height, $video_size) {
        $video_size = str_replace(array('video','_url'),'',$video_size);
        $hw_var['width'] = $width;
        $hw_var['height'] = $height;

        if($hw_var['width'] >= $hw_var['height']) {
            $hw_ratio = $video_size/$hw_var['width'];
            $hw_final['width'] = $video_size;
            $hw_final['height'] = number_format($hw_var['height'] * $hw_ratio,0);
        } else {
            $hw_ratio = $video_size/$hw_var['height'];
            $hw_final['height'] = $video_size;
            $hw_final['width'] = number_format($hw_var['width'] * $hw_ratio,0);
        }
        return $hw_final;
    }
    /**
     * Gets an array of random image(s) from a gallery
     * 
     * @access  public
     * @param   int $id The ID of the gallery to pull the random images from
     * @param   int $number The number of random images to return
     * @param   string $thumb_size The size of the random images thumbnail
     * @param   string $viewable_size The size of the viewable (lightbox) random image
     * @see     SMW_Models_Wordpress_Items_Images::getRand()
     * @see     SMW_Controllers_Items_Type_Images::itemDisplay()
     * @return  array An array that holds the random images
     */
    public function getRand( $id, $number, $thumb_size, $viewable_size ) {
        $images = $this->db->getRand( $id, $number );
        foreach($images as $key => $image) {
            $rand_images[$key] = $this->itemDisplay( $image, $thumb_size, $viewable_size );
        }
        return $rand_images;
    }
    /**
     * Sets the ID & Key for an image in the object
     * 
     * @access public
     * @param string $idKey The ID|Key of the image
     * @return string The ID|Key of the image
     */
    public function setIdKey( $idKey ) {
        $this->idKey = $idKey;
        return $this->idKey;
    }
    /**
     * Returns all the images in a gallery with the passed ID
     * 
     * @access  public
     * @param   int $id The ID of the gallery
     * @see     SMW_Models_Wordpress_Items_Images::selectAll()
     * @return  array An array of all the images in the gallery 
     */
    public function getAll( $id ) {
        return $this->db->selectAll( $id );
    }
    /**
     * Returns the HTML for the image EXIF ajax call
     * 
     * @access  public
     * @see     SMW_Models_Wordpress_Items_Images::getExif()
     * @return  html The image EXIF
     */
    public function imageEXIFAjax() {
        $this->image_exif = $this->db->getExif( $_POST['id'], $_POST['key'] );
        $this->filename = $_POST['filename'];
        
        SMW::getBlock('admin/galleries/edit/exif');
        die();
    }
    /**
     * Ajax call for hiding or showing an image
     * 
     * @access  public
     * @see     SMW_Models_Wordpress_Items_Images::hideItem()
     * @return  html
     */
    public function imageHideAjax() {
        $this->db->hideItem( $_POST['id'] );
        if($_POST['hidden'] == 1) {
            echo '<button class="button-secondary image-hide" imagehide="0">Hide Image</button>';
            echo '<h3>Not Hidden</h3>';
        } else {
            echo '<button class="button-secondary image-hide" imagehide="1">Show Image</button>';
            echo '<h3>Hidden</h3>';
                

        }
        
        die();
    }
    /**
     * Ajax call for saving an image caption
     * 
     * @access  public
     * @see     SMW_Models_Wordpress_Items_Images::updateItem()
     * @return  html The caption
     */
    public function caption_save_callback() {
        $remove_slashes = stripslashes($_POST['caption']);
        $slashed = addslashes($remove_slashes);
        $args['wordpress'] = array(
            'caption' => $slashed 
        );
        $args['service'] = array(
            'ImageID' => $_POST['image_id'],
            'Caption' => stripslashes($_POST['caption'])
        );
        $args['where'] = array(
            'image_id' => $_POST['image_id']
        );
        $response = $this->db->updateItem( $args );
        echo stripslashes($response['caption']);
        die();
    }
    /**
     * Ajax call for returning the edit gallery image list
     * 
     * @access  public
     * @see     SMW_Helpers_Pagination
     * @see     SMW_Helpers_Pagination::total_pages()
     * @see     SMW_Helpers_Pagination::offset()
     * @see     SMW_Models_Items_Type_Images::hideItem()
     * @return  html The admin edit gallery page images block
     */
    public function imagesAjax() {
        extract($_POST);
        
        if (!session_id())
            session_start();
        
        $this->currentPage = $page_number >= 1 ? $page_number : 1;
        
        $key = 'image-number-' . $id;
        if($type == 'img_number') {
            $_SESSION[$key] = $number;
        }
        
        if($type == 'pagination') {
            if($_SESSION[$key] && $_SESSION[$key] != $number) {
                $number = $_SESSION[$key];
            }
        }

        $this->pagination = new SMW_Helpers_Pagination($this->currentPage, $number, $count);

        $this->totalPages = $this->pagination->total_pages();
        
        $this->offset = $number != 'All' ? $this->pagination->offset() : -1;
        
        $this->paged_images = $this->select($this->offset,$id,$number);
        
        SMW::getBlock('admin/galleries/edit/images');
        die();
    }
    /**
     * Ajax call for returning the edit gallery image list
     * 
     * @access  public
     * @param   int $offset The offset of the images in the gallery for pagination
     * @param   int $id The ID for the gallery
     * @param   int $number Number of images to return. Defaults to 10
     * @see     SMW_Models_Wordpress_Items_Images::selectAll()
     * @see     SMW_Models_Wordpress_Items_Images::select()
     * @return  array
     */
    public function select($offset,$id, $number = 10) {
        
        $offset = (int)$offset;
        
        if($offset == -1) {
            return $this->db->selectAll( $id );
        } else {
            return $this->db->select($offset,$id, $number);
        }
    }
    /**
     * Returns an integer of the count of images
     * 
     * @access  public
     * @param   int $id The ID for the gallery
     * @param   int $type The type of count to return. Options are 'only'(Only return count of hidden images)|true(Count of all Images)|false(Count of all non-hidden images)
     * @see     SMW_Models_Wordpress_Items_Images::getCount()
     * @return  int The count of images
     */
    public function getCount( $id = false, $type = false ) {
        return $this->db->getCount( $id, $type );
    }
    /**
     * Gets a page of images
     * 
     * @access  public
     * @param   int $args The arguments to pass to the model
     * @see     SMW_Models_Wordpress_Items_Images::getPage()
     * @return  array An array of images for the page
     */
    public function getPage( $args ) {
        return $this->db->getPage( $args );
    }
    /**
     * Returns an array which holds an image item
     * 
     * @access  public
     * @param   int $id The ID for the image
     * @see     SMW_Models_Wordpress_Items_Images::selectItem()
     * @return  array The image
     */
    public function getItem( $id ) {
        return $this->db->selectItem( $id );
    }
    public function getUrl() {
        
    }
    public function getThumbUrl() {
        
    }
}
