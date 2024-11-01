<?php

/**
 * SMW_Controllers_Admin_Galleries - Controls the manage gallery section in the admin
 *
 * @package SMW_Controllers
 * @subpackage Admin
 * @author Anthony Humes
 **/
class SMW_Controllers_Admin_Galleries extends SMW_Controllers_Abstract {
    /**
     * This variable is set in __construct and provides access to the Sub-Category item controller
     * 
     * @access protected
     * @var Object|SMW_Controllers_Items_Type_SubCategories
     */
    public $sub_category;
    /**
     * This variable is set in __construct and provides access to the category item controller
     * 
     * @access protected
     * @var Object|SMW_Controllers_Items_Type_Categories
     */
    public $category;
    /**
     * This variable is set in __construct and provides access to the Gallery item controller
     * 
     * @access protected
     * @var Object|SMW_Controllers_Items_Type_Galleries
     */
    public $db;
    /**
     * This variable is set in __construct and provides access to the forms helper
     * 
     * @access protected
     * @var Object|SMW_Helpers_Forms
     */
    public $forms;
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object|SMW_Controllers_Admin_Galleries
     */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @see     SMW::getItem()
     * @see     SMW::getHelper()
     * @return  void
     */
    private function __construct() {
        $this->sub_category = SMW::getItem( 'sub-categories' );
        $this->category = SMW::getItem( 'categories' );
        $this->db = SMW::getItem( 'galleries' );
        $this->forms = SMW::getHelper( 'forms' );
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Admin_Galleries
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Returns an array of galleries that are only in the Service and not in the WP database
     * 
     * @access  public
     * @return  array
     */
    public function getService() {
        return $this->db->getServiceOnly();
    }
    /**
     * Returns an array of galleries that are only in the WP database
     * 
     * @access  public
     * @return  array
     */
    public function getGalleries() {
       return $this->db->getAll();
    }
    /**
     * Echos out the javascript block file for the manage galleries page
     * 
     * @access  public
     * @see     SMW::getBlock()
     * @return  void
     */
    public function settingsJava() {
        SMW::getBlock( 'admin/galleries/javascript' );
    }
    /**
     * Creates the WP action which prints the javascript
     * 
     * @access  public
     * @return  array
     */
    public function scripts() {
        add_action('admin_print_footer_scripts', array(&$this,'settingsJava'));
    }
}