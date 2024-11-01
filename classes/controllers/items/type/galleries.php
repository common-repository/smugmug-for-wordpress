<?php

/**
 * SMW_Controllers_Items_Type_Galleries - Controls the galleries item type
 * 
 * @package SMW_Controllers_Items
 * @subpackage Type
 * @author Anthony Humes
 **/
class SMW_Controllers_Items_Type_Galleries extends SMW_Controllers_Items_CompositeItem {
    /**
     * This variable is set in imagesAjax() and provides access to the Galleries model
     * 
     * @access  protected
     * @see     SMW_Models_Wordpress_Items_Galleries
     * @var     int The current page
     */
    protected $db;
    /**
     * This variable is set in imagesAjax() and provides access to the Service Gallery model
     * 
     * @access  protected
     * @see     SMW_Models_Service_GalleryComms
     * @var     object|SMW_Models_Service_GalleryComms
     */
    protected $service;
    /**
     * This variable is set in imagesAjax() and provides access to the Service Category model
     * 
     * @access  protected
     * @see     SMW_Models_Service_CategoryComms
     * @var     object|SMW_Models_Service_CategoryComms
     */
    protected $category;
    /**
     * This variable is set in imagesAjax() and provides access to the Service SubCategory model
     * 
     * @access  protected
     * @see     SMW_Models_Service_SubCategoryComms
     * @var     object|SMW_Models_Service_SubCategoryComms
     */
    protected $sub_category;
    /**
     * This variable is set in imagesAjax() and provides the total number of pages
     * 
     * @access  protected
     * @see     SMW_Controllers_Items_Type_Images::imagesAjax()
     * @var     array The array of images to be used in the pagination
     */
    protected $images;
    /**
    * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
    * 
    * @access private
    * @var object|SMW_Models_Wordpress_Items_Images
    */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @return  void
     */
    private function __construct() {
        $this->db = $this->getItemDb('galleries');
        $this->service = $this->getItemService('gallery');
        $this->category = $this->getItemService('category');
        $this->sub_category = $this->getItemService('sub-category');
        $this->images = $this->getItemDb('images');
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Items_Type_Galleries
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Returns an integer of the count of galleries
     * 
     * @access  public
     * @param   bool $passworded If true, returns the count of passworded galleries
     * @see     SMW_Models_Wordpress_Items_Galleries::getCount()
     * @return  int The count of galleries
     */
    public function getCount( $passworded = false ) {
        if($passworded) {
            return $this->db->getCount( $passworded );
        } else {
            return $this->db->getCount();
        }
    }
    /**
     * Returns a gallery item
     * 
     * @access  public
     * @param   int $int The ID of the gallery
     * @param   bool $passworded If true, returns the the gallery item as an array, else returns the gallery item as an object
     * @see     SMW_Models_Wordpress_Items_Galleries::selectItem()
     * @return  array|object The gallery item in an array or object
     */
    public function getItem( $id, $array = null ) {
        return $this->db->selectItem( $id, $array );
    }
    /**
     * Returns an array of sizes of galleries
     * 
     * @access  public
     * @param   string $sizes Specifies the largest possible size
     * @return  array An array of sizes of galleries
     */
    public function gallerySizes( $sizes ) {
        $gallery_sizes = array(
            'Originals' => 0,
            'X3Larges' => 0,
            'X2Larges' => 0,
            'XLarges' => 0,
            'Larges' => 0,
        );
        switch( $sizes ) {
            case 'Originals':
                $gallery_sizes['Originals'] = 1;
                $gallery_sizes['X3Larges'] = 1;
                $gallery_sizes['X2Larges'] = 1;
                $gallery_sizes['XLarges'] = 1;
                $gallery_sizes['Larges'] = 1;
                break;
            case 'X3Larges':
                $gallery_sizes['X3Larges'] = 1;
                $gallery_sizes['X2Larges'] = 1;
                $gallery_sizes['XLarges'] = 1;
                $gallery_sizes['Larges'] = 1;
                break;
            case 'X2Larges':
                $gallery_sizes['X2Larges'] = 1;
                $gallery_sizes['XLarges'] = 1;
                $gallery_sizes['Larges'] = 1;
                break;
            case 'XLarges':
                $gallery_sizes['XLarges'] = 1;
                $gallery_sizes['Larges'] = 1;
                break;
            case 'Larges':
                $gallery_sizes['Larges'] = 1;
                break;
        }
        return $gallery_sizes;
    }
    /**
     * Ajax function for deleting a user created gallery
     * 
     * @access  public
     * @return  html
     */
    public function delete_gallery_callback() {
        $id = $_POST['gallery_id'];
        $name = $_POST['name'];
        $response = $this->service->deleteItem( $id );
        if(!$response) {
            echo "<td colspan='9'><div id='message' class='updated'><p><strong>Gallery {$name} Deleted</strong></p><p>The album {$name} was successfully deleted! All pictures and data were deleted too from ".SMW_SERVICE.".</p></div></td>";
        } else {
            echo "<td colspan='9'><div id='message' class='error'><p><strong>Gallery {$name} Failed to be Deleted</strong></p><p>The album {$name} was was not deleted from ".SMW_SERVICE.".</p></div></td>";
        }
        die();
    }
    /**
     * Ajax function for editing a gallery
     * 
     * @access  public
     * @return  html
     */
    public function editGalleryAjax() {
        $form_fields = $_POST['form_data'];
        $form_fields = explode('&',$form_fields);
        foreach($form_fields as $form_field) {
            $form_data = explode('=',$form_field);
            if($form_data[0] == 'Sizes') {
                $form_sizes = $this->gallerySizes( $form_data[1] );
                //print_r($form_sizes);
                foreach( $form_sizes as $key => $form_size ){
                    $form_information[$key] = $form_size;
                }
            } elseif($form_data[0] == 'Password') {
                if($form_data[1]) {
                    $form_information['Passworded'] = 1;
                    $form_information[$form_data[0]] = str_replace('+', ' ', $form_data[1]);
                } else {
                    $form_information['Passworded'] = 0;
                    $form_information[$form_data[0]] = str_replace('+', ' ', $form_data[1]);
                }
                
            } else {
                $form_information[$form_data[0]] = str_replace('+', ' ', $form_data[1]);
            }
        }
        $update = $this->updateItem( $form_information, $_POST['id'] );
        print_r($form_information);
        die();
    }
    /**
     * Updates a gallery in both Wordpress and the Service
     * 
     * @access  public
     * @param   array $item The gallery to be updated
     * @param   array $id The id of the gallery to be updated
     * @see     SMW_Models_Wordpress_Items_Galleries::updateItem()
     * @see     SMW_Models_Service_GalleryComms::updateItem()
     * @return  array An array of responses
     */
    public function updateItem( $item, $id ) {
        $service = $item;
        $response['wordpress'] = $this->db->updateItem( $item, $id );
        $service['AlbumID'] = $id;
        $response['service'] = $this->service->updateItem( $service );
        return $response;
    }
    /**
     * Gets only galleries that are not in the Wordpress database
     * 
     * @access  public
     * @param   array $item The gallery to be updated
     * @param   array $id The id of the gallery to be updated
     * @see     SMW_Models_Wordpress_Items_Galleries::selectItems()
     * @see     SMW_Models_Abstract::arraySearch()
     * @see     SMW_Models_Service_GalleryComms::getItems()
     * @return  array An array of galleries
     */
    public function getServiceOnly() {
        $service_galleries = $this->service->getItems();
        $wordpress_galleries = $this->db->selectAll('id',true);
        
        foreach($service_galleries as $gallery) {
            $exists = $this->arraySearch($gallery['id'], $wordpress_galleries);
            
            if(!$exists) {
                $idKey = $gallery['id'] . '|' . $gallery['Key'];
                $galleries[] = $this->service->getItems($idKey);
            }
        }
        return $galleries;
    }
    /**
     * Gets all galleries that are in the Wordpress database
     * 
     * @access  public
     * @see     SMW_Models_Wordpress_Items_Galleries::selectAll()
     * @return  array An array of galleries
     */
    public function getAll() {
        return $this->db->selectAll();
    }
    /**
     * Gets a list of galleries in a format to be used in forms
     * 
     * @access  public
     * @see     SMW_Models_Wordpress_Items_Galleries::selectAll()
     * @return  array An array of gallery values and names
     */
    public function getAllForms() {
        $galleries = $this->db->selectAll( NULL, true);
        for($j = 0; $j < count($galleries); $j++) {
                $result_list[$j]['value'] = $galleries[$j]['gallery_id'];
                $result_list[$j]['name'] = $galleries[$j]['title'];
        }
        return $result_list;
    }
    /**
     * Saves the gallery to WP and the service
     * 
     * @access  public
     * @param   array $form_fields The fields for the edit or create gallery form
     * @see     SMW_Models_Wordpress_Items_Galleries::addItem()
     * @see     SMW_Models_Service_CategoryComms::createItem()
     * @see     SMW_Models_Service_SubCategoryComms::createItem()
     * @see     SMW_Models_Service_GalleryComms::createItem()
     * @see     SMW_Models_Service_GalleryComms::getItems()
     * @return  array|false Returns an array if successful | false if unsuccessful
     */
    public function saveItem($form_fields) {
        foreach( $form_fields as $key => $form_field ) {
            if(!empty($form_field) || $form_field == '0') {
                $form_data[$key] = $form_field;
            }
        }
        if(!empty($form_data['NewCategoryName'])) {
            $form_data['CategoryID'] = $this->category->createItem( array('Name' => $form_data['NewCategoryName']) );
        }
        if(!empty($form_data['NewSubCategoryName'])) {
            $form_data['SubCategoryID'] = $this->sub_category->createItem( array('CategoryID' => $form_data['CategoryID'],'Name' => $form_data['NewSubCategoryName']) );
        }
        unset($form_data['SaveToDatabase']);
        unset($form_data['NewCategoryName']);
        unset($form_data['NewSubCategoryName']);
        $new_gallery = $this->service->createItem($form_data);
        if($form_fields['SaveToDatabase']) {
            $idKey = $new_gallery['id'] . '|' . $new_gallery['Key'];
            $gallery = $this->service->getItems($idKey);
            $this->db->addItem($gallery);
        }
        if($new_gallery) {
            return $new_gallery;
        } else {
            return false;
        }
        
    }
    public function getUrl() {
        
    }
    public function getThumbUrl() {
        
    }
    public function getCaption() {
        
    }
    /**
     * Returns the image count for a gallery
     * 
     * @access  public
     * @param   int $id The gallery to get the count from
     * @see     SMW_Models_Wordpress_Items_Galleries::selectVar()
     * @return  int The count of images for a gallery
     */
    public function getImageCount( $id ) {
        return $this->db->selectVar('image_count', $id);
    }
    /**
     * Ajax call which prints the admin list of Galleries on the Manage Galleries page
     * 
     * @access  public
     * @see     SMW_Helpers_Pagination
     * @see     SMW_Helpers_Pagination::total_pages()
     * @see     SMW_Helpers_Pagination::offset()
     * @see     SMW_Controllers_Items_Type_Galleries::select()
     * @see     SMW_Controllers_Items_Type_Galleries::getCount()
     * @see     SMW_Controllers_Items_Type_Galleries::getServiceOnly()
     * @return  html The table which holds the galleries in the backend
     */
    public function getGallery_callback() {
        extract($_POST);
        if($type == 'img_number') {
            $_SESSION['gallery_number_' . $location] = $number;
        }
        if($type == 'pagination') {
            if($_SESSION['gallery_number_' . $location] && $_SESSION['gallery_number_' . $location] != $number) {
                $number = $_SESSION['gallery_number_' . $location];
            }
        }
        switch($gall_type) {
            case 'wordpress':
                $this->currentPage = $page_number >= 1 ? $page_number : 1;
                $this->count = $this->getCount();
                $this->pagination = new SMW_Helpers_Pagination($this->currentPage, $number, $this->count);
                $this->totalPages = $this->pagination->total_pages();
                if($this->totalPages > 1) {
                    $this->offset = $number != 'All' ? $this->pagination->offset() : -1;
                } else {
                    $this->offset = -1;
                }
                
                $this->galleries = $this->select($this->offset, $number);
                //print_r($number);
                if($this->galleries) {
                    SMW::getBlock('admin/galleries/wordpress-galleries');
                } else {
                    echo "<div id='message' class='updated'><p><strong>No " . SMW_SERVICE . " Galleries are in Wordpress</strong></p><p>All of your " . SMW_SERVICE . " galleries are NOT in the Wordpress database.</p></div>";
                }
                break;
            case 'service':
                $this->service_galleries = $this->getServiceOnly();
                if($this->service_galleries) {
                    SMW::getBlock('admin/galleries/service-galleries');
                } else {
                    echo "<div id='message' class='updated'><p><strong>All " . SMW_SERVICE . " Galleries in Wordpress</strong></p><p>All of your " . SMW_SERVICE . " galleries are in the Wordpress database.</p><p>If you have recently excluded some galleries, click the button below to refresh this list</p><button style='display: block; margin: 0 auto 10px;' class='button-secondary' id='get-service'>Refresh " . SMW_SERVICE . " Galleries</button></div>";
                }
                
                break;
        }
        
        die();
    }
    /**
     * Prints the admin list of Galleries on the Manage Galleries page
     * 
     * @access  public
     * @param   int $offset The offset of where to start the pagination
     * @param   int $number The number of galleries to return. If -1 it will return all
     * @see     SMW_Controllers_Items_Type_Galleries::getAll()
     * @see     SMW_Models_Wordpress_Items_Gallereies::select()
     * @return  html The table which holds the galleries in the backend
     */
    public function select($offset, $number = 10) {
        
        $offset = (int)$offset;
        
        if($offset == -1) {
            return $this->getAll();
        } else {
            return $this->db->select($offset, $number);
        }
    }
    /**
     * Syncs all galleries in the WP database with the Service
     * 
     * @access  public
     * @see     SMW_Models_Wordpress_Items_Gallereies::selectVars()
     * @see     SMW_Models_Wordpress_Items_Gallereies::updateItem()
     * @see     SMW_Models_Service_GalleryComms::getItems()
     * @see     SMW_Models_Wordpress_Items_Images::removeGallery()
     * @see     SMW_Models_Wordpress_Items_Images::addGallery()
     * @return  void
     */
    public function syncAllGalleries_callback() {
        $galleries = $this->db->selectVars( 'gallery_id, gallery_key, last_updated, image_count', true );
        foreach($galleries as $gallery) {
            $idKey = $gallery['gallery_id'] .'|'. $gallery['gallery_key'];
            $service = $this->service->getItems( $idKey );
            
            $new['last_updated'] = array($gallery['last_updated'],$service['LastUpdated']);
            $new['image_count'] = array($gallery['image_count'],$service['ImageCount']);
            if($gallery['last_updated'] != $service['LastUpdated'] || $gallery['image_count'] != $service['ImageCount']) {
                
                $this->images->removeGallery( $gallery['gallery_id'] );
                $images = $this->images->addGallery( $idKey );
                //$this->removeItem( $gallery['gallery_id'] );
                
            }
            $this->db->updateItem( $service, $gallery['gallery_id'] );
            //print_r($service);
        }
        
        die();
    }
    /**
     * Add all galleries in Service to WP database
     * 
     * @access  public
     * @see     SMW_Controllers_Items_Type_Galleries::getServiceOnly()
     * @see     SMW_Models_Wordpress_Items_Images::addGallery()
     * @return  void
     */
    public function addAllGalleries() {
        $this->galleries = $this->getServiceOnly();
        if($this->galleries) {
            foreach($this->galleries as $gallery) {
                $this->db->addItem( $gallery );
                $idKey = $gallery['id'].'|'.$gallery['Key'];
                $this->images->addGallery( $idKey );
            }
        }
    }
    /**
     * Add all galleries in Service to WP database
     * 
     * @access  public
     * @see     SMW_Controllers_Items_Type_Galleries::removeItem()
     * @see     SMW_Controllers_Items_Type_Galleries::getAll()
     * @return  void
     */
    public function excludeAllGalleries_callback() {
        $this->galleries = $this->getAll();
        foreach($this->galleries as $gallery) {
            $this->removeItem( $gallery->gallery_id );
        }
        die();
    }
    /**
     * Ajax call to add gallery to the WP database
     * 
     * @access  public
     * @see     SMW_Controllers_Items_Type_Galleries::addItem()
     * @return  void
     */
    public function add_gallery_callback() {
        $images = $this->addItem( $_POST['gallery_id_key'] );
        
        die();
    }
    /**
     * Returns a boolean on whether or not a gallery is passworded
     * 
     * @access  public
     * @param   int $id The id of the gallery
     * @see     SMW_Models_Wordpress_Items_Galleries::selectVar()
     * @return  bool
     */
    public function isPassworded( $id ) {
        return $this->db->selectVar( 'passworded',$id );
    }
    /**
     * Returns a string which is the gallery password
     * 
     * @access  public
     * @param   int $id The id of the gallery
     * @see     SMW_Models_Wordpress_Items_Galleries::selectVar()
     * @return  string The gallery password
     */
    public function getPassword( $id ) {
        return $this->db->selectVar( 'password',$id );
    }
    /**
     * Returns a string which is the gallery password hint
     * 
     * @access  public
     * @param   int $id The id of the gallery
     * @see     SMW_Models_Wordpress_Items_Galleries::selectVar()
     * @return  string The gallery password hint
     */
    public function getPasswordHint( $id ) {
        return $this->db->selectVar( 'password_hint',$id );
    }
    /**
     * Adds a gallery to the wordpress database and returns the images
     * 
     * @access  public
     * @param   string $idKey The id|key of the gallery to add
     * @see     SMW_Models_Service_GalleryComms::getItems()
     * @see     SMW_Controllers_Items_Type_Images::addGallery()
     * @return  array The images from the gallery
     */
    public function addItem( $idKey ) {
        $gallery = $this->service->getItems( $idKey );
        $this->db->addItem( $gallery );
        $images = $this->images->addGallery( $idKey );
        return $images;
    }
    /**
     * Ajax call which adds galleries to the database
     * 
     * @access  public
     * @see     SMW_Controllers_Items_Type_Galleries::addItem()
     * @see     SMW_Controllers_Items_Type_Galleries::addAllGalleries()
     * @return  string The gallery password
     */
    public function addGalleries() {
        switch( $_POST['type'] ) {
            case 'single':
                $this->addItem( $_POST['id'] );
                break;
            case 'all':
                $this->addAllGalleries();
                echo "<div id='message' class='updated'><p><strong>All " . SMW_SERVICE . " Galleries in Wordpress</strong></p><p>All of your " . SMW_SERVICE . " galleries are in the Wordpress database.</p><p>If you have recently excluded some galleries, click the button below to refresh this list</p><button style='display: block; margin: 0 auto 10px;' class='button-secondary' id='get-service'>Refresh " . SMW_SERVICE . " Galleries</button></div>";
                break;
            case 'bulk':
                //print_r($_POST['id']);
                foreach($_POST['id'] as $id ){
                    $this->addItem( $id );
                }
                break;
        }
        die();
    }
    /**
     * Ajax call which removes galleries from the WP database
     * 
     * @access  public
     * @see     SMW_Controllers_Items_Type_Galleries::getAll()
     * @see     SMW_Controllers_Items_Type_Galleries::removeItem()
     * @return  string The gallery password
     */
    public function excludeGalleries() {
        switch( $_POST['type'] ) {
            case 'single':
                $this->removeItem( $_POST['id'] );
                break;
            case 'all':
                $this->galleries = $this->getAll();
                foreach($this->galleries as $gallery) {
                    $this->removeItem( $gallery->gallery_id );
                }
                echo "<div id='message' class='updated'><p><strong>No " . SMW_SERVICE . " Galleries are in Wordpress</strong></p><p>All of your " . SMW_SERVICE . " galleries are NOT in the Wordpress database.</p></div>";
                break;
            case 'bulk':
                foreach($_POST['id'] as $id ){
                    $this->removeItem( $id );
                }
                break;
        }
        die();
    }
    /**
     * Removes items from the wordpress database
     * 
     * @access  public
     * @param   int $id Gallery id to remove
     * @see     SMW_Controllers_Items_Type_Galleries::removeItem()
     * @return  void
     */
    public function removeItem( $id, $service = false ) {
        if( $service ) {
            $this->service->removeItem( $id );
        } elseif($service == 'all') {
            $this->db->removeItem( $id );
            $this->service->removeItem( $id );
        } else {
            $this->db->removeItem( $id );
        }
        
    }
    
    public function getTemplate() {
        
    }
}
