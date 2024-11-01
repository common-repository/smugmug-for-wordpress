<?php

/**
 * SMW_Controllers_Admin_Galleries_Edit - Controls the admin settings page
 * 
 * @package SMW_Controllers_Admin
 * @subpackage Galleries
 * @author  Anthony Humes
 **/
class SMW_Controllers_Admin_Galleries_Edit extends SMW_Controllers_Abstract {
    /**
     * This variable is set in __construct and provides access to the Galleries controller
     * 
     * @access private
     * @var Object|SMW_Controllers_Items_Type_Galleries
     */
    protected $item;
    /**
     * This variable is set in __construct and provides access to the Images controller
     * 
     * @access private
     * @var Object|SMW_Controllers_Items_Type_Images
     */
    protected $images;
    /**
     * This variable is set in __construct and sets the id of the gallery
     * 
     * @access private
     * @var int
     */
    protected $id;
    /**
     * This variable is set in __construct and sets the service key
     * 
     * @access private
     * @var string
     */
    protected $key;
    /**
     * This variable is set in __construct and loads the gallery for use
     * 
     * @access private
     * @see SMW_Controllers_Items_Type_Galleries::getItem()
     * @var array
     */
    protected $gallery;
    /**
     * This variable is set in __construct and provides access to the SubCategories controller
     * 
     * @access private
     * @var Object|SMW_Controllers_Items_Type_SubCategories
     */
    protected $sub_category;
    /**
     * This variable is set in __construct and provides access to the Categories controller
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
     * @var Object|SMW_Controllers_Admin_Galleries_Edit
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
     * @see     SMW_Controllers_Items_Type_Images
     * @see     SMW_Helpers_Forms
     * @return  void
     */
    private function __construct() {
        $this->item = $this->getItemControl( 'galleries' );
        $this->sub_category = $this->getItemControl( 'sub-categories' );
        $this->category = $this->getItemControl( 'categories' );
        $this->images = $this->getItemControl( 'images' );
        $this->forms = SMW::getHelper( 'forms' );
        $this->id = $_GET['id'];
        $this->gallery = $this->item->getItem( $this->id, true );
        $this->gallery['extras'] = unserialize($this->gallery['extras']);
        $this->key = $this->gallery['Key'];
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Admin_Galleries_Edit
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function getGalleryImages( $id ) {
        $images = $this->images->getAll( $id, true );
        for($i = 0; $i <= (count($images) - 1); $i++) {
            $images[$i]['extras'] = unserialize($images[$i]['extras']);
        }
        return $images;
    }
    public function getItem() {
        $gallery = $this->item->getItem( $this->id, true );
        $gallery['extras'] = unserialize($gallery['extras']);
        return $gallery;
    }
    /**
     * Returns an array of fields to be used in the edit gallery section
     * 
     * @access  public
     * @return  array
     */
    public function createFields() {
        $categories = $this->category->formList();
        
        $gallery = $this->item->getItem( $this->id, true );
        
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
            array('name' => 'Original', 'value' => '', 'description' => 'Keep the aspect ratio of your photos'),
            array('name' => 'Square', 'value' => '1', 'description' => 'Crop thumbnails into squares')
        );
        $sort_direction = array(
            array('name' => 'Ascending', 'value' => '', 'description' => '(1-99, A-Z, 1980-2004, etc)'),
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
            'gallery_title' => array( 'name' => 'Title', 'title' => __('Title', 'smw'), 'value' => urldecode($gallery['title']), 'type' => 'text', 'description' => 'New Album Title', 'size' => 40, 'required' => true ),
            'gallery_description' => array( 'name' => 'Description', 'title' => __('Description', 'smw'), 'value' => $gallery['description'], 'type' => 'textarea', 'description' => 'Please give a few words about this album.'),
            'gallery_keywords' => array( 'name' => 'Keywords', 'title' => __('Keywords', 'smw'), 'value' => $gallery['keywords'], 'type' => 'text', 'size' => 40 ),   
            'gallery_public' => array( 'name' => 'Public', 'title' => __('Publically Viewable', 'smw'), 'value' => $gallery['public'], 'type' => 'boolean' ),            
            'gallery_category' => array( 'name' => 'CategoryID', 'options' => $categories, 'title' => __('Category', 'smw'), 'value' => $gallery['category_id'], 'type' => 'select' ),            
            'gallery_new_category' => array( 'name' => 'NewCategoryName', 'title' => __('New Category Name', 'smw'), 'type' => 'text', 'section_id' => 'new-category', 'size' => 40  ),            
            'gallery_subcategory' => array( 'name' => 'SubCategoryID', 'options' => $subcategories, 'title' => __('Sub-Category', 'smw'), 'value' => $gallery['sub_category_id'], 'type' => 'select' ),            
            'gallery_new_subcategory' => array( 'name' => 'NewSubCategoryName', 'title' => __('New Sub-Category Name', 'smw'), 'type' => 'text', 'section_id' => 'new-subcategory', 'size' => 40  ),            
            'gallery_password' => array( 'name' => 'Password', 'value' => $gallery['password'], 'title' => __('Password', 'smw'), 'type' => 'text', 'size' => 40  ),            
            'gallery_password_hint' => array( 'name' => 'PasswordHint', 'value' => $gallery['password_hint'], 'title' => __('Password Hint', 'smw'), 'type' => 'text', 'size' => 40  ),            
            'gallery_thumbnails' => array( 'name' => 'SquareThumbs', 'options' => $thumb_sizes, 'title' => __('Thumbnails', 'smw'), 'value' => $gallery['thumbnails'], 'type' => 'radio' ),            
            'gallery_sizes' => array( 'name' => 'Sizes', 'options' => $sizes, 'title' => __('Largest Viewable Picture Size', 'smw'), 'type' => 'radio' ),            
            'gallery_sort_direction' => array( 'name' => 'SortDirection', 'options' => $sort_direction, 'title' => __('Sort Direction', 'smw'), 'value' => $gallery['sort_direction'], 'type' => 'radio' ),            
            'gallery_method' => array( 'name' => 'SortMethod', 'options' => $sortMethod, 'title' => __('Custom Sort Method', 'smw'), 'value' => $gallery['sort_method'], 'type' => 'select' )
        );
        return apply_filters( 'smw_post_meta_boxes', $meta_boxes );
    }
    /**
     * Returns the form which is used in the edit gallery section
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
        SMW::getBlock( 'admin/galleries/edit/javascript' );
    }
    /**
     * Calls the javascript function
     * 
     * @access  public
     * @return  void
     */
    public function scripts() {
        $this->gallery = $this->item->getItem($this->id,true);
        add_action('admin_print_footer_scripts', array(&$this,'settingsJava'));
    }
}