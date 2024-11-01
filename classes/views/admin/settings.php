<?php

class SMW_Views_Admin_Settings extends SMW_Views_Abstract {
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
	public function settingsJava() {
		SMW::getBlock('admin/settings/javascript');
	}
	public function scripts() {
		add_action('admin_print_footer_scripts', array(&$this,'settingsJava'));
	}
	public function remove_auth_callback() {
	if( ! delete_option('smw_access_token') ) {
            echo '<div id="message" class="error"><p><strong>Update Didn\'t Work</strong></p><p>Your SmugMug authorization wasn\'t removed.</p></div>';
        } else {
            echo '<div id="message" class="updated"><p><strong>Success!</strong></p><p>Your SmugMug authorization has been successfully removed.</p></div>';
        }
	die();
	}
	public function showPage() {
		$this->scripts();
		
		$this->auth = $this->getViewSection('authorization',get_class());
                
                $this->general = $this->getViewSection('general',get_class());
                
                SMW::getBlock('admin/settings');
                
	}
	
}