<?php

/**
 * SMW_Controllers_Admin_Categories - Controls the Categories section in admin
 *
 * @package SMW_Controllers
 * @subpackage Admin
 * @author Anthony Humes
 **/
class SMW_Controllers_Admin_Categories extends SMW_Controllers_Abstract {
    /**
     * This variable is set in __construct and provides access to the forms helper
     * 
     * @access protected
     * @var Object|SMW_Helpers_Forms
     */
    protected $forms;
    /**
     * This variable is set in __construct and provides access to the Category item controller
     * 
     * @access protected
     * @var Object|SMW_Controllers_Items_Type_Categories
     */
    protected $db;
    /**
     * This variable is set in __construct and provides a list of all categories
     * 
     * @access protected
     * @var Object|SMW_Controllers_Items_Type_Categories
     */
    protected $categories;
    /**
     * This variable is set in __construct and provides an array of all categories created by the user
     * 
     * @access protected
     * @var Object|SMW_Controllers_Items_Type_Categories
     */
    protected $user_categories;


    /**
    * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
    * 
    * @access private
    * @var Object|SMW_Controllers_Admin_Categories
    */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @see SMW_Helpers_Forms
     * @see SMW_Controllers_Items_Type_Categories
     * @see SMW_Controllers_Items_Type_Categories::getForm()
     * @see SMW_Models_Wordpress_Items_Galleries::tableExists()
     * @return  void
     */
    private function __construct() {
        $this->forms = SMW::getHelper('forms');
        $this->db = SMW::getItem('categories');
        $this->categories = $this->db->getForm();
        $this->user_categories = $this->db->getForm( true );
        $default_option = array('value' => 'select','name'=>'Select Category');
        $table_exists = SMW::getModel('wordpress/items/galleries')->tableExists('smw_galleries');
        //array_shift($this->categories);
        if($table_exists) {
                array_shift($this->user_categories);
                array_unshift($this->categories, $default_option);
                array_unshift($this->user_categories, $default_option);
        }
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Admin_Categories
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Returns an array of galleries that are only in the Service and not in the WP database
     * 
     * @access  public
     * @return  void
     */
    public function scripts() {
        add_action('admin_print_footer_scripts', array(&$this,'viewJavascript'));
    }
    /**
     * Echos the javascript for the admin categories page
     * 
     * @access  public
     * @return  void
     */
    public function viewJavascript() {
        SMW::getBlock('admin/categories/javascript');
    }
    /**
     * Returns the meta boxes for the create category section
     * 
     * @access  public
     * @return  array
     */
    private function categoryCreate() {
        $meta_boxes = array(
            'new_category_name' => array( 'name' => 'Name', 'title' => __('Name', 'smw'), 'type' => 'text', 'description' => 'New Category Title', 'size' => 40, 'required' => true ),
            'new_category_nice_name' => array( 'name' => 'NiceName', 'title' => __('Nice Name', 'smw'), 'type' => 'text', 'description' => 'Nice url name for the category. Leave blank to have it automatically created.')
         );

        return apply_filters( 'smw_meta_boxes', $meta_boxes );
    }
    /**
     * Returns the meta boxes for the delete category section
     * 
     * @access  public
     * @return  array
     */
    private function categoryDelete() {
        $meta_boxes = array(
            'delete_category_name' => array( 'name' => 'CategoryID', 'options' => $this->user_categories,'title' => __('Category', 'smw'), 'type' => 'select', 'description' => 'New Category Title', 'size' => 40, 'required' => true )
         );

        return apply_filters( 'smw_meta_boxes', $meta_boxes );
    }
    /**
     * Returns the meta boxes for the rename category section
     * 
     * @access  public
     * @return  array
     */
    private function categoryRename() {
        $meta_boxes = array(
            'new_category_id' => array( 'name' => 'CategoryIDRename', 'title' => __('Category to Rename', 'smw'), 'type' => 'select', 'options' => $this->user_categories, 'description' => 'Category to change name', 'required' => true ),
            'new_category_name' => array( 'name' => 'CategoryRenameNew', 'title' => __('New Name', 'smw'), 'type' => 'text', 'description' => 'New name for your category', 'required' => true)
         );

        return apply_filters( 'smw_meta_boxes', $meta_boxes );
    }
    /**
     * Returns the meta boxes for the create sub-category section
     * 
     * @access  public
     * @return  array
     */
    private function subCategoryCreate() {
        $meta_boxes = array(
            'category' => array( 'name' => 'CategoryIDNewSub', 'options' => $this->categories, 'title' => __('Parent Category', 'smw'), 'type' => 'select', 'required' => true ),
            'new_sub_category_name' => array( 'name' => 'SubCategoryNameNew', 'title' => __('Name', 'smw'), 'type' => 'text', 'description' => 'New Sub-Category Name', 'size' => 40, 'required' => true ),
            'new_sub_category_nice_name' => array( 'name' => 'SubCategoryNiceNew', 'title' => __('Nice Name', 'smw'), 'type' => 'text', 'description' => 'Nice url name for the sub-category. Leave blank to have it automatically created.')
         );

        return apply_filters( 'smw_meta_boxes', $meta_boxes );
    }
    /**
     * Returns the meta boxes for the delete sub-category section
     * 
     * @access  public
     * @return  array
     */
    private function subCategoryDelete() {
        $sub_categories = array(
            array('name' => 'None','value' => ''),
        );
        $meta_boxes = array(
            'category' => array( 'name' => 'CategoryIDDeleteSub', 'options' => $this->categories, 'title' => __('Parent Category', 'smw'), 'type' => 'select', 'size' => 40, 'required' => true, 'section_id' => 'parent-category-delete' ),
            'delete_sub_category_name' => array( 'name' => 'SubCategoryIDDelete', 'title' => __('Sub-Category', 'smw'), 'options' => $sub_categories, 'type' => 'select', 'size' => 40, 'required' => true, 'section_id' => 'sub-category-delete' )
         );

        return apply_filters( 'smw_meta_boxes', $meta_boxes );
    }
    /**
     * Returns the meta boxes for the rename category section
     * 
     * @access  public
     * @return  array
     */
    private function subCategoryRename() {
        $sub_categories = array(
            array('name' => 'None','value' => ''),
        );
        $meta_boxes = array(
            'category' => array( 'name' => 'CategoryIDRenameSub', 'options' => $this->categories, 'title' => __('Parent Category', 'smw'), 'type' => 'select', 'size' => 40, 'required' => true ),
            'sub_category' => array( 'name' => 'SubCategoryIDRename', 'options' => $sub_categories, 'title' => __('Sub-Category', 'smw'), 'type' => 'select', 'size' => 40, 'required' => true ),
            'rename_sub_category_name' => array( 'name' => 'SubCategoryNameRename', 'title' => __('New Name', 'smw'), 'type' => 'text', 'size' => 40, 'required' => true )
         );

        return apply_filters( 'smw_meta_boxes', $meta_boxes );
    }
    /**
     * Echos the form sections for the category page.
     * 
     * @access  public
     * @param   string $type Possible options category-create|category-delete|category-rename|sub-category-create|sub-category-delete|sub-category-rename
     * @return  array
     */
    public function getForm( $type ) {
        switch( $type ) {
            case 'category-create':
                $meta_boxes = $this->categoryCreate();
                $this->forms->createFormTable( $meta_boxes, 2, false );
                break;
            case 'category-delete':
                $meta_boxes = $this->categoryDelete();
                $this->forms->createFormTable( $meta_boxes, 2, false );
                break;
            case 'category-rename':
                $meta_boxes = $this->categoryRename();
                $this->forms->createFormTable( $meta_boxes, 2, false );
                break;
            case 'sub-category-create':
                $meta_boxes = $this->subCategoryCreate();
                $this->forms->createFormTable( $meta_boxes, 2, false );
                break;
            case 'sub-category-delete':
                $meta_boxes = $this->subCategoryDelete();
                $this->forms->createFormTable( $meta_boxes, 2, false );
                break;
            case 'sub-category-rename':
                $meta_boxes = $this->subCategoryRename();
                $this->forms->createFormTable( $meta_boxes, 2, false );
                break;
        }
    }
}