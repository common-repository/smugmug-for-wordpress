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
class SMW_Views_Admin_Galleries_Edit extends SMW_Views_Abstract {
    private static $instance;
    private function __construct() {
        $this->control = SMW::getControl('admin/galleries/edit');
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function showPage() {
        $this->gallery = $this->control->getItem();
        $this->control->scripts();
        SMW::getBlock('admin/galleries/edit/main');
    }
}