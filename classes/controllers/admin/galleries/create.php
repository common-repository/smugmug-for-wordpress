<?php

/**
 * SMW_Controllers_Admin_Galleries_Create - Controls the admin create gallery page
 * 
 * @package SMW_Controllers_Admin
 * @subpackage Galleries
 * @author  Anthony Humes
 **/
class SMW_Controllers_Admin_Galleries_Create extends SMW_Controllers_Abstract {
    /**
     * This variable is set in __construct and provides access to the form helper
     * 
     * @access private
     * @var Object|SMW_Controllers_Items_Type_Galleries
     */
    protected $gallery;
    /**
     * This variable is set in __construct and provides access to the form helper
     * 
     * @access private
     * @var Object|SMW_Controllers_Items_Type_SubCategories
     */
    protected $sub_category;
    /**
     * This variable is set in __construct and provides access to the form helper
     * 
     * @access private
     * @var Object|SMW_Controllers_Items_Type_Categories
     */
    protected $category;
    /**
     * This variable is set in __construct and provides access to the form helper
     * 
     * @access private
     * @var Object|SMW_Helpers_Forms
     */
    protected $forms;
    /**
     * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
     * 
     * @access private
     * @var Object
     * @see SMW::getHelper()
     */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @see     SMW_Controllers_Items_Type_Galleries
     * @see     SMW_Controllers_Items_Type_SubCategories
     * @see     SMW_Controllers_Items_Type_Categories
     * @see     SMW_Helpers_Forms
     * @return  void
     */
    private function __construct() {
        $this->gallery = $this->getItemControl( 'galleries' );
        $this->sub_category = $this->getItemControl( 'sub-categories' );
        $this->category = $this->getItemControl( 'categories' );
        $this->forms = SMW::getHelper( 'forms' );
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Admin_Settings
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Callback function for the Create Gallery ajax on the frontend. Creates the Gallery and saves it to the WP database and to the service
     * 
     * @access  public
     * @return  void
     */
    public function gallery_create_callback() {
        $gallery = SMW::getItem('galleries');
        $form_fields = explode( '&', $_POST['form_data'] );
        foreach( $form_fields as $form_field ) {
            $form_field = explode( '=', $form_field );
            $form_data[$form_field[0]] = addslashes(urldecode(str_replace('+',' ',$form_field[1])));
        }
        if(!empty($form_data['Title'])) {
            $response = $gallery->saveItem($form_data);
        } else {
            echo 'No Title';
        }
        if($response) {
            echo "&id={$response['id']}&key={$response['Key']}";
        } else {
            echo 'Fail';
        }
        
        die();
    }
    /**
     * Returns an array of fields to be used in the create fields
     * 
     * @access  public
     * @return  array
     */
    public function createFields() {
        $categories = $this->category->formList();
        
        array_push($categories,array('value' => 'new', 'name' => 'Create New'));
        
        $sizes = array(
            array( 'name' => 'Larges', 'value' => 'Larges', 'description' => 'Up to 800px wide or 600px high' ),
            array( 'name' => 'X Large', 'value' => 'XLarges', 'description' => 'Up to 1024px wide or 768px high' ),
            array( 'name' => 'X Large 2', 'value' => 'X2Larges', 'description' => 'Up to 1280px wide or 960px high' ),
            array( 'name' => 'X Large 3', 'value' => 'X3Larges', 'description' => 'Up to 1600px wide or 1200px high' ),
            array( 'name' => 'Original', 'value' => 'Originals', 'checked' => true ),
        );
        $sortMethod = array(
            array( 'name' => 'Select Sort Method', 'value' => '' ),
            array( 'name' => 'Default (Position)', 'value' => 'Position' ),
            array( 'name' => 'By Caption', 'value' => 'Caption' ),
            array( 'name' => 'By filenames', 'value' => 'Filename' ),
            array( 'name' => 'By date uploaded', 'value' => 'Date' ),
            array( 'name' => 'By date modified (if available)', 'value' => 'DateTime' ),
            array( 'name' => 'By date taken (if available)', 'value' => 'DateTimeOriginal' )
        );
        
        $thumb_sizes = array(
            array('name' => 'Original', 'value' => '0', 'description' => 'Keep the aspect ratio of your photos', 'checked' => true),
            array('name' => 'Square', 'value' => '1', 'description' => 'Crop thumbnails into squares')
        );
        $sort_direction = array(
            array('name' => 'Ascending', 'value' => '0', 'description' => '(1-99, A-Z, 1980-2004, etc)', 'checked' => true),
            array('name' => 'Descending', 'value' => '1', 'description' => '(99-1, Z-A, 2004-1980, etc)')
        );
        
        $subcategories = array(
            array('name' => 'None', 'value' => ''),
            array('name' => 'Create New', 'value' => 'new')
        );
        
        $add_to_database = array(
            array('title' => '', 'value' => 1, 'checked' => true)
        );
        
        /* Array of the meta box options. */
        $meta_boxes = array(
            'new_gallery_title' => array( 'name' => 'Title', 'title' => __('Title', 'smw'), 'type' => 'text', 'description' => 'New Album Title', 'size' => 40, 'required' => true ),
            'new_gallery_description' => array( 'name' => 'Description', 'title' => __('Description', 'smw'), 'type' => 'textarea', 'description' => 'Please give a few words about this album.'),
            'new_gallery_keywords' => array( 'name' => 'Keywords', 'title' => __('Keywords', 'smw'), 'type' => 'text', 'size' => 40 ),   
            'new_gallery_public' => array( 'name' => 'Public', 'title' => __('Publically Viewable', 'smw'), 'type' => 'boolean' ),            
            'new_gallery_category' => array( 'name' => 'CategoryID', 'options' => $categories, 'title' => __('Category', 'smw'), 'type' => 'select' ),            
            'new_gallery_new_category' => array( 'name' => 'NewCategoryName', 'title' => __('New Category Name', 'smw'), 'type' => 'text', 'section_id' => 'new-category', 'size' => 40  ),            
            'new_gallery_subcategory' => array( 'name' => 'SubCategoryID', 'options' => $subcategories, 'title' => __('Sub-Category', 'smw'), 'type' => 'select' ),            
            'new_gallery_new_subcategory' => array( 'name' => 'NewSubCategoryName', 'title' => __('New Sub-Category Name', 'smw'), 'type' => 'text', 'section_id' => 'new-subcategory', 'size' => 40  ),            
            'new_gallery_password' => array( 'name' => 'Password',  'title' => __('Password', 'smw'), 'type' => 'text', 'size' => 40  ),            
            'new_gallery_password_hint' => array( 'name' => 'PasswordHint', 'title' => __('Password Hint', 'smw'), 'type' => 'text', 'size' => 40  ),            
            'new_gallery_thumbnails' => array( 'name' => 'SquareThumbs', 'options' => $thumb_sizes, 'title' => __('Thumbnails', 'smw'), 'type' => 'radio' ),            
            'new_gallery_sizes' => array( 'name' => 'Sizes', 'options' => $sizes, 'title' => __('Largest Viewable Picture Size', 'smw'), 'type' => 'radio' ),            
            'new_gallery_sort_direction' => array( 'name' => 'SortDirection', 'options' => $sort_direction, 'title' => __('Sort Direction', 'smw'), 'type' => 'radio' ),            
            'new_gallery_method' => array( 'name' => 'SortMethod', 'options' => $sortMethod, 'title' => __('Custom Sort Method', 'smw'), 'type' => 'select' ),            
            'new_gallery_save_database' => array( 'name' => 'SaveToDatabase', 'options' => $add_to_database, 'title' => __('Save to Wordpress Database', 'smw'), 'type' => 'checkbox' ),            

        );

        return apply_filters( 'smw_post_meta_boxes', $meta_boxes );
    }
    /**
     * Returns the form which is used in the create gallery section
     * 
     * @access  public
     * @return  array
     */
    public function getForm() {
        $this->form_items = $this->createFields();
        return $this->forms->createFormTable( $this->form_items, 2, false );
    }
    /**
     * Echos the javascript for the create galleries page
     * 
     * @access  public
     * @return  void
     */
    public function settingsJava() {
        SMW::getBlock( 'admin/galleries/create/javascript' );
    }
    /**
     * Calls the javascript function
     * 
     * @access  public
     * @return  void
     */
    public function scripts() {
        add_action('admin_print_footer_scripts', array(&$this,'settingsJava'));
    }
}
