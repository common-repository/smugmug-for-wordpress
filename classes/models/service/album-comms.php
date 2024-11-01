<?php

class SMW_Models_Service_AlbumComms extends SMW_Models_Service {
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
    public function getAlbums( string $idKey = NULL, $heavy = false ) {
        if($idKey == NULL) {
            if(!$heavy) {
                $album = $this->smugFunct('albums_get');
            } else {
                $album = $this->smugFunct('albums_get',array( 'Heavy' => 1 ));
            }
        } else {
            $idKey = explode('|',$idKey);
            $album = $this->smugFunct('albums_getInfo',array('id' => $idKey[0], 'Key' => $idKey[1]));
        }
        return $album;
    }
    public function changeSettings( array $args ) {
        $response = $this->smugFunct( 'albums_changeSettings', $args );
    }
    public function createAlbum( array $args ) {
        $response = $this->smugFunct( 'albums_create', $args );
    }
    public function deleteAlbum( string $idKey = NULL, array $args = array() ) {
        if($idKey) {
            $idKey = explode('|',$idKey);
            $idArray = array('AlbumID' => $idKey[0]);
            $args = $idArray + $args;
        }
        $response = $this->smugFunct( 'albums_delete', $args );
    }
    public function albumStats( array $args ) {
        $response = $this->smugFunct( 'albums_getStats', $args );
    }
}
