<?php

abstract class SMW_Models_Service extends SMW_Models_Abstract {
    private function smugInstance() {
        $f = SMW_phpSmug::getInstance( array(
            "APIKey" => SMW_API_KEY,
            "AppName" => "SmugMug for Wordpress / " . SMW_VERSION,
            "APIVer" => "1.3.0",
            "OAuthSecret" => SMW_SECRET_KEY
        ) );
        if(get_option('smw_access_token')) {
            $token_new = unserialize( get_option( 'smw_access_token' ) );
            // Set the Access token for use by SMW_phpSmug.   
            $f->setToken( "id={$token_new['Token']['id']}", "Secret={$token_new['Token']['Secret']}" );
            
        }
        
        
        
        return $f;
    }
    
    public function smugFunct($function,$value = false,$error = false ) {
        $f = $this->smugInstance();
		if($error) {
			if($value != false) {
                $response = $f->$function($value);
            } else {
                $response = $f->$function();
            }
		} else {
			try {
	            if($value != false) {
	                $response = $f->$function($value);
	            } else {
	                $response = $f->$function();
	            }
	
	        }
	        catch ( Exception $e ) {
	            echo "{$e->getMessage()} (Error Code: {$e->getCode()})";
	        }
		}
        

        return $response;
    }
    
    public function getItemDb( $type ) {
        return SMW::getModel('wordpress/items/' . $type);
    }
}