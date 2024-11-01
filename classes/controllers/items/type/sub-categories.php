<?php

/**
 * SMW_Controllers_Items_Type_SubCategories - Controls the subcategory item type
 * 
 * @package SMW_Controllers_Items
 * @subpackage Type
 * @author Anthony Humes
 **/
class SMW_Controllers_Items_Type_SubCategories extends SMW_Controllers_Items_Item {
    /**
     * This variable is set in __construct and provides access to the SubCategory model
     * 
     * @access protected
     * @var Object|SMW_Models_Items_Type_SubCategories
     */
    protected $db;
    /**
    * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
    * 
    * @access private
    * @var Object|SMW_Controllers_Items_Type_SubCategories
    */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @return  void
     */
    private function __construct() {
        $this->db = $this->getItemDb( 'sub-categories' );
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Items_Type_SubCategories
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Makes changes to Sub Categories in the admin through an Ajax function and returns a message
     * 
     * @access public
     * @see SMW_Models_Items_Type_SubCategories::createItem()
     * @see SMW_Models_Items_Type_SubCategories::deleteItem()
     * @see SMW_Models_Items_Type_SubCategories::renameItem()
     * @return html
     */
    public function subCategoryAdminChange() {
        $unformatted_data = explode('&',$_POST['form_data']);
        foreach($unformatted_data as $key => $data) {
            $form_info = explode('=',$data);
            $form_info[1] = str_replace('+', ' ', $form_info[1]);
            $form_data[$form_info[0]] = $form_info[1];
        }
        
        switch($form_data['FormType']) {
            case 'subcategory-create':
                if($form_data['CategoryIDNewSub'] == 'select') {
                    $subcategory['CategoryID'] = '0';
                } else {
                    $subcategory['CategoryID'] = $form_data['CategoryIDNewSub'];
                }
                $subcategory['Name'] = $form_data['SubCategoryNameNew'];
                $subcategory['NiceName'] = $form_data['SubCategoryNiceNew'];
                $response = $this->db->createItem( $subcategory );
                if($response) {
                    $completed['message'] = "<div id='message' class='updated'><p><strong>Sub-Category <em>{$response['name']}</em> Created</strong></p><p>The category {$response['name']} has been created successfully.</p></div>";
                    $completed = json_encode($completed);
                    echo $completed;
                } else {
                    $completed['message'] = '<div class="message error"><p><strong>Sub-Category Creatation Failed</strong></p><p>Wordpress failed to create the gallery '.$subcategory['Name'].'.</p></div>';
                    $completed = json_encode($completed);
                    echo $completed;
                }
                break;
            case 'subcategory-delete':
                $subcategory['SubCategoryID'] = $form_data['SubCategoryIDDelete'];
                $response = $this->db->deleteItem( $subcategory );
                if( $response ) {
                    $completed['message'] = '<div id="message" class="updated"><p><strong>Sub-Category <em>'.$response['name'].'</em> Deleted</strong></p><p>The category '.$response['name'].' has been created successfully.</p></div>';
                    $completed = json_encode( $completed );
                    echo $completed;
                } else {
                    $completed['message'] = '<div id="message" class="error"><p><strong>Sub-Category Deletion Failed</strong></p><p>Wordpress and/or SmugMug failed to delete the sub-category.</p></div>';;
                    $completed = json_encode( $completed );
                    echo $completed;
                }
                
                break;
            case 'subcategory-rename':
                $args['SubCategoryID'] = $form_data['SubCategoryIDRename'];
                $args['Name'] = $form_data['SubCategoryNameRename'];
                
                $response = $this->db->renameItem( $args );
                
                if( $response ) {
                    $completed['message'] = '<div id="message" class="updated"><p><strong>Sub-Category <em>'.$response['name'].'</em> Renamed to <em>'.$args['Name'].'</em></strong></p><p>The category '.$response['sub_category_name'].' has been created successfully.</p></div>';;
                    $completed = json_encode($completed);
                    echo $completed;
                } else {
                    $completed['message'] = '<div id="message" class="error"><p><strong>Sub-Category Deletion Failed</strong></p><p>Wordpress and/or SmugMug failed to delete the sub-category.</p></div>';;
                    $completed = json_encode( $completed );
                    echo $completed;
                }
                break;
        }
        die();
    }
    /**
     * Gets all the subcategories associated with a category and returns the html options for a select box
     * 
     * @access public
     * @see SMW_Models_Items_Type_SubCategories::selectItems()
     * @return html
     */
    public function subcategories_get_callback() {
        
        $category_id = $_POST['category_id'];
        $subcategory_id = $_POST['subcategory_id'];
        $subcategory_manage = $_POST['subcategory_manage'];
        
        $page = $_POST['page'];
        
        if($category_id != 'new') {
                $subcategories = $this->db->selectItems( $category_id );
                if($subcategories) {
                    echo '<option value="">Select Sub Category</option>';
                    if(!$page) {
                        echo '<option value="0">None</option>';
                    }
                    foreach($subcategories as $subcategory) {
                            if($subcategory_id == $subcategory->sub_category_id) {
                                    echo '<option selected="selected" value="'.$subcategory->sub_category_id.'">'.$subcategory->name.'</option>';
                            } else {
                                    echo '<option value="'.$subcategory->sub_category_id.'">'.$subcategory->name.'</option>';
                            }
                    }
                    if(!$page) {
                        echo '<option value="new">Create New</option>';
                    }
                } else {
                    echo '<option value="">None Created Yet</option>';
                    if(!$page) {
                        echo '<option value="0">None</option>';
                    
                        echo '<option value="new">Create New</option>';
                    }
                    
                }

        } else {
            echo '<option value="">None Created Yet</option>';
            if(!$page) {
                echo '<option value="0">None</option>';
                echo '<option value="new">Create New</option>';
            }
        }

        die();
    }
}