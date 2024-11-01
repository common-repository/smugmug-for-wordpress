<?php

class SMW_Views_Admin_Settings_Authorization extends SMW_Views_Admin_Settings {
	var $auth_key;
    private static $instance;
	function __construct() {
            //add_action('wp_ajax_remove_auth', array(&$this,'remove_auth_callback'));
            add_action('admin_footer',array(&$this,'smw_footer_script'));
            $this->auth_key = get_option('smugmug_access_token');
            $this->smug_auth = unserialize(get_option('smugmug_authorization'));
	}
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
	public function renderer($layout=false) {
            $this->class = get_class();
            $this->render($layout,$this->class);
	}
	public static function smw_footer_script() {
            ?>
            <script type="text/javascript" charset="utf-8">
            function prettyPhoto_init() {
                    jQuery("a[rel^='prettyPhoto']").prettyPhoto({
                            default_width:600,
                            default_height:600,
                            allow_resize: false
                    });
            }
            function prettyLoader_init() {
                    jQuery.prettyLoader({
                            loader: '/wp-content/plugins/smug-press/prettyLoader/images/prettyLoader/ajax-loader.gif'
                    });
            }
            jQuery(function($){
                prettyPhoto_init();
                //prettyLoader_init();
            });
            </script>
            <?php
	}
	public function removeAuth() {
            SMW::getBlock('admin/settings/auth-remove');
	}
	public function addAuth() {
        $this->service = SMW::getModel('service/auth-comms');
		
            try {
                if ( ! isset( $_SESSION['SmugGalReqToken'] ) ) {
                    $reqToken = $this->service->getRequestToken();
                    $_SESSION['SmugGalReqToken'] = serialize( $reqToken );
                    //echo "<p><a href='".$serviceAuth->getAuthorizeLink( $reqToken )."'>Click Here</a></p>";
                    SMW::getBlock('admin/settings/auth-create');
                } else {
                    $auth_service = SMW::getModel('service/auth-comms');
                    $reqToken = unserialize( $_SESSION['SmugGalReqToken'] );
                    unset( $_SESSION['SmugGalReqToken'] );
                    $_SESSION = array();

                    // Step 3: Use the Request token obtained in step 1 to get an access token
                    $auth_service->setToken( $reqToken );
                    $token = $auth_service->getAccessToken();	// The results of this call is what your application needs to store.

                    $serialized_token = serialize($token);



                    update_option('smw_access_token',$serialized_token);

                    $option = get_option('smw_access_token');

                                    //print_r($option);

                    $token_new = unserialize(get_option('smw_access_token'));
                                    //print_r($token_new);
                    // Set the Access token for use by SMW_phpSmug.   
                    $auth_service->setToken( $token_new );
                    //SMW::getBlock('admin/settings/auth-success');
                }
            } catch ( Exception $e ) {
                if ($e->getCode() == 36) {
                    echo "<div id='message' class='error'><p><strong>Expired Token (Refresh this Page)</strong></p></div>";
                } else {
                    echo "<div id='message' class='error'><p><strong>Not Successful</strong></p><p>{$e->getMessage()} (Error Code: {$e->getCode()})</p></div>";
            }

            }
	}
	public function remove_auth_callback() {
            if( ! delete_option('smugmug_access_token') ) {
                echo '<div id="message" class="error"><p><strong>Update Didn\'t Work</strong></p><p>Your SmugMug authorization wasn\'t removed.</p></div>';
            } else {
                echo '<div id="message" class="updated"><p><strong>Success!</strong></p><p>Your SmugMug authorization has been successfully removed.</p></div>';
                $this->authorize_smugmug();
            }
		
            die();
	}
	public function remove_authorization() {
            
	}
	function create_section() {
            $this->renderer('authorization');
	}
}
