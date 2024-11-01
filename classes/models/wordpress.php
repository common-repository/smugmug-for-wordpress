<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of wordpress
 *
 * @author anthony.humes
 */
abstract class SMW_Models_Wordpress extends SMW_Models_Abstract {
    private function __construct() {
        global $wpdb;
        $this->db = $wpdb;
    }
    public function tableExists( $table_name ) {
        $table_name = $table_name;
        $table = $this->db->get_var( "SHOW TABLES LIKE '{$table_name}'" );
        if( $table == $table_name ) {
            return true;
        }
        return false;
    }
    public function insertItem( $table_name ) {
        
    }
    public function dropTable( $tablename ) {
        return $this->db->query("DROP TABLE IF EXISTS {$tablename}");
    }
    public function getDb( $type ) {
        switch( $type ) {
            case 'albums':
                return $this->getAlbumDb();
                break;
            case 'categories':
                return $this->getCategoryDb();
                break;
            case 'subcategories':
                return $this->getSubCategoryDb();
                break;
            case 'images':
                return $this->getImagesDb();
                break;
            case 'galleries':
                return $this->getGalleriesDb();
                break;
        }
    }
    private function getAlbumDb() {
        return SMW::getModel('wordpress/items/albums');
    }
    private function getCategoryDb() {
        return SMW::getModel('wordpress/items/categories');
    }
    private function getSubCategoryDb() {
        return SMW::getModel('wordpress/items/sub-categories');
    }
    private function getImagesDb() {
        return SMW::getModel('wordpress/items/images');
    }
    private function getGalleriesDb() {
        return SMW::getModel('wordpress/items/galleries');
    }
    public function createTable( $table_name, array $options ) {
        
        $sql = 'CREATE TABLE ' . $table_name .'(
            ';
        
        $numItems = count($options);
        $i = 0;
        foreach($options as $option) {
            if($i + 1 == $numItems) {
                $sql .= $option['name'] . ' ' . $option['code'] . '
                ';
            } else {
                $sql .= $option['name'] . ' ' . $option['code'] . ',
                ';
            }
            $i++;
        }
        $sql .= ');';
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

?>
