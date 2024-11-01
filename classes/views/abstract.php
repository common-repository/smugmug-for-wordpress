<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of views
 *
 * @author anthony
 */
abstract class SMW_Views_Abstract {
    private function __construct() {
        
    }
    public function getViewSection( $type,$class ) {
        $sections = explode( '_', $class );
        array_push( $sections, ucfirst($type) );
        $returnedClass = implode( '_',$sections );
        //print_r($returnedClass);
        $instance = call_user_func($returnedClass . '::getInstance');
        return $instance;
    }
    abstract public function showPage();
    public function getObj( $class ) {
        $classes = explode( '_', $class );
        array_shift( $classes );
        array_shift( $classes );
        foreach($classes as $class) {
            $new_classes[] = strtolower($class);
        }
        $class = implode('/',$new_classes);
        return SMW::getControl( $class );
    }
    public function getItem( $type ) {
        switch( $type ) {
            case 'albums':
                return $this->getAlbum();
                break;
            case 'categories':
                return $this->getCategory();
                break;
            case 'subcategories':
                return $this->getSubCategory();
                break;
            case 'images':
                return $this->getImages();
                break;
            case 'galleries':
                return $this->getGalleries();
                break;
        }
    }
    private function getAlbum() {
        return SMW::getItem('albums');
    }
    private function getCategory() {
        return SMW::getItem('categories');
    }
    private function getSubCategory() {
        return SMW::getItem('sub-categories');
    }
    private function getImages() {
        return SMW::getItem('images');
    }
    private function getGalleries() {
        return SMW::getItem('galleries');
    }
}