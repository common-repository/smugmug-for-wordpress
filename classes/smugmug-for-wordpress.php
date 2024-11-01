<?php
/*
 * SmugMug for Wordpress - This plugin allows the integration of the SmugMug with Wordpress
 *
 * @author Anthony Humes <support@quantumdevonline.com>
 * @version 0.8.1
 * @package SMW
 * @license GPL 3 {@link http://www.gnu.org/copyleft/gpl.html}
 * @copyright Copyright (c) 2012 Anthony Humes
 * 
 */
/**
 * This class initializes the SmugMug for Wordpress plugin and provides access to all the 
 *
 * @package SMW
 **/
class SMW {
    private static $instance;
    protected $layoutFolder;
    protected $buy_links;
    protected $authorization;
    protected $frontend;
    protected $settings;
    /**
     * This variable is set in __construct and gives the type of lightbox script to use
     * 
     * @access protected
     * @var string
     */
    protected $lightbox_type;
    /**
     * Initializes the main SMW class
     * 
     * Sets the $lightbox_type variable
     * 
     * @access  private
     * @return  void 
     */
    private function __construct() {
         $this->lightbox_type = get_option('smw_lightbox_type');
    }
    /**
     * Used with the Singleton Design pattern to envoke this class
     * 
     * @access  public
     * @static
     * @return  SMW Object
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        $render = SMW_Views_Render::getInstance();
        return self::$instance;
    }
    /**
     * Renders a file in the block folder as html
     * 
     * Takes a string value which references a file in the blocks folder and renders that file into html. Example: 'admin/settings' This will call the file $plugin_folder/classes/blocks/admin/settings.php
     * 
     * @access  public
     * @static
     * @return  bool Whether or not the file rendered
     * @param   string $type The reference for the file. Ex. 'folder/file'
     */
    public function getBlock( $type ) {
        
        $path = SMW_CLASSES_DIR . 'blocks/'. $type . '.php';

        if (is_file($path))
        {
            // Render the html through a buffer
            ob_start();
                // Include the requested layout, which will echo the variables into the html
                include $path;

                // Assign the results to a variable we can use
                $contents = ob_get_contents();
            ob_end_clean();
            
            echo $contents;

            return true;
        }
        else // The requested layout wasn't there
        {
            echo "Error - the layout requested for rendering html does not exist." . $path . '.php';
            return false;
        }
    }
    /**
     * Renders a file in the template folder as html
     * 
     * Takes a string value which references a file in the template folder (either the folder **templates** in the plugin folder or the folder **smugmug** in the theme folder) and renders that file into html.
     * 
     * @example 'admin/settings' This will call the file $plugin_folder/classes/blocks/admin/settings.php
     * 
     * @access  public
     * @return  bool Whether or not the file rendered
     * @param   string $type The reference for the file. Ex. 'folder/file'
     * @param   bool $return If true, will return the contents of the file. If false, will echo the contents.
     */
    public function getTemplate( $type, $return = false ) {
        
        $path = SMW_TEMPLATE_DIR . $type . '.php';

        if (is_file($path))
        {
            // Render the html through a buffer
            ob_start();
                // Include the requested layout, which will echo the variables into the html
                include $path;

                // Assign the results to a variable we can use
                $contents = ob_get_contents();
            ob_end_clean();
            
            if($return) {
                return $contents;
            } else {
                echo $contents;
            }
            

            //return true;
        }
        else // The requested layout wasn't there
        {
            echo "Error - the layout requested for rendering html does not exist." . $path;
            return false;
        }
    }
    /**
     * Returns a model object to be used
     * 
     * Takes a string value which references an object in the **classes/models** and returns the object.
     * 
     * @example 'servce/album-comms' This will call the class **SMW_Models_Service_AlbumComms**
     * 
     * @access  public
     * @return  object Returns the object value
     * @param   string $type The reference for the file. Ex. 'folder/file'
     * @param   array $args Arguments to be passed to the object
     */
    public function getModel( $type, $args = null ) {
        $classes = explode( '/', $type );
        
        foreach( $classes as $class ) {
            $pos = strpos($class,'-');
            
            if( $pos === false ) {
                $newClass[] = ucfirst( $class );
            } else {
                $classNames = explode('-',$class);
                foreach( $classNames as $className ) {
                    $newClassNames[] = ucfirst( $className );
                }
                $newClass[] = implode( '', $newClassNames );
            }
        }
        
        $path = implode('_', $newClass);
        
        $classname = 'SMW_Models_' . $path;
        $class = call_user_func( $classname.'::getInstance', $args );
        return $class;
    }
    /**
     * Returns a helper object to be used
     * 
     * Takes a string value which references an object in the **classes/helpers** folder and returns the object.
     * 
     * @example 'add-ajax-options' This will call the class **SMW_Helpers_AddAjaxOptions**
     * 
     * @access  public
     * @return  object Returns the object value
     * @param   string $type The reference for the file. Ex. 'file'
     * @param   array $args Arguments to be passed to the object
     */
    public function getHelper( $type, $args = null ) {
        $classes = explode( '/', $type );
        
        foreach( $classes as $class ) {
            $pos = strpos($class,'-');
            
            if( $pos === false ) {
                $newClass[] = ucfirst( $class );
            } else {
                $classNames = explode('-',$class);
                foreach( $classNames as $className ) {
                    $newClassNames[] = ucfirst( $className );
                }
                $newClass[] = implode( '', $newClassNames );
            }
        }
        
        $path = implode('_', $newClass);
        
        $classname = 'SMW_Helpers_' . $path;
        $class = call_user_func( $classname.'::getInstance', $args );
        return $class;
    }
    /**
     * Returns a view object to be used
     * 
     * Takes a string value which references an object in the **classes/views** folder and returns the object.
     * 
     * @example 'admin/galleries/create' This will call the class **SMW_Views_Admin_Galleries_Create**
     * 
     * @access  public
     * @return  object Returns the object value
     * @param   string $type The reference for the file. Ex. 'folder/file'
     * @param   array $args Arguments to be passed to the object
     */
    public function getView( $type, $args = null ) {
        $classes = explode( '/', $type );
        
        foreach( $classes as $class ) {
            $pos = strpos($class,'-');
            
            if( $pos === false ) {
                $newClass[] = ucfirst( $class );
            } else {
                $classNames = explode('-',$class);
                foreach( $classNames as $className ) {
                    $newClassNames[] = ucfirst( $className );
                }
                $newClass[] = implode( '', $newClassNames );
            }
        }
        
        $path = implode('_', $newClass);
        
        $classname = 'SMW_Views_' . $path;
        $class = call_user_func( $classname.'::getInstance', $args );
        return $class;
    }
    /**
     * Returns an object to be used for Frontend control
     * 
     * Takes a string value which references an object in the **classes/controllers/frontend** folder and returns the object.
     * 
     * @example 'galleries' This will call the class **SMW_Controllers_Frontend_Galleries**
     * 
     * @access  public
     * @return  object Returns the object value
     * @param   string $type The reference for the file. Ex. 'folder/file'
     * @param   array $args Arguments to be passed to the object
     */
    public function getFrontend( $type, $args = null ) {
        $classes = explode( '/', $type );
        
        foreach( $classes as $class ) {
            $pos = strpos($class,'-');
            
            if( $pos === false ) {
                $newClass[] = ucfirst( $class );
            } else {
                $classNames = explode('-',$class);
                foreach( $classNames as $className ) {
                    $newClassNames[] = ucfirst( $className );
                }
                $newClass[] = implode( '', $newClassNames );
            }
        }
        
        $path = implode('_', $newClass);
        
        $classname = 'SMW_Controllers_Frontend_' . $path;
        $class = call_user_func( $classname.'::getInstance', $args );
        return $class;
    }
    /**
     * Returns a controller object
     * 
     * Takes a string value which references an object in the **classes/controllers** folder and returns the object.
     * 
     * @example 'admin/custom-post-type' This will call the class **SMW_Controllers_Admin_CustomPostType**
     * 
     * @access  public
     * @return  object Returns the object value
     * @param   string $type The reference for the file. Ex. 'folder/file'
     */
    public function getControl( $type ) {
        $classes = explode( '/', $type );
        
        foreach( $classes as $class ) {
            $pos = strpos($class,'-');
            
            if( $pos === false ) {
                $newClass[] = ucfirst( $class );
            } else {
                $classNames = explode('-',$class);
                foreach( $classNames as $className ) {
                    $newClassNames[] = ucfirst( $className );
                }
                $newClass[] = implode( '', $newClassNames );
            }
        }
        
        $path = implode('_', $newClass);
        
        $classname = 'SMW_Controllers_' . $path;
        $class = call_user_func( $classname.'::getInstance', $args );
        return $class;
    }
    /**
     * Returns an object to be used for controlling Item types
     * 
     * Takes a string value which references an object in the **classes/controllers/items/type** folder and returns the object.
     * 
     * @example 'galleries' This will call the class **SMW_Controllers_Items_Type_Galleries**
     * 
     * @access  public
     * @return  object Returns the object value
     * @param   string $type Defaults to 'galleries' The reference for the file. Ex. 'file'
     */
    public function getItem( $type = 'galleries' ) {
        $classes = explode( '/', $type );
        
        foreach( $classes as $class ) {
            $pos = strpos($class,'-');
            
            if( $pos === false ) {
                $newClass[] = ucfirst( $class );
            } else {
                $classNames = explode('-',$class);
                foreach( $classNames as $className ) {
                    $newClassNames[] = ucfirst( $className );
                }
                $newClass[] = implode( '', $newClassNames );
            }
        }
        
        $path = implode('_', $newClass);
        
        $classname = 'SMW_Controllers_Items_Type_' . $path;
        $class = call_user_func( $classname.'::getInstance', $args );
        return $class;
    }
    /**
     * Initializes the plugin and references all the funtions
     * 
     * @access  public
     * @return  void
     */
    public function plugin_init() {
        
        //print_r(TEMPLATE_URL);
        $activate = $this->getModel('wordpress/activation');
        //register_activation_hook( __FILE__, array(&$activate,'activatePlugin') );
        add_action('plugins_loaded',array(&$this,'admin_init'));
        add_action('admin_menu',array(&$this,'admin_menu'));
        if(get_option('smugpress_access_token')) {
            if(get_option('smw_access_token')) {
                delete_option('smugpress_access_token');
            } else {
                $old_option = get_option('smugpress_access_token');
                add_option('smw_access_token', $old_option);
                delete_option('smugpress_access_token');
                //add_action('admin_notices', array(&$this, 'no_activation_message'));
            }
        } else {
            if(get_option('smw_access_token')) {
                add_action('init',array(&$activate,'activatePlugin'));
                add_action('widgets_init', create_function('', 'return register_widget("SMW_Controllers_Admin_Widgets");'));
            } else {

                add_action('admin_notices', array(&$this, 'no_activation_message'));
            }
        }
        
        add_action('admin_enqueue_scripts', array(&$this,'load_custom_wp_admin_scripts'));
        add_filter('plugin_action_links', array(&$this, 'add_settings_link'), 10, 2 );
        if(!is_admin()) {
            add_action('wp_enqueue_scripts',array(&$this,'smw_front_end_scripts_styles'));
            add_action('wp_print_footer_scripts',array(&$this,'smw_front_footer_script'));
        } else {
            add_action('admin_print_footer_scripts',array(&$this,'smw_footer_script'));
        }
        
        $validate_var = get_option('smw_db_version');
        
        $documentation = get_option('smw_documentation');
        
        if($documentation != SMW_DOCS_VERSION) {
            add_action('wp_ajax_docuLink', array(&$this,'showDocsAjax'));
            add_action('admin_notices', array(&$this, 'showDocumentation'));
            add_action('admin_print_footer_scripts', array(&$this, 'showDocsJavascript'));
        }
        
        //echo $validate_var;
        
        if(!$validate_var) {
            $old_version = get_option('smp_default_smugmug_url');
            if($old_version) {
                add_action('wp_ajax_upgrade_database', array(&$this,'upgrade_database_callback'));
                add_action('admin_notices', array(&$this, 'upgrade_database_message'));
                add_action('admin_print_footer_scripts', array(&$this, 'upgrade_javascript'));
            }
        } elseif($validate_var != SMW_DB_VERSION) {
            add_action('wp_ajax_upgrade_database', array(&$this,'upgrade_database_callback'));
            add_action('admin_notices', array(&$this, 'upgrade_database_message'));
            add_action('admin_print_footer_scripts', array(&$this, 'upgrade_javascript'));
        }
    }
    /**
     * Echos this message if the database structure has been updated
     * 
     * @access  public
     * @return  void
     */
    public function upgrade_database_message() {
        echo '<div id="message" class="error smugmug-upgrade">
            <p><strong>SmugMug Upgrade</strong></p>
            <p id="upgrade-text"><span id="smw-upgrade-database" style="cursor:pointer;color:#C00;text-decoration:underline;">Click Here</span> to upgrade the Wordpress database to the current version of SmugMug for Wordpress</p>
            </div>';
    }
    /**
     * Echos this message if the documentation has been updated
     * 
     * @access  public
     * @return  void
     */
    public function showDocumentation() {
        echo '<div id="message" class="error smugmug-docs">
            <p><strong>Upgraded or Newly Installed and Having Issues?</strong></p>
            <p id="upgrade-text">There have been a lot of changes between 0.6.2 and 0.7, and this <strong>WILL BREAK</strong> your current installation. Please see our <a href="http://quantumdevonline.com/smugmug-for-wordpress/documentation" target="_blank">Documentation</a> or if you would like some personal attention <a href="http://quantumdevonline.com/smugmug-for-wordpress/questions-or-feedback" target="_blank">Contact Me</a>. To help with the template changes, I will be redoing any custom templates that worked with 0.6.2. Just go to this <a href="http://quantumdevonline.com/smugmug-for-wordpress/custom-template-updates" target="_blank">Form</a> and send me a zip file with the templates in it. Go here for an <a href="http://quantumdevonline.com/?p=351" target="_blank">Explanation</a> of why these changes were made.</p>
            <p id="upgrade-text"><span id="smw-documentation-link" style="cursor:pointer;color:#C00;text-decoration:underline;">Click Here</span> to close this message permantly.</p>
            </div>';
    }
    /**
     * Ajax function which updates the documentation version
     * 
     * @access  public
     * @return  void
     */
    public function showDocsAjax() {
        $documentation = get_option('smw_documentation');
        if(!$documentation) {
            add_option('smw_documentation',SMW_DOCS_VERSION);
        } elseif ($documentation != SMW_DOCS_VERSION) {
            update_option('smw_documentation',SMW_DOCS_VERSION);
        }
        die();
    }
    /**
     * Prints out the footer javascript which accepts the click on the documentation link and updates the database with the current version of the documetation
     * 
     * @access  public
     * @return  void
     */
    public function showDocsJavascript() {
        ?>
            <script type="text/javascript">
                jQuery(function($) {
                    $("#smw-documentation-link").click(function() {
                        var upgrade_data = {
                            action: 'docuLink'
                            };
                        $.post(ajaxurl,upgrade_data, function(data) {
                            setTimeout(function() {
                                jQuery('.smugmug-docs').fadeOut('fast');
                            }, 1000);
                        });
                    });
                });
                
            </script>
        <?php
    }
    /**
     * Prints out the footer javascript which accepts the click on the documentation link and updates the database with the current version of the documetation
     * 
     * @access  public
     * @return  void
     */
    public function upgrade_javascript() {
        ?>
            <script type="text/javascript">
                jQuery(function($) {
                    $("#smw-upgrade-database").click(function() {
                        var upgrade_data = {
                            action: 'upgrade_database'
                            };
                        $.post(ajaxurl,upgrade_data, function(data) {
                            $('#upgrade-text').html(data);
                            setTimeout(function() {
                                jQuery('.smugmug-upgrade').fadeOut('fast');
                            }, 1000);
                        });
                    });
                });
            </script>
        <?php
    }
    /**
     * Ajax function which updates the database structure
     * 
     * @access  public
     * @return  void
     */
    public function upgrade_database_callback() {
        $model = SMW::getModel('wordpress/activation');
        $response = $model->upgradeDatabase();
        if($response) {
            echo '<strong>Upgrade Successful!</strong>';
        } else {
            echo '<strong>Upgrade Failed!</strong>';
        }
        
        die();
    }
    /**
     * Echos the message which points to the settings page if the application hasn't been activated with SmugMug
     * 
     * @access  public
     * @return  void
     */
    public function no_activation_message() {
        echo '<div id="message" class="error"><p><strong>SmugMug for Wordpress Authorization Not Set</strong></p><p>SmugMug for Wordpress requires activation at SmugMug to work. Go to the <a href="'.SMW_CURRENT_URL.'/wp-admin/admin.php?page=smugmug-settings">Settings</a> page to set activation!</p></div>';
    }
    public function load_scripts() {
        
        
    }
    /**
     * WP Enqueue Script for all the frontend scripts
     * 
     * @access  public
     * @return  void
     */
    public function smw_front_end_scripts_styles() {
        global $post;
        if(get_option('smw_jquery_frontend') == 1) {
            wp_enqueue_script( 'jquery' );
        }
        //add_action('wp_print_footer_scripts',array(&$this,'load_scripts'),10);
        //wp_register_script( 'frontend', SMW_PLUGIN_URL . 'js/load_scripts.php?load=frontend&lightbox=' . $this->lightbox_type, 'jquery', '1.0' );
        //wp_register_script( 'smw_scripts', SMW_PLUGIN_URL . 'js/load_scripts.php?load=frontend&lightbox=' . $this->lightbox_type, 'jquery', '1.0' );
        //wp_enqueue_script('smw_scripts');
        if($this->lightbox_type == 'prettyphoto') {
            wp_register_script( 'smw_prettyphoto_script', SMW_PLUGIN_URL . 'external-code/prettyPhoto/js/jquery.prettyPhoto.js', 'jquery', '3.1.2' );
            
            wp_register_style( 'smw-prettyPhoto-style', SMW_PLUGIN_URL . 'external-code/prettyPhoto/css/prettyPhoto.css','3.1.2');
            wp_enqueue_script( 'smw_prettyphoto_script' );
            
            wp_enqueue_style( 'smw-prettyPhoto-style' );
        } elseif($this->lightbox_type == 'clearbox') {
            wp_register_script( 'smw_prettyphoto_script', SMW_PLUGIN_URL . 'external-code/clearbox/clearbox.js?dir='.SMW_PLUGIN_URL.'external-code/clearbox/clearbox', 'jquery', NULL );
            wp_enqueue_script( 'smw_prettyphoto_script' );
        }
        wp_register_script( 'smw-frontscripts', SMW_PLUGIN_URL . 'js/load_scripts.php?c=1&load=address,pagination,frontend,nimbleloader&post=' . $post->ID, array('jquery'));
        wp_enqueue_script('smw-frontscripts');
        wp_localize_script( 'smw-frontscripts', 'SMW', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        wp_register_style( 'smw-frontend-style', SMW_TEMPLATE_URL . 'smugmug.css', false, '1.0' );
        wp_enqueue_style( 'smw-frontend-style' );
    }
    /**
     * Prints the lightbox settings script on the frontend
     * 
     * @access  public
     * @return  void
     */
    public function smw_front_footer_script() {

        if($this->lightbox_type == 'prettyphoto'):
        if(!is_admin()) :
            global $post;
            ?>
            
            <script type="text/javascript">
              jQuery(function($){
                $("a[rel^='prettyPhoto']").prettyPhoto({
                    animation_speed: '<?php echo get_option( 'smw_lightbox_animation' ); ?>',
                    slideshow: <?php echo (get_option( 'smw_lightbox_slideshow' ) == 0 ? 'false' : get_option('smw_lightbox_slideshow')); ?>,
                    autoplay_slideshow: <?php echo (get_option( 'smw_lightbox_auto_slide' ) == 1 ? 'true' : 'false'); ?>,
                    opacity: <?php echo get_option( 'smw_lightbox_opacity' ); ?>,
                    show_title: <?php echo (get_option( 'smw_lightbox_show_title' ) == 1 ? 'true' : 'false'); ?>,
                    allow_resize: <?php echo (get_option( 'smw_lightbox_allow_resize' ) == 1 ? 'true' : 'false'); ?>,
                    theme: '<?php echo get_option( 'smw_lightbox_template' ); ?>',
                    deeplinking: <?php echo (get_option( 'smw_lightbox_deeplinking' ) == 1 ? 'true' : 'false'); ?>,
                    overlay_gallery: <?php echo (get_option( 'smw_lightbox_allow_resize' ) == 1 ? 'true' : 'false'); ?>,
                    keyboard_shortcuts: <?php echo (get_option( 'smw_lightbox_keyboard_shortcuts' ) == 1 ? 'true' : 'false'); ?>
                });
              });
            </script>
        <?php endif; endif;
    }
    /**
     * Prints the javascript activates the prettyPhoto script
     * 
     * @access  public
     * @return  void
     */
    public function smw_footer_script() {
        ?>
        <script type="text/javascript" charset="utf-8">
            jQuery(function($){
                prettyPhoto_init();
                //prettyLoader_init();
            });
        </script>
        <?php
    }
    /**
     * Returns the current filename
     * 
     * @access  public
     * @return  string The current filename
     */
    public function get_current_filename() {
        $currentFile = $_SERVER["SCRIPT_NAME"];
        $parts = Explode('/', $currentFile);
        return end($parts);
    }
    /**
     * Runs the functions that enables admin functionality
     * 
     * @access  public
     * @return  void
     */
    public function admin_init() {
        $custom_post_type = SMW::getControl('admin/custom-post-type');
        $page = SMW::getControl('frontend/page');
        $this->getHelper('add-ajax-options');
        $shortcodes = $this->getControl('admin/shortcodes');
        $shortcodes->createShortcodes();
        add_action('init',array(&$custom_post_type, 'createPostType'));
        add_action('admin_menu',array(&$custom_post_type, 'addOptionsToPage'));
        add_action('template_redirect', array(&$page,'templateRedirect'), 5);

    }
    /**
     * Finds out if a script is enqueued by Wordpress
     * 
     * @access  public
     * @return  void
     */
    public function is_enqueued_script( $script ) {
        return isset( $GLOBALS['wp_scripts']->registered[ $script ] );
    }
    /**
     * Loads styles in the Wordpress admin for use by the plugin
     * 
     * @access  public
     * @return  void
     */
    public function load_admin_styles() {
        wp_register_style( 'smw_style', SMW_PLUGIN_URL . 'css/smugmug-admin.css', false, '2.2' );
        wp_enqueue_style( 'smw_style' );
        
        wp_register_style( 'smw_prettyphoto_style', SMW_PLUGIN_URL . 'external-code/prettyPhoto/css/prettyPhoto.css', false, '3.1.3' );
        wp_enqueue_style( 'smw_prettyphoto_style' );
        
        wp_register_style( 'jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/themes/smoothness/jquery-ui.css' );
        wp_enqueue_style( 'jquery-ui-style' );
    }
    /**
     * Loads scripts in the Wordpress admin for use by the plugin
     * 
     * @access  public
     * @return  void
     */
    public function load_admin_scripts() {
        wp_register_script( 'smw_prettyphoto_script', SMW_PLUGIN_URL . 'external-code/prettyPhoto/js/jquery.prettyPhoto.js', 'jquery', '3.1.3' );
        wp_enqueue_script( 'smw_prettyphoto_script' );
        
        wp_register_script( 'smw_nimbleloader_script', SMW_PLUGIN_URL . 'external-code/nimble-loader/jquery.nimble.loader.js', 'jquery', '1.0.0' );
        wp_enqueue_script( 'smw_nimbleloader_script' );
        
        wp_register_script( 'smw_address_script', SMW_PLUGIN_URL . 'js/jquery.address-1.4.min.js', array('jquery'), '1.4' );
        wp_enqueue_script( 'smw_address_script' );
        
        wp_register_script( 'smw_admin_script', SMW_PLUGIN_URL . 'js/smugmug-admin.js', array('jquery','smw_nimbleloader_script'), '1.0' );
        wp_enqueue_script( 'smw_admin_script' );
        
        if($this->is_enqueued_script( 'jquery-ui' ))
            wp_enqueue_script( 'jquery-ui' );
        if($this->is_enqueued_script( 'jquery-ui-dialog' ))
            wp_enqueue_script( 'jquery-ui-dialog' );
    }
    /**
     * Loads styles and scripts for the WP Admin
     * 
     * @access  public
     * @return  void
     */
    function load_custom_wp_admin_scripts(){
        $this->load_admin_styles();
        $this->load_admin_scripts();
    }
    /**
     * Loads the functions that creates the WP admin pages and the WP admin menu
     * 
     * @access  public
     * @return  void
     */
    public function admin_menu() {
        $settingsView = $this->getView( 'admin/settings' );
        $albumsView = $this->getView('admin/galleries');
        $overView = $this->getView('admin/overview');
        $categoriesView = $this->getView('admin/categories');
        add_menu_page('SmugMug for Wordpress','SmugMug', 'manage_options', 'smugmug-plugin', array($overView,'showPage'), SMW_CURRENT_URL .'/wp-content/plugins/smugmug-for-wordpress/smugmug-icon.png');
        add_submenu_page('smugmug-plugin','SmugMug Overview','Overview','manage_options','smugmug-plugin','');
            if(get_option('smw_access_token')) {
                add_submenu_page('smugmug-plugin','Manage Galleries | SmugMug','Manage Galleries','manage_options',
                                'smugmug-manage-galleries',array($albumsView,'showPage'),10);
                add_submenu_page('smugmug-manage-albums','Create Gallery | SmugMug','Create Gallery','manage_options',
                                'smugmug-create-gallery',array($albumsView,'showCreatePage'),10);
                add_submenu_page('smugmug-manage-albums','Edit Gallery | SmugMug','Edit Gallery','manage_options',
                                'smugmug-edit-gallery',array($albumsView,'showEditPage'),10);
                add_submenu_page('smugmug-plugin','Manage Categories | SmugMug','Manage Categories','manage_options',
                                'smugmug-manage-categories',array($categoriesView,'showPage'),10);
            }
        add_submenu_page('smugmug-plugin','Settings | SmugMug','Settings','manage_options',
            'smugmug-settings',array($settingsView,'showPage'),10);

    }
    /**
     * Parses a url and returns the value
     * 
     * @access  public
     * @return  void
     * @param   string $url The url to be parsed
     * @param   string $type Possible values are 'domain' (returns the domain), 'path'(returns the path), & 'no-http'(returns the domain without http/https). Defaults to 'domain'. 
     */
    public function parse_url( $url, $type = 'domain' ) {
        $parsed_url = parse_url($url);
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
            default:
                $url_final = $url;
                break;
        }
        return $url_final;
    }
    function add_settings_link($links, $file) {
        static $this_plugin;
        if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

        if ($file == $this_plugin){
            $settings_link = '<a href="'. SMW_CURRENT_URL .'/wp-admin/admin.php?page=smugmug-settings">'.__("Settings", "smugmug-galleries").'</a>';
        array_unshift($links, $settings_link);
        }
        return $links;
    }
    /**
     * Renders a file and either returns the contents or echos them out
     * 
     * @deprecated Since 0.5
     * @access  public
     * @return  mixed
     * @param   string $layout 
     * @param   string $class 
     * @param   string $type 
     * @param   array $params 
     * @param   string $render_type Two options 'normal' & 'shortcode' If normal, will echo otherwise returns the content
     */
    public function render($layout=null,$class=null,$type=null,$params = null,$render_type = "normal")
    {
        global $smw_render;
        global $smw_images;
        
        $layout = $smw_render->layout_file($layout,$type);

        $this->layoutFolder = $smw_render->render_template_path($class,$type,$layout);
        
        $images_obj = $smw_images;
        
        // Check if a layout was specified
        if ($layout)
        {

                // Construct the include path for the layout based on the path in the config file
                $filename = $this->layoutFolder . $layout . ".php";

                // Check to see if the file exists
                if (is_file($filename))
                {
                        // Render the html through a buffer
                        ob_start();
                                // Include the requested layout, which will echo the variables into the html
                                include $filename;

                                // Assign the results to a variable we can use
                                $contents = ob_get_contents();
                        ob_end_clean();
                        if($render_type == 'normal') {
                            echo $contents;
                        } elseif($render_type == 'shortcode') {
                            return $contents;
                        }

                        return true;
                }
                else // The requested layout wasn't there
                {
                        echo "Error - the layout requested for rendering html does not exist." . $this->layoutFolder . $layout . '.php';
                        return false;
                }
        }
        else // No layout was specified
        {
                echo "Error - no layout was specified";
                return false;
        }
        
        //$smw_render->render($layout,$class,$type,$params,$render_type,$object_passed);
    }
}