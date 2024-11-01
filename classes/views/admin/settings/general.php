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
class SMW_Views_Admin_Settings_General {
    private static $instance;
    public $forms;
    private function __construct() {
        $this->control = SMW::getControl( 'admin/settings' );
        $this->forms = SMW::getHelper('forms');
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function settingsTable( $type ) {
        $this->form_items = $this->control->getSettings( $type );
        SMW::getBlock('admin/settings/' . $type);
    }
    function general_settings_callback() {
        
        $posted_data = explode("&", $_POST['form_data']);
        
        $forms = SMW::getHelper('forms');
        
        $forms->save_form_ajax($posted_data);
        
        //if($form_save) {
            //echo '<div id="message" class="error"><p><strong>Update Didn\'t Work</strong></p><p>Your settings weren\'t saved.</p></div>';
        //} else {
            echo '<div id="message" class="updated"><p><strong>Success!</strong></p><p>Your '.$_POST['type'].' settings have been saved.</p></div>';
        //}
        
        die();
    }
}