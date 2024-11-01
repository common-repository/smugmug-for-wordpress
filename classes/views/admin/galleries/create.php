<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of view
 *
 * @author anthony.humes
 */
class SMW_Views_Admin_Galleries_Create extends SMW_Views_Abstract {
    public $obj;
    private static $instance;
    private function __construct() {
        $this->obj = $this->getObj(get_class());
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }

    public function showPage() {
        $this->obj->scripts();
        SMW::getBlock('admin/galleries/create/main');
    }
}