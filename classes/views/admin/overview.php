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
class SMW_Views_Admin_Overview extends SMW_Views_Abstract {
    private static $instance;
    private function __construct() {
        $this->control = SMW::getControl('admin/overview');
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function showPage() {
        $this->args = $this->control->getArgs();
        SMW::getBlock('admin/overview');
    }
}