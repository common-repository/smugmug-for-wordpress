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
class SMW_Models_Wordpress_Items_Images extends SMW_Models_Wordpress_Items {
    private static $instance;
    private function __construct() {
        global $wpdb;
        $this->db = $wpdb;
        $this->service = SMW::getModel('service/image-comms');
        $this->tableName = $this->getTableName();
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function getTableName() {
        return $this->db->prefix . "smw_images";
    }
    public function itemTable( array $galleries ) {
        $sql_array = array(
            array('name' => 'id','code' => 'int(9) PRIMARY KEY AUTO_INCREMENT'),
            array('name' => 'gallery_id','code' => 'INT(15) NOT NULL, INDEX (gallery_id)'),
            array('name' => 'gallery_key','code' => 'VARCHAR(10) NOT NULL'),
            array('name' => 'image_id','code' => 'INT(15) UNIQUE NOT NULL'),
            array('name' => 'image_key','code' => 'VARCHAR(10) NOT NULL'),
            array('name' => 'filename','code' => 'VARCHAR(255) NOT NULL'),
            array('name' => 'caption','code' => 'TEXT'),
            array('name' => 'size','code' => 'INT(8) NOT NULL'),
            array('name' => 'original_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'x3large_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'x2large_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'xlarge_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'large_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'medium_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'small_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'tiny_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'thumb_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'hidden','code' => 'INT(1) NOT NULL'),
            array('name' => 'keywords','code' => 'TEXT NOT NULL'),
            array('name' => 'height','code' => 'INT(10) NOT NULL'),
            array('name' => 'width','code' => 'INT(10) NOT NULL'),
            array('name' => 'video1920_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'video1280_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'video960_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'video640_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'video320_url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'postion','code' => 'INT(10) NOT NULL'),
            array('name' => 'date_uploaded','code' => 'DATETIME'),
            array('name' => 'last_updated','code' => 'DATETIME'),
            array('name' => 'url','code' => 'VARCHAR(1024) DEFAULT \'\' NOT NULL'),
            array('name' => 'extras','code' => 'MEDIUMTEXT')
        );
        if( !$this->tableExists( $this->tableName ) ) {
            $this->createTable( $this->tableName, $sql_array );
            //foreach($galleries as $gallery) {
                //$idKey = $gallery['id'] .'|'. $gallery['Key'];
                //$images = $this->service->getImages( $idKey, true );
                //print_r($images);
                //$images = $images['Images'];
                //foreach( $images as $image ) {
                    //$this->addItem( $image, $gallery['id'], $gallery['Key'] );
                //}
            //}
        }
    }
    public function addItem( array $image, $galleryId, $galleryKey ) {
        $table_name = $this->getTableName();
        
        $extras = $image;
        unset($extras['id']);
        unset($extras['Key']);
        unset($extras['Title']);
        unset($extras['URL']);
        unset($extras['Caption']);
        unset($extras['Size']);
        unset($extras['OriginalURL']);
        unset($extras['X3LargeURL']);
        unset($extras['X2LargeURL']);
        unset($extras['XLargeURL']);
        unset($extras['LargeURL']);
        unset($extras['MediumURL']);
        unset($extras['SmallURL']);
        unset($extras['TinyURL']);
        unset($extras['ThumbURL']);
        unset($extras['Height']);
        unset($extras['Width']);
        unset($extras['Video1920URL']);
        unset($extras['Video1280URL']);
        unset($extras['Video960URL']);
        unset($extras['Video640URL']);
        unset($extras['Video320URL']);
        unset($extras['Position']);
        unset($extras['Date']);
        unset($extras['LastUpdated']);
        
        
        $extras = serialize($extras);
        $response[] = $this->db->insert( $table_name, array(
            'gallery_id' => $galleryId,
            'gallery_key' => $galleryKey,
            'image_id' => $image['id'],
            'image_key' => $image['Key'],
            'filename' => $image['FileName'],
            'url' => $image['URL'],
            'caption' => $image['Caption'],
            'size' => $image['Size'],
            'original_url' => $image['OriginalURL'],
            'x3large_url' => $image['X3LargeURL'],
            'x2large_url' => $image['X2LargeURL'],
            'xlarge_url' => $image['XLargeURL'],
            'large_url' => $image['LargeURL'],
            'medium_url' => $image['MediumURL'],
            'small_url' => $image['SmallURL'],
            'tiny_url' => $image['TinyURL'],
            'thumb_url' => $image['ThumbURL'],
            'hidden' => $image['Hidden'],
            'keywords' => $image['Keywords'],
            'height' => $image['Height'],
            'width' => $image['Width'],
            'video1920_url' => $image['Video1920URL'],
            'video1280_url' => $image['Video1280URL'],
            'video960_url' => $image['Video960URL'],
            'video640_url' => $image['Video640URL'],
            'video320_url' => $image['Video320URL'],
            'postion' => $image['Position'],
            'date_uploaded' => $image['Date'],
            'last_updated' => $image['LastUpdated'],
            'extras' => $extras,
        ));
        return $response;
    }
    public function updateItem( $args ) {
        extract( $args );
        
        $this->service->changeSettings( $service );
        $this->db->update($this->tableName, $wordpress, $where, $format = NULL);
        return $this->selectItem( $service['ImageID'] );
    }
    public function removeItem( $id ) {
        
    }
    public function removeGallery( $id ) {
        return $this->db->query("DELETE FROM {$this->tableName} WHERE gallery_id = {$id}");
    }
    public function addGallery( $idKey ) {
        $id_key = explode('|',$idKey);
        $images = $this->service->getImages( $idKey, true );
        foreach($images['Images'] as $image) {
            $this->addItem($image,$id_key[0],$id_key[1]);
        }
        return $images;
    }
    public function getRand( $id, $number ) {
        return $this->db->get_results( "SELECT * FROM {$this->tableName} WHERE gallery_id = {$id} AND hidden = 0 ORDER BY RAND() LIMIT {$number}", ARRAY_A );
    }
    public function getPage( $args ) {
        return $this->db->get_results( "SELECT {$args['rows']} FROM {$this->tableName} WHERE gallery_id = {$args['id']} AND hidden = 0 LIMIT {$args['offset']}, {$args['number']}", ARRAY_A );
        //return "SELECT {$args['rows']} FROM {$this->tableName} WHERE gallery_id = {$args['id']} AND hidden = 0 LIMIT {$args['offset']}, {$args['number']}";
    }
    public function getCount( $id = false, $hidden = false ) {
        if($hidden == 'only') {
            return $this->db->get_var( "SELECT COUNT(*) FROM {$this->tableName} WHERE gallery_id = {$id} AND hidden = 1" );
        } elseif($hidden) {
            return $this->db->get_var( "SELECT COUNT(*) FROM {$this->tableName} WHERE gallery_id = {$id}" );
        } elseif($id) {
            return $this->db->get_var( "SELECT COUNT(*) FROM {$this->tableName} WHERE gallery_id = {$id} AND hidden = 0" );
        } else {
            return $this->db->get_var( "SELECT COUNT(*) FROM {$this->tableName}" );
        }
    }
    public function getExif( $id, $key ) {
        return $this->service->getExif( array('ImageID' => $id, 'ImageKey' => $key ) );
    }
    public function selectItem( $id ) {
        return $this->db->get_row("SELECT * FROM {$this->tableName} WHERE image_id = {$id}", ARRAY_A);
    }
    public function select($offset,$id,$number) {
        return $this->db->get_results("SELECT * FROM {$this->tableName} WHERE gallery_id = {$id} LIMIT $number OFFSET $offset", ARRAY_A);
    }
    public function selectAll( $id ) {
        return $this->db->get_results("SELECT * FROM {$this->tableName} WHERE gallery_id = {$id}", ARRAY_A);
    }
    public function getVar( $args ) {
        extract( $args );
        return $this->db->get_var("SELECT {$columns} FROM {$this->tableName} WHERE {$where}");
    }
    public function hideItem( $id ) {
        $image = $this->selectItem( $id );
        if( $image['hidden'] == 1 ) {
            $this->service->changeSettings( array( 'ImageID' => $id, 'Hidden' => 1 ) );
            return $this->db->query("UPDATE {$this->tableName} SET hidden = 0 WHERE image_id = {$id}");
        } else {
            $this->service->changeSettings( array( 'ImageID' => $id, 'Hidden' => 0 ) );
            return $this->db->query("UPDATE {$this->tableName} SET hidden = 1 WHERE image_id = {$id}");
        }
        
    }
}
