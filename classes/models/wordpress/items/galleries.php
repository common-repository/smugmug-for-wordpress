<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gallerys
 *
 * @author anthony.humes
 */
class SMW_Models_Wordpress_Items_Galleries extends SMW_Models_Wordpress_Items {
    public $db;
    public $tableName;
    private static $instance;
    private function __construct() {
        //parent::__construct();
        global $wpdb;
        $this->db = $wpdb;
        $this->tableName = $this->getTableName();
        $this->service = SMW::getModel('service/gallery-comms');
        $this->images = $this->getDb('images');
        $this->category = $this->getDb('categories');
        $this->subCategory = $this->getDb('subcategories');
    }
    public static function getInstance() {
        if(!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    public function getTableName() {
        return $this->db->prefix . "smw_galleries";
    }
    public function itemTable( array $galleries ) { 
        $sql_array = array(
            array('name' => 'id','code' => 'int(9) PRIMARY KEY AUTO_INCREMENT'),
            array('name' => 'gallery_id','code' => 'INT(15) UNIQUE NOT NULL'),
            array('name' => 'gallery_key','code' => 'VARCHAR(10) NOT NULL'),
            array('name' => 'title','code' => 'VARCHAR(255) NOT NULL'),
            array('name' => 'keywords','code' => 'TEXT NOT NULL'),
            array('name' => 'url','code' => 'VARCHAR(255) DEFAULT \'\' NOT NULL'),
            array('name' => 'description','code' => 'MEDIUMTEXT NOT NULL'),
            array('name' => 'exif','code' => 'INT(1)'),
            array('name' => 'external','code' => 'INT(1)'),
            array('name' => 'position','code' => 'INT(5) NOT NULL'),
            array('name' => 'password','code' => 'VARCHAR(255) DEFAULT \'\' NOT NULL'),
            array('name' => 'password_hint','code' => 'VARCHAR(255) DEFAULT \'\' NOT NULL'),
            array('name' => 'passworded','code' => 'INT(1)'),
            array('name' => 'sizes','code' => 'TEXT NOT NULL'),
            array('name' => 'image_count','code' => 'INT(10) NOT NULL'),
            array('name' => 'highlight_id','code' => 'INT(15)'),
            array('name' => 'highlight_key','code' => 'VARCHAR(20)'),
            array('name' => 'highlight_type','code' => 'VARCHAR(30)'),
            array('name' => 'category_id','code' => 'INT(10) NOT NULL'),
            array('name' => 'category_name','code' => 'TEXT NOT NULL'),
            array('name' => 'sub_category_id','code' => 'INT(10)'),
            array('name' => 'sub_category_name','code' => 'TEXT'),
            array('name' => 'sort_direction','code' => 'INT(1)'),
            array('name' => 'sort_method','code' => 'VARCHAR(255) DEFAULT \'\' NOT NULL'),
            array('name' => 'last_updated','code' => 'DATETIME'),
            array('name' => 'extras','code' => 'MEDIUMTEXT')
        );
        if( !$this->tableExists( $this->tableName ) ) {
            $this->createTable( $this->tableName, $sql_array );
            //foreach($galleries as $gallery ) {
                //$this->addItem( $gallery );
            //}
        }
    }
    public function addItem( array $gallery ) {
        $extras = $gallery;
        unset($extras['id']);
        unset($extras['Key']);
        unset($extras['Title']);
        unset($extras['Keywords']);
        unset($extras['Description']);
        unset($extras['EXIF']);
        unset($extras['External']);
        unset($extras['Highlight']);
        unset($extras['Position']);
        unset($extras['Password']);
        unset($extras['PasswordHint']);
        unset($extras['Originals']);
        unset($extras['X3Larges']);
        unset($extras['X2Larges']);
        unset($extras['XLarges']);
        unset($extras['Larges']);
        unset($extras['Category']);
        unset($extras['SubCategory']);
        unset($extras['SortDirection']);
        unset($extras['SortMethod']);
        unset($extras['URL']);
        unset($extras['LastUpdated']);
        $extras = serialize($extras);
        $response = $this->db->insert($this->tableName,array(
            'gallery_id' => $gallery['id'],
            'gallery_key' => $gallery['Key'],
            'title' => $gallery['Title'],
            'keywords' => $gallery['Keywords'],
            'description' => $gallery['Description'],
            'exif' => $gallery['EXIF'],
            'external' => $gallery['External'],
            'highlight_id' => $gallery['Highlight']['id'],
            'highlight_key' => $gallery['Highlight']['Key'],
            'highlight_type' => $gallery['Highlight']['Type'],
            'position' => $gallery['Position'],
            'password' => $gallery['Password'],
            'password_hint' => $gallery['PasswordHint'],
            'passworded' => $gallery['Passworded'],
            'image_count' => $gallery['ImageCount'],
            'sizes' => serialize( array( 
                'originals' => $gallery['Originals'],
                'x3Larges' => $gallery['X3Larges'],
                'x2larges' => $gallery['X2Larges'],
                'xlarges' => $gallery['XLarges'],
                'larges' => $gallery['Larges']
                )),
            'category_name' => $gallery['Category']['Name'],
            'category_id' =>  $gallery['Category']['id'],
            'sub_category_name' => $gallery['SubCategory']['Name'],
            'sub_category_id' => $gallery['SubCategory']['id'],
            'sort_direction' => $gallery['SortDirection'],
            'sort_method' => $gallery['SortMethod'],
            'url' => $gallery['URL'],
            'last_updated' => $gallery['LastUpdated'],
            'extras' => $extras,
        ));
        
        return $response;
    }
    public function updateItem( $item, $id ) {
        if($item['Category']) {
            $category_name = $item['Category']['Name'];
            $category_id = $item['Category']['id'];
        } else {
            $category_name = $this->category->getName($item['CategoryID']);
            $category_id = $item['CategoryID'];
        }
        if($item['SubCategory']) {
            $sub_category_name = $item['SubCategory']['Name'];
            $sub_category_id = $item['SubCategory']['id'];
        } else {
            $sub_category_name = $this->subCategory->getName($item['SubCategoryID']);
            $sub_category_id = $item['SubCategoryID'];
        }
        if($item['ImageCount']) {
            $image_count = $item['ImageCount'];
        } else {
            $image_count = $this->images->getCount( $id );
        }
        if($item['Passworded']) {
            $passworded = $item['Passworded'];
        } elseif($item['Passworded'] == 0) {
            $passworded = 0;
        } else {
            $columns = "passworded";
            $passworded = $this->selectVar( $columns, $id );
        }
        if($item['Highlight']) {
            $highlight_id = $item['Highlight']['id'];
            $highlight_key = $item['Highlight']['Key'];
            $highlight_type = $item['Highlight']['Type'];
        } else {
            $highlight_id = $this->selectVar( 'highlight_id', $id );
            $highlight_key = $this->selectVar( 'highlight_key', $id );
            $highlight_type = $this->selectVar( 'highlight_type', $id );
        }
        if($item['URL']) {
            $url = $item['URL'];
        } else {
            $url = $this->selectVar( 'url', $id );
        }
        
        $info = array(
            'title' => $item['Title'],
            'description' => $item['Description'],
            'keywords' => $item['Keywords'],
            'external' => $item['External'],
            'position' => $item['Position'],
            'password' => $item['Password'],
            'password_hint' => $item['PasswordHint'],
            'passworded' => $passworded,
            'image_count' => $image_count,
            'highlight_id' => $highlight_id,
            'highlight_key' => $highlight_key,
            'highlight_type' => $highlight_type,
            'sizes' => serialize( array( 
                'originals' => $item['Originals'],
                'x3Larges' => $item['X3Larges'],
                'x2larges' => $item['X2Larges'],
                'xlarges' => $item['XLarges'],
                'larges' => $item['Larges']
                )),
            'category_name' => $category_name,
            'category_id' =>  $category_id,
            'last_updated' =>  $item['LastUpdated'],
            'sub_category_name' => $sub_category_name,
            'sub_category_id' => $sub_category_id,
            //'highlight' => serialize( $item['Hightlight'] ),
            'sort_direction' => $item['SortDirection'],
            'sort_method' => $item['SortMethod'],
            'url' => $url
        );
        foreach( $info as $key => $data ) {
            $value = $this->selectVar( $key, $id );
            if($value != $data) {
                $update_data[$key] = $data;
            }
        }
        $response = $this->db->update( $this->tableName, $update_data, array('gallery_id' => $id) );
        return $response;
    }
    public function getCount( $passworded = false ) {
        if($passworded) {
            return $this->db->get_var( "SELECT COUNT(*) FROM {$this->tableName} WHERE passworded = 1" );
        } else {
            return $this->db->get_var( "SELECT COUNT(*) FROM {$this->tableName}" );
        }
    }
    public function removeItem( $id ) {
        $this->images->removeGallery( $id );
        return $this->db->query("DELETE FROM {$this->tableName} WHERE gallery_id = {$id}");
    }
    public function selectVar( $columns, $id ) {
        return $this->db->get_var("SELECT {$columns} FROM {$this->tableName} WHERE gallery_id = {$id}");
    }
    public function selectVars( $columns, $array = NULL ) {
        if($array ? $array_type = ARRAY_A : $array_type = OBJECT);
        return $this->db->get_results("SELECT {$columns} FROM {$this->tableName}", $array_type);
    }
    public function selectItem( $id, $array = NULL ) {
        if($array ? $array_type = ARRAY_A : $array_type = OBJECT);
        return $this->db->get_row("SELECT * FROM {$this->tableName} WHERE gallery_id = {$id}", $array_type);
    }
    public function select($offset, $number) {
        return $this->db->get_results("SELECT * FROM {$this->tableName} LIMIT $number OFFSET $offset");
    }
    public function selectAll( $type = NULL, $array = false ) {
        if($array ? $array_type = ARRAY_A : $array_type = OBJECT);
        switch( $type ) {
            case 'id':
                return $this->db->get_results("SELECT gallery_id FROM {$this->tableName}", $array_type);
                break;
            default:
                return $this->db->get_results("SELECT * FROM {$this->tableName}", $array_type);
                break;
        }
    }
}
