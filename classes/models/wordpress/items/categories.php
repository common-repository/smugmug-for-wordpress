<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of images
 *
 * @author anthony.humes
 */
class SMW_Models_Wordpress_Items_Categories extends SMW_Models_Wordpress_Items {
    private static $instance;
    private function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->tableName = $this->getTableName();
        $this->service = SMW::getModel('service/category-comms');
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function getTableName() {
        return $this->db->prefix . "smw_categories";
    }
    public function deleteItem( $id ) {
        $args['CategoryID'] = $id;
        $response = $this->service->deleteItem( $args );
        if(!$response) {
            $item = $this->selectItem($id,true);
            $this->removeItem( $id );
            return $item;
        }
        return false;
    }
    public function removeItem( $id ) {
        return $this->db->query("DELETE FROM {$this->tableName} WHERE category_id = {$id}");
    }
    public function renameItem( $args ) {
        $response = $this->service->renameItem( $args );
        if(!$response) {
            $this->dropTable();
            $this->itemTable();
            return $this->selectItem( $args['CategoryID'], true );
        }
        return false;
    }
    public function createItem( $category ) {
        $args['Name'] = $category['name'];
        if($category['nice_name']) {
            $args['NiceName'] = $category['nice_name'];
        }
        $response = $this->service->createItem( $args );
        if($response) {
            $this->dropTable();
            $this->itemTable();
            return $this->selectItem( $response, true );
        } else {
            return false;
        }
    }
    public function itemTable() {
        $categories = $this->service->getItem();
        $sql_array = array(
            array('name' => 'id','code' => 'int(9) PRIMARY KEY AUTO_INCREMENT'),
            array('name' => 'category_id','code' => 'INT(15) UNIQUE NOT NULL'),
            array('name' => 'category_name','code' => 'TEXT NOT NULL'),
            array('name' => 'category_nice_name','code' => 'TEXT NOT NULL'),
            array('name' => 'category_type','code' => 'TEXT DEFAULT \'\' NOT NULL'),
        );
        if( !$this->tableExists( $this->tableName ) ) {
            $this->createTable( $this->tableName, $sql_array );
            foreach($categories as $category) {
                $this->addItem( $category );
            }
        }
    }
    public function addItem( array $category ) {
        $response[] = $this->db->insert( $this->tableName, array(
            'category_id' => $category['id'],
            'category_name' => $category['Name'],
            'category_nice_name' => $category['NiceName'],
            'category_type' => $category['Type']
        ));
        return $response;
    }
    public function updateItem( $item ) {
        
    }
    //public function removeItem( $id ) {
        
    //}
    public function getName( $id ) {
        $category = $this->selectItem( $id, true );
        $category_name = $category['category_name'];
        return $category_name;
    }
    public function selectItem( $id, $array = false ) {
        if($array) {
            return $this->db->get_row("SELECT * FROM {$this->tableName} WHERE category_id = {$id}", ARRAY_A);
        } else {
            return $this->db->get_row("SELECT * FROM {$this->tableName} WHERE category_id = {$id}");
        }
    }
    public function selectAll() {
        return $this->db->get_results("SELECT * FROM {$this->tableName}");
    }
    public function selectAllForms( $user = false ) {
        if($user) {
            $categories = $this->db->get_results("SELECT * FROM {$this->tableName} WHERE category_type = 'User'", ARRAY_A);
        } else {
            $categories = $this->db->get_results("SELECT * FROM {$this->tableName}", ARRAY_A);
        }
        
        foreach( $categories as $key => $category ) {
            $new_categories[$key]['value'] = $category['category_id'];
            $new_categories[$key]['name'] = $category['category_name'];
        }
        return $new_categories;
    }
}
