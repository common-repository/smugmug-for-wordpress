<?php

/**
 * SMW_Controllers_Items_Item - Abstract class which starts the Item controller
 *
 * @package SMW_Controllers
 * @subpackage Items
 * @author Anthony Humes
 **/
abstract class SMW_Controllers_Items_Item extends SMW_Controllers_Abstract {
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var string
     */
    protected $idKey;
    /**
     * Sets the idKey
     * 
     * @access  private
     * @return  string
     */
    function setIdKey( $idKey ) {
        $this->idKey = $idKey;
        
    }
    /**
     * Gets the idKey
     * 
     * @access  private
     * @return  string
     */
    function getIdKey() {
        if(isset( $this->idKey )) {
            return $this->idKey;
        } else {
            return 'ID/Key is not set';
        }
    }
    abstract static function getInstance();
}
