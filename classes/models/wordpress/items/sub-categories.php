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
class SMW_Models_Wordpress_Items_SubCategories extends SMW_Models_Wordpress_Items {
    private static $instance;
    private function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->tableName = $this->getTableName();
        $this->service = SMW::getModel('service/sub-category-comms');
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function getTableName() {
        return $this->db->prefix . "smw_sub_categories";
    }
    public function itemTable() {
        $subcategories = $this->service->getItem();
        
        $sql_array = array(
            array('name' => 'id','code' => 'int(9) PRIMARY KEY AUTO_INCREMENT'),
            array('name' => 'category_id','code' => 'INT(15) NOT NULL, INDEX (category_id)'),
            array('name' => 'sub_category_id','code' => 'INT(15) UNIQUE NOT NULL'),
            array('name' => 'name','code' => 'TEXT NOT NULL'),
            array('name' => 'nice_name','code' => 'TEXT NOT NULL')
        );
        if( !$this->tableExists( $this->tableName ) ) {
            
            $this->createTable( $this->tableName, $sql_array );
            foreach($subcategories as $subcategory) {
                $this->addItem( $subcategory );
            }
        }
    }
    public function addItem( array $sub_category ) {
        $table_name = $this->getTableName();
        $response[] = $this->db->insert( $table_name, array(
            'category_id' => $sub_category['Category']['id'],
            'sub_category_id' => $sub_category['id'],
            'name' => $sub_category['Name'],
            'nice_name' => $sub_category['NiceName']
        ));
        return $response;
    }
    public function createItem( $args ) {
        $response = $this->service->createItem( $args );
        if($response) {
            $this->dropTable( $this->tableName );
            $this->itemTable();
            return $this->selectItem( $response, true );
        } else {
            return false;
        }
    }
    public function renameItem( $args ) {
        $response = $this->service->renameItem( $args );
        if( $response ) {
            $item = $this->selectItem( $args['SubCategoryID'], true );
            $this->dropTable( $this->tableName );
            $this->itemTable();
            return $item;
        }
        return false;
    }
    public function updateItem( $item ) {
        
    }
    public function deleteItem( $args ) {
        $id = $args['SubCategoryID'];
        $response = $this->service->deleteItem( $args );
        if(!$response) {
            $item = $this->selectItem($id,true);
            $this->removeItem( $id );
            return $item;
        }
        return false;
    }
    public function removeItem( $id ) {
        return $this->db->query("DELETE FROM {$this->tableName} WHERE sub_category_id = {$id}");
    }
    public function selectItem( $id, $array = false ) {
        if( $array ) {
            return $this->db->get_row("SELECT * FROM {$this->tableName} WHERE sub_category_id = {$id}", ARRAY_A);
        } else {
            return $this->db->get_row("SELECT * FROM {$this->tableName} WHERE sub_category_id = {$id}");
        }
    }
    public function getName( $id ) {
        $sub_category = $this->selectItem( $id, true );
        $sub_category_name = $sub_category['name'];
        return $sub_category_name;
    }
    public function selectItems( $id = NULL, $array = false ) {
        if($array) {
            if($id == NULL) {
                return $this->db->get_results("SELECT * FROM {$this->tableName}", ARRAY_A);
            } else {
                return $this->db->get_results("SELECT * FROM {$this->tableName} WHERE category_id = {$id}", ARRAY_A);
            }
        } else {
            if($id == NULL) {
                return $this->db->get_results("SELECT * FROM {$this->tableName}");
            } else {
                return $this->db->get_results("SELECT * FROM {$this->tableName} WHERE category_id = {$id}");
            }
        }
        
    }
    
}
