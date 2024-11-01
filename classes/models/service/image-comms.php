<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of image-comms
 *
 * @author anthony
 */
class SMW_Models_Service_ImageComms extends SMW_Models_Service {
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
    public function getImages( $idKey, $heavy = false ) {
        $idKey = explode('|',$idKey);
        if(!$heavy) {
            $images = $this->smugFunct( 'images_get', array( 'AlbumID' => $idKey[0], 'AlbumKey' => $idKey[1] ) );
        } else {
            $images = $this->smugFunct('images_get',array( 'AlbumID' => $idKey[0], 'AlbumKey' => $idKey[1], 'Heavy' => 1 ));
        }
        return $images;
    }
    public function changeSettings( array $args ) {
        return $this->smugFunct( 'images_changeSettings', $args );
    }
    public function imageDelete( $imageId ) {
        return $this->smugFunct( 'images_delete' );
    }
    public function getStats( array $args ) {
        return $this->smugFunct( 'images_getStats', $args );
    }
    public function getExif( array $args ) {
        return $this->smugFunct( 'images_getEXIF', $args );
    }
}
