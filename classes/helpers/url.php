<?php

/**
 * SMW_Helpers_Url - Controls the admin settings page
 * 
 * @package SMW
 * @subpackage Helpers
 * @author  Anthony Humes
 **/
class SMW_Helpers_Url {
    /**
     * This variable is set in getInstance() and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object|SMW_Helpers_Url
     * @see SMW::getHelper()
     */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @return  void
     */
    private function __construct() {
        $default = get_option( 'smw_service_url' );
        $service_url = get_option( 'smw_default_service_url' );
        if( !$service_url || $service_url == '://' ) {
            $this->base_url = 'http://' . get_option('smw_service_url');
        } else {
            $this->base_url = 'http://' . get_option('smw_default_service_url');
        }
        
        $this->lightbox_type = get_option('smw_lightbox_type');
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Helpers_Url
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Returns the final url depending on the setting in the admin
     * 
     * @access  public
     * @param   string $url The url is parsed and then recreated
     * @return  string The final url with the domain set in the settings page
     */
    function finalURL($url) {
        $path = $this->parse($url,'path');
        $final_url = $this->base_url . $path;
        return $final_url;
    }
    /**
     * Returns the final video url depending on the lightbox size
     * 
     * @access  public
     * @param   array $item An array which holds the item info
     * @param   int $width The width of the video url
     * @param   int $height The height of the video url
     * @param   int $video_size The size of the video
     * @return  string The url of the video to be used
     */
    function videoURL($item,$width,$height,$video_size) {
        switch($this->lightbox_type) {
            case 'prettyphoto':
                $video_url = $this->finalURL($item[$video_size]) . '?width='.$width.'&height='.$height;
                break;
            case 'clearbox':
                $video_url = $this->finalURL($item[$video_size]);
                break;
        }
        return $video_url;
    }
    /**
     * Removes a variable from the query string
     * 
     * @access  public
     * @param   string $query The querystring to remove variables from
     * @param   string|array $key Either to the key to remove or an array of keys to remove
     * @return  string The url
     */
    function remove_querystring_var($query, $key) {
        parse_str($query,$vars);
        if(is_array($key)) {
            foreach($key as $value) {
                unset($vars[$value]);
            }
        } else {
            unset($vars[$key]);
        }
        
        return http_build_query($vars);
    }
    /**
     * Parses a url and returns the value specified by the type
     * 
     * @access  public
     * @param   string $url The url to parse
     * @param   string $type The type of parsing to run on the url
     * @return  string The url
     */
    public function parse( $url, $type ) {
        $parsed_url = parse_url($url);
        if(!$parsed_url['host']) {
            $parsed_url['host'] = $_SERVER['SERVER_NAME'];
        }
        if(!$parsed_url['scheme']) {
            $parsed_url['scheme'] = 'http';
        }
        switch($type) {
            case 'domain':
                $url_final = $parsed_url['scheme'] . '://' . $parsed_url['host'];
                break;
            case 'path':
                $url_final = $parsed_url['path'];
                break;
            case 'no-http':
                $url_final = $parsed_url['host'];
                break;
            case 'all':
                $url_final = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'];
                break;
            case 'all_query':
                $url_final = $parsed_url['scheme'] . '://' . $parsed_url['host'] . $parsed_url['path'] . '?' . $parsed_url['query'];
                break;
            default:
                $url_final = $url;
                break;
        }
        return $url_final;
    }
}