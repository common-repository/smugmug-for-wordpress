<?php

/**
 * SMW_Controllers_Admin_Categories - Controls the Categories section in admin
 *
 * @package SMW_Controllers
 * @subpackage Admin
 * @author Anthony Humes
 **/
class SMW_Controllers_Admin_Overview extends SMW_Controllers_Abstract {
    /**
     * This variable is set in __construct and provides access to the Gallery item controller
     * 
     * @access private
     * @var Object
     */
    protected $galleries;
    /**
     * This variable is set in __construct and provides access to the Image item controller
     * 
     * @access private
     * @var Object
     */
    protected $images;
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
        $this->galleries = SMW::getItem('galleries');
        $this->images = SMW::getItem('images');
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Admin_Overview
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Gets the arguments required for the view to show statistics about the SmugMug account
     * 
     * @access  public
     * @return  array Returns an array of statistics
     */
    public function getArgs() {
        $args['gallery_count'] = $this->galleries->getCount();
        $args['image_count'] = $this->images->getCount();
        $args['gallery_pass_count'] = $this->galleries->getCount( true );
        return $args;
    }
}