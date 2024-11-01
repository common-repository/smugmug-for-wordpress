<?php

class SMW_Models_Service_GalleryComms extends SMW_Models_Service {
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
    public function getItems( $idKey = NULL, $heavy = false, $args = NULL ) {
        if($idKey == NULL) {
            if(!$heavy) {
                $album = $this->smugFunct('albums_get', $args);
            } else {
                $album = $this->smugFunct('albums_get',array( 'Heavy' => 1 ));
            }
        } else {
            $idKey = explode('|',$idKey);
            $album = $this->smugFunct('albums_getInfo',array('AlbumID' => $idKey[0], 'AlbumKey' => $idKey[1]));
        }
        return $album;
    }
    public function changeSettings( array $args ) {
        $response = $this->smugFunct( 'albums_changeSettings', $args );
    }
    public function createItem( array $args ) {
        return $this->smugFunct( 'albums_create', $args );
    }
    public function deleteItem( $id, $args = array() ) {
        
        $args = $args + array('AlbumID' => $id);
        $response = $this->smugFunct( 'albums_delete', $args );
    }
    public function albumStats( array $args ) {
        $response = $this->smugFunct( 'albums_getStats', $args );
    }
    public function updateItem( $args ) {
        $response = $this->smugFunct('albums_changeSettings', $args);
        return $response;
    }
}
