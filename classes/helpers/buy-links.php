<?php
/**
 * SMW_Helpers_BuyLinks - Controls the admin settings page
 * 
 * @package SMW
 * @subpackage Helpers
 * @author  Anthony Humes
 **/
class SMW_Helpers_BuyLinks {
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access protected
     * @var string The base url to use for the buy links
     */
    protected $base_url;
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object|SMW_Helpers_BuyLinks
     * @see SMW::getHelper()
     */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @see     SMW_Controllers_Items_Type_Galleries
     * @return  void
     */
    private function __construct() {
        $this->galleries = SMW::getItem('galleries');
        $this->base_url = 'http://' . get_option('smw_default_service_url');
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Helpers_BuyLinks
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Returns a buy link for an image
     * 
     * @access  public
     * @param   array $image The image info which is used to create the buy link
     * @return  void
     */
    public function image( $image ) {
        $buy_link = $this->base_url . '/buy/' . $image['gallery_id'] . '_' . $image['gallery_key'] . '/' . $image['image_id'] . '_' . $image['image_key'];
        return $buy_link;
    }
    /**
     * Returns a buy link for an image
     * 
     * @access  public
     * @param   array $gallery The gallery info which is used to create the buy link
     * @return  void
     */
    public function gallery( $id ) {
        $gallery = $this->galleries->getItem( $id, true );
        $buy_link = $this->base_url . '/buy/' . $gallery['gallery_id'] . '_' . $gallery['gallery_key'];
        return $buy_link;
    }
}
