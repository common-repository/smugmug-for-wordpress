<?php

/**
 * SMW_Controllers_Abstract - An Abstract class that provides several functions for all controllers to use
 * 
 * @package SMW
 * @subpackage Controllers
 **/
class SMW_Controllers_Abstract {
    /**
     * Calls a class which is a model for Item Type to connect to Wordpress
     * 
     * @example A $type of 'galleries' returns SMW_Models_Wordpress_Items_Galleries
     * @access  public
     * @param   string $type Name of the Item
     * @return  Object|SMW_Models_Wordpress_Items_Albums|SMW_Models_Wordpress_Items_Categories|SMW_Models_Wordpress_Items_Galleries|SMW_Models_Wordpress_Items_Images|SMW_Models_Wordpress_Items_SubCategories
     */
    public function getItemDb( $type ) {
        return SMW::getModel('wordpress/items/' . $type );
    }
    /**
     * Calls a class which is a model for Item Type to connect to the Service
     * 
     * @example A $type of 'gallery' returns SMW_Models_Service_GalleryComms
     * @access  public
     * @param   string $type Name of the Item
     * @return  Object|SMW_Models_Service_AlbumComms|SMW_Models_Service_AuthComms|SMW_Models_Service_CategoryComms|SMW_Models_Service_GalleryComms|SMW_Models_Service_ImageComms|SMW_Models_Service_SubCategoryComms
     */
    public function getItemService( $type ) {
        return SMW::getModel('service/' . $type . '-comms');
    }
    /**
     * Calls a class which is a Controller for an Item Type
     * 
     * @example A $type of 'galleries' returns SMW_Controllers_Items_Type_Galleries
     * @access  public
     * @static
     * @param   string $type Name of the Item
     * @return  Object|SMW_Controllers_Items_Type_Categories|SMW_Controllers_Items_Type_Galleries|SMW_Controllers_Items_Type_SubCategories|SMW_Controllers_Items_Type_Images
     */
    public function getItemControl( $type ) {
        return SMW::getItem($type);
    }
    /**
     * A looping function that iterates through a multi-dimensional array and returns $key values for sections that have the $needle
     * 
     * @access  public
     * @param   mixed $needle What to look for
     * @param   array $haystack Where to look for the $needle
     * @param   bool $strict
     * @return  array $path The path is the list of $key values
     */
    public function arraySearch( $needle, $haystack, $strict=false, $path=array() )
    {
        if( !is_array($haystack) ) {
            return false;
        }
        $i = 0;
        foreach( $haystack as $key => $val ) {
            if( is_array($val) && $subPath = $this->arraySearch($needle, $val, $strict, $path) ) {
                $path = array_merge($path, array($key), $subPath);
                return $path;
            } elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) {
                $path[] = $key;
                return $path;
            }
        }
        return false;

    }
}
