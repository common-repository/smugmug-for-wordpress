<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of auth-comms
 *
 * @author anthony
 */
class SMW_Models_Service_AuthComms extends SMW_Models_Service {
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
    public function getRequestToken() {
        $requestToken = $this->smugFunct('auth_getRequestToken',false);
        return $requestToken;
    }
    public function getAuthorizeLink() {
        $authLink = $this->smugFunct('authorize',array("Access" => "Full", "Permissions" => "Modify"),true);
        return $authLink;
    }
    public function setToken( $req ) {
        $setToken = $this->smugFunct('setToken',array('id' => $req['Token']['id'], 'Secret' => $req['Token']['Secret']),true);;
        return $setToken;
    }
    public function getAccessToken() {
        $accessToken = $this->smugFunct('auth_getAccessToken',false,true);
        return $accessToken;
    }
}
?>
