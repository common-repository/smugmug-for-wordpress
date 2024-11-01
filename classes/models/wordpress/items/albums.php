<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of galleries
 *
 * @author anthony.humes
 */
class SMW_Models_Wordpress_Items_Albums extends SMW_Models_Wordpress_Items {
    private static $instance;
    private function __construct() {
        //parent::__construct();
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function getTableName() {
        return $this->db->prefix . "smugmug_albums";
        
    }
    public function addItem() {
        
    }
    public function updateItem( $item ) {
        
    }
    public function removeItem() {
        
    }
    public function selectItem() {
        
    }
}
