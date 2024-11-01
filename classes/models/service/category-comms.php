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
class SMW_Models_Service_CategoryComms extends SMW_Models_Service {
    private static $instance;
    private function __construct() {
        //$this->db = $this->getItemDb( 'categories' );
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function getItem() {
        return $this->smugFunct( 'categories_get' );
    }
    public function createItem( $args ) {
        $response = $this->smugFunct( 'categories_create', $args );
        return $response['id'];
    }
    public function deleteItem( $args ) {
        return $this->smugFunct( 'categories_delete', $args );
    }
    public function renameItem( $args ) {
        return $this->smugFunct( 'categories_rename', $args );
    }
}
