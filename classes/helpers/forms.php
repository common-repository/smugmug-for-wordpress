<?php
global $post_var;
$post_var == $_POST;
/**
 * SMW_Helpers_Forms - Controls the admin settings page
 * 
 * @package SMW
 * @subpackage Helpers
 * @author  Anthony Humes
 **/
class SMW_Helpers_Forms {
    public $post;
    public $meta_boxes;
    public $columns;
    public $args;
    public $value;
    public $url;
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object|SMW_Helpers_Forms
     * @see SMW::getHelper()
     */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @see     SMW_Controllers_Items_Type_Galleries
     * @return  void
     */
    private function __construct() {
        $this->url = SMW::getHelper('url');
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Helpers_Forms
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className();
        }
        return self::$instance;
    }
    /**
     * Renders the layout for the class
     * 
     * @access  public
     * @return  void
     */
    public function renderer( $layout=false ) {
        $this->class = get_class();
        $this->render($layout,$this->class);
    }
    /**
     * Creates the form table
     * 
     * @access  public
     * @param   array $meta_boxes The fields to be used in the create form block
     * @param   int $columns Number of columns for the meta boxes
     * @param   bool $is_post If true, on a post page
     * @return  void
     */
    function createFormTable( $meta_boxes, $columns = 1, $is_post = true ) {
        if($is_post) {
            global $post;
            $this->post = $post;
        }
        
        $this->is_post = $is_post;
        
        //print_r($_POST);
         
        $this->meta_boxes = $meta_boxes;
        
        $this->columns = $columns;
        
        SMW::getBlock('helpers/forms/form-table');
        
        //$this->renderer('form-table');
        
    }
    /**
     * Generates the table to be used
     * 
     * @access  public
     * @param   array $meta The fields to be used in the create form block
     * @param   int $columns Number of columns for the meta boxes
     * @param   bool $is_post If true, on a post page
     * @return  void
     */
    function generateTable($meta, $columns, $is_post) {
        
        if($is_post) {
            $value = stripslashes( get_post_meta( $this->post->ID, $meta['name'], true ) );
            if(!$value) {
                $value = $meta['default_value'];
            }
            
        } else {
            $value = stripslashes( get_option( $meta['name']) );
        }
        if($meta['value'] ? $value = $meta['value'] : $value = $value);
        return $this->smw_field_creation( $meta, $value, $columns, $is_post );
    }

    /**
     * Generates a field
     * 
     * @access  public
     * @param   array $args Holds the values for the form
     * @param   bool $value
     * @param   int $columns Number of columns for the meta boxes
     * @param   bool $is_post If true, on a post page
     * @return  void
     */
    public function smw_field_creation( $args=false, $value=false, $columns=1, $is_post = false ) {
        $this->args = $args;
        $this->value = $value;
        $this->columns = $columns;
        $this->is_post = $is_post;
        
        SMW::getBlock('helpers/forms/input-' . $args['type']);
    }
    /**
     * Generates a field
     * 
     * @access  public
     * @param   array $posted_data Data to be saved
     * @return  void
     */
    function save_form_ajax( $posted_data ) {
        $i = 0;
        foreach($posted_data as $data) {
            $_setting = explode('=',$data);
            $_name = str_replace(array('%5B','%5D'),'',$_setting[0]);
            $changes[$_name] = str_replace('+',' ',$_setting[1]);
            $i++;
        }
        foreach($changes as $name => $data) {
            $option = get_option( $name );
            if($name == 'smw_default_service_url') {
                $data = $this->url->parse($data,'no-http');
                $data = esc_url_raw($data);
            }
            if($option == '' && $data != '') {
                $success = add_option($name,$data,'','yes');
            } elseif ( $data != $option && $data != '' ) {
                $success = update_option($name,$data);
            } elseif ( $data == '' ) {
                $success = delete_option( $meta_box['name'] );
            }
        }
        return $success;
    }
    /**
     * Generates a field
     * 
     * @access  public
     * @param   array $meta_boxes List of fields
     * @param   bool $is_post If true, the post
     * @return  void
     */
    function saveForm( $meta_boxes, $is_post ) {
        
        //if($this->get_current_filename() != admin.php) {
            $post_id = $_POST['post_ID'];
        //}

        foreach ( $meta_boxes as $meta_box ) {
            
            if ( !wp_verify_nonce( $_POST[$meta_box['name'] . '_noncename'], $meta_box['name'] ) )
                return $post_id;
            
            //if ( 'smp-album' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id ) )
                //return $post_id;
            
            $data = stripslashes( $_POST[$meta_box['name']] );
            if($is_post) {
                $smp_option = get_post_meta( $post_id, $meta_box['name'], true );
                if ( $smp_option == '' && $data != '' )
                    add_post_meta( $post_id, $meta_box['name'], $data, true );
                elseif ( $data != $smp_option && $data != '' )
                    update_post_meta( $post_id, $meta_box['name'], $data );

                elseif ( $data == '' )
                    delete_post_meta( $post_id, $meta_box['name'], get_post_meta( $post_id, $meta_box['name'], true ) );
            } else {
                $smp_option = get_option( $meta_box['name'] );
                if ( $smp_option == '' && $data != '' )
                    add_option( $meta_box['name'], $data, '', 'yes' );
                elseif ( $data != $smp_option && $data != '' )
                    update_option( $meta_box['name'], $data );

                elseif ( $data == '' )
                    delete_option( $meta_box['name'] );
            }

        }
    }
}