<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of category-comms
 *
 * @author anthony.humes
 */
class SMW_Models_Service_SubCategoryComms extends SMW_Models_Service {
    private static $instance;
    private function __construct() {
        
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function getItem( $args = NULL ) {
        if( $args == NULL ) {
            return $this->smugFunct( 'subcategories_getAll' );
        } else {
            return $this->smugFunct( 'subcategories_get', $args );
        }
        
    }
    
    public function createItem( $args ) {
        $response = $this->smugFunct( 'subcategories_create', $args );
        return $response['id'];
    }
    public function deleteItem( $args ) {
        return $this->smugFunct( 'subcategories_delete', $args );
    }
    public function renameItem( $args ) {
        return $this->smugFunct( 'subcategories_rename', $args );
    }
}
