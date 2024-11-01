<?php
/*
Plugin Name: SmugMug for Wordpress
Plugin URI: http://www.quantumdevonline.com/smugmug-for-wordpress/
Description: Create, Manage, and Show public and private SmugMug galleries on Wordpress websites/blogs.
Author: Anthony Humes
Version: 0.8.1
Author URI: http://www.quantumdevonline.com/

Copyright 2011 by Anthony Humes

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

if(!session_id())
session_start();

DEFINE('SMW_MIN_PHP_VERSION','5.1.2');

//print_r(PHP_VERSION);

if( PHP_VERSION >= SMW_MIN_PHP_VERSION ) {

    DEFINE('SMW_SERVICE','SmugMug');
    
    DEFINE('SMW_API_KEY','E8iGXpLViTw02u3j84HPvssubUiMPnc0');
    DEFINE('SMW_SECRET_KEY','1eb7da3f4eb60f9e045bde78c0596050');

    DEFINE('SMW_PLUGIN_TITLE','SmugMug for Wordpress');
    DEFINE('SMW_PLUGIN_NAME','smugmug-for-wordpress');

    DEFINE('SMW_VERSION','0.8');
    DEFINE('SMW_DB_VERSION','1.0');
    DEFINE('SMW_DOCS_VERSION','1.0');

    DEFINE('SMW_PLUGIN_DIR',WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
    DEFINE('SMW_PLUGIN_URL',plugins_url('/smugmug-for-wordpress/'));

    DEFINE('SMW_CLASSES_DIR',SMW_PLUGIN_DIR . 'classes/');

    DEFINE('SMW_CONTROLLERS_DIR',SMW_CLASSES_DIR . 'controllers/');
    DEFINE('SMW_VIEWS_DIR',SMW_CLASSES_DIR . 'views/');
    DEFINE('SMW_MODELS_DIR',SMW_CLASSES_DIR . 'models/');

    DEFINE('SMW_CURRENT_URL',home_url());
    $template_dir = get_template_directory() . '/smugmug/';
    
    //echo($template_dir);
    if(is_dir($template_dir)) {
        DEFINE('SMW_TEMPLATE_DIR',$template_dir);
        DEFINE('SMW_ALT_TEMPLATE_DIR',SMW_PLUGIN_DIR . 'templates/');
        DEFINE('SMW_TEMPLATE_URL', get_bloginfo('stylesheet_directory') . '/smugmug/');
    } else {
        DEFINE('SMW_TEMPLATE_DIR',SMW_PLUGIN_DIR . 'templates/');
        DEFINE('SMW_TEMPLATE_URL',SMW_PLUGIN_URL . 'templates/');
    }

    require_once( SMW_CLASSES_DIR . 'helpers/autoloader.php' );

    SMW_Loader::registerAutoload();

    require_once(SMW_CLASSES_DIR . "smugmug-for-wordpress.php");
    
    
    $smw = SMW::getInstance();

    $smw->plugin_init();

} else {
    
    function badPhpVersion() {
        echo '<div id="message" class="error badPhpVersion">
            <p><strong>Bad PHP Version</strong></p>
            <p>'. SMW_PLUGIN_TITLE .' requires at least PHP version <strong>'. SMW_MIN_PHP_VERSION .'</strong> to function properly. Please contact your system administrator and ask them to upgrade your PHP version. Your current version of PHP is <strong>'. PHP_VERSION .'</strong>. If you don\'t want to continue to see this message, please deactivate '. SMW_PLUGIN_TITLE .'</p>
            </div>';
    }
    
    add_action('admin_notices', 'badPhpVersion');
    
}
// Add settings link on plugin page
function smw_plugin_settings_link($links) { 
    $settings_link = '<a href="admin.php?page=smugmug-settings">Settings</a>'; 
    array_unshift($links, $settings_link); 
    return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'smw_plugin_settings_link' );