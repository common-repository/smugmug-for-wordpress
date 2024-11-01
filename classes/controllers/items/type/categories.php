<?php

/**
 * SMW_Controllers_Items_Type_Categories - Controls the categories item type
 * 
 * @package SMW_Controllers_Items
 * @subpackage Type
 * @author Anthony Humes
 **/
class SMW_Controllers_Items_Type_Categories extends SMW_Controllers_Items_Item {
    /**
     * This variable is set in __construct and provides access to the SubCategory model
     * 
     * @access protected
     * @var Object|SMW_Models_Items_Type_Categories
     */
    protected $db;
    /**
    * This variable is set in getInstance and stores an instance of this class for the Singleton pattern
    * 
    * @access private
    * @var Object|SMW_Controllers_Items_Type_Categories
    */
    private static $instance;
    /**
     * Instaniates this class and gives the variables needs to allow the class to function correctly
     * 
     * @access  private
     * @return  void
     */
    private function __construct() {
        $this->db = $this->getItemDb( 'categories' );
    }
    /**
     * Used with the Singleton design pattern to return an instance of this class
     * 
     * @access  public
     * @static
     * @return  Object|SMW_Controllers_Items_Type_Categories
     */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    /**
     * Makes changes to Categories in the admin through an Ajax function and returns a message
     * 
     * @access public
     * @see SMW_Models_Items_Type_Categories::createItem()
     * @see SMW_Models_Items_Type_Categories::deleteItem()
     * @see SMW_Models_Items_Type_Categories::renameItem()
     * @return html
     */
    public function categoryAdminChange() {
        $unformatted_data = explode('&',$_POST['form_data']);
        foreach($unformatted_data as $key => $data) {
            $form_info = explode('=',$data);
            $form_info[1] = str_replace('+', ' ', $form_info[1]);
            $form_data[$form_info[0]] = $form_info[1];
        }
        switch($form_data['FormType']) {
            case 'category-create':
                $category['name'] = $form_data['Name'];
                $category['nice_name'] = str_replace(' ','-',$form_data['NiceName']);
                $response = $this->db->createItem( $category );
                $categories = $this->getForm();
                $user_categories = $this->getForm( true );
                $default_option = array('value' => '','name'=>'Select Category');
                array_unshift($categories, $default_option);
                array_unshift($user_categories, $default_option);
                foreach($categories as $category) {
                    $options .= '<option value="'.$category['value'].'">'.$category['name'].'</option>';
                }
                foreach($user_categories as $category) {
                    $user_options .= '<option value="'.$category['value'].'">'.$category['name'].'</option>';
                }
                if($response) {
                    $return['message'] = '<div id="message" class="updated"><p><strong>Category <em>'.$response['category_name'].'</em> Created</strong></p><p>The category '.$response['category_name'].' has been created successfully.</p></div>';
                    $return['categories'] = $options;
                    $return['user_categories'] = $user_options;
                    $return = json_encode($return);
                    echo $return;
                } else {
                    $return['message'] = '<div class="message error"><p><strong>Category Creatation Failed</strong></p><p>Wordpress failed to create the gallery '.$category['name'].'.</p></div>';
                    $return = json_encode($return);
                    echo $return;
                }
                break;
            case 'category-delete':
                $category = $form_data['CategoryID'];
                $response = $this->db->deleteItem( $category );
                $categories = $this->getForm();
                $user_categories = $this->getForm( true );
                $default_option = array('value' => '','name'=>'Select Category');
                array_unshift($categories, $default_option);
                array_unshift($user_categories, $default_option);
                foreach($categories as $category) {
                    $options .= '<option value="'.$category['value'].'">'.$category['name'].'</option>';
                }
                foreach($user_categories as $category) {
                    $user_options .= '<option value="'.$category['value'].'">'.$category['name'].'</option>';
                }
                
                if($response) {
                    $return['message'] = '<div id="message" class="updated"><p><strong>Category <em>'.$response['category_name'].'</em> Deleted</strong></p><p>The category '.$response['category_name'].' has been deleted successfully.</p></div>';
                    $return['user_categories'] = $user_options;
                    $return['categories'] = $options;
                    $return = json_encode($return);
                    echo $return;
                } else {
                    $return['message'] = '<div class="message error"><p><strong>Category Deletion Failed</strong></p><p>Wordpress failed to delete the gallery '.$category['name'].'.</p></div>';
                    $return = json_encode($return);
                    echo $return;
                }
                break;
            case 'category-rename':
                $args['CategoryID'] = $form_data['CategoryIDRename'];
                $args['Name'] = $form_data['CategoryRenameNew'];
                
                $response = $this->db->renameItem( $args );
                
                $categories = $this->getForm();
                $user_categories = $this->getForm( true );
                $default_option = array('value' => '','name'=>'Select Category');
                array_unshift( $categories, $default_option );
                array_unshift( $user_categories, $default_option );
                foreach($categories as $category) {
                    $options .= '<option value="'.$category['value'].'">'.$category['name'].'</option>';
                }
                foreach($user_categories as $category) {
                    $user_options .= '<option value="'.$category['value'].'">'.$category['name'].'</option>';
                }
                
                if( $response ) {
                    $return['message'] = '<div id="message" class="updated"><p><strong>Category <em>'.$response['category_name'].'</em> Deleted</strong></p><p>The category '.$response['category_name'].' has been deleted successfully.</p></div>';
                    $return['user_categories'] = $user_options;
                    $return['categories'] = $options;
                    $return = json_encode( $return );
                    echo $return;
                } else {
                    $return['message'] = '<div class="message error"><p><strong>Category Rename Failed</strong></p><p>Wordpress failed to rename the gallery '.$args['Name'].'.</p></div>';
                    $return = json_encode( $return );
                    echo $return;
                }
                
                break;
        }
        
        die();
    }
    /**
     * Gets the list of categories for a form
     * 
     * @access public
     * @see SMW_Models_Items_Type_Categories::selectAll()
     * @return html
     */
    public function formList() {
        $categories_db = $this->db->selectAll();
        $i = 0;
        foreach( $categories_db as $category ) {
            $categories[$i]['name'] = $category->category_name;
            $categories[$i]['value'] = $category->category_id;
            $i++;
        }
        return $categories;
    }
    /**
     * Gets the form for the categories
     * 
     * @access public
     * @see SMW_Models_Items_Type_Categories::selectAllForms()
     * @return html
     */
    public function getForm( $user = false ) {
        return $this->db->selectAllForms( $user );
    }
}