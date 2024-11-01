<?php

/**
 * SMW_Helpers_Lightbox - Controls the admin settings page
 * 
 * @package SMW
 * @subpackage Helpers
 * @author  Anthony Humes
 **/
class SMW_Helpers_Lightbox {
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
        $this->type = get_option('smw_lightbox_type');
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Helpers_Lightbox
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Sets the lightbox code depending on the type set in the admin
     * 
     * @access  public
     * @param   string $set The gallery set
     * @param   array $image The image to be used for the lightbox
     * @return  string Lightbox code
     */
    public function code( $set, $image ) {
        switch($this->type) {
            case 'prettyphoto':
                $lightbox_code = 'rel="prettyPhoto['.$set.']" title="'.$image['Caption'].'"';
                break;
            case 'clearbox':
                $lightbox_code = $image['Type'];
                if($image['Type'] == 'image') {
                    $lightbox_code = 'rel="clearbox[gallery='.$set.',,comment='.$image['Caption'].']"';
                } elseif($image['Type'] == 'video') {
                    $lightbox_code = 'rel="clearbox[gallery='.$set.',,width='.$image['Width'].',,height='.$image['Height'].',,comment='.$image['Caption'].']"';
                }

                break;
        }
        return $lightbox_code;
    }
}