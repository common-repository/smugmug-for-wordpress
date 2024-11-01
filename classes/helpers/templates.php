<?php

/**
 * SMW_Helpers_Templates - Controls the admin settings page
 * 
 * @package SMW
 * @subpackage Helpers
 * @author  Anthony Humes
 **/
class SMW_Helpers_Templates {
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object|SMW_Controllers_Admin_Galleries_Edit
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
        
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Helpers_Templates
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Returns the file extension from a file
     * 
     * @access  public
     * @return  string The file extension
     */
    function getFileExtension($file_name) {
        return substr(strrchr($file_name,'.'),1);
    }
    /**
     * List of files a folder
     * 
     * @access  public
     * @param   string $directory_base_path The path to the folder
     * @param   ignored $type 
     * @return  string The file extension
     */
    function directoryList($directory_base_path,$type) {
        $directory_base_path = rtrim($directory_base_path, "/") . "/";

            // create an array to hold directory list
        $results = array();

        // create a handler for the directory
        $handler = opendir($directory_base_path);
        $i = 0;
        // open directory and walk through the filenames
        while ($file = readdir($handler)) {
            $extension = $this->getFileExtension($file);

            // if file isn't this directory or its parent, add it to the results
            if(!is_dir($directory_base_path . $file) && $extension == 'php') {
                if ($file != "." && $file != "..") {
                    $withoutExt = pathinfo($file, PATHINFO_FILENAME);
                    $results[] = $withoutExt;
                }
            }
            $i++;
        }

        // tidy up: close the handler
        closedir($handler);

        // done!
        return $results;
    }
    /**
     * Gets a template
     * 
     * @access  public
     * @param   string $directory_base_path The path to the template dir
     * @param   string $type The kind of template to return
     * @param   string $name The name of the template
     * @return  string The file extension
     */
    function getTemplates($template_dir,$type=false,$name) 
    {
        //ALT_TEMPLATE_DIR
        if($type == 'gallery') {
            $gallery_dir = $template_dir .'galleries/';
            if(is_dir($gallery_dir)) {
                $template_dir = $gallery_dir;
            } else {
                $template_dir = SMW_ALT_TEMPLATE_DIR .'galleries/';
            }

        } elseif($type == 'widget') {
            $widget_dir = $template_dir .'widgets/';
            if(is_dir($widget_dir)) {
                $template_dir = $widget_dir;
            } else {
                $template_dir = SMW_ALT_TEMPLATE_DIR .'widgets/';
            }
        }

        $templates = $this->directoryList($template_dir,$type);

        $post_templates = array();

        if ( is_array( $templates ) ) {


            $j = 0;
            foreach ( $templates as $template ) {
                $basename = $template_dir . $template.'.php';//str_replace($base, '', $template['value'].'.php');

                $template_data[$j] = htmlentities(implode( '', file( $basename )));

                if ( preg_match( '|'.$name.':(.*)$|mi', $template_data[$j], $name_new ) )
                    $name_new = _cleanup_header_comment( $name_new[1] );

                if ( !empty( $name_new ) && !is_array($name_new) ) {
                    $post_templates[$j]['value'] = $template;
                    $post_templates[$j]['name'] = $name_new;
                }
                $j++;
            }
        }

        return $post_templates;
    }
}
