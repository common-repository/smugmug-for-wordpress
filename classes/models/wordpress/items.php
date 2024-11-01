<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of items
 *
 * @author anthony
 */
abstract class SMW_Models_Wordpress_Items extends SMW_Models_Wordpress {
    abstract function getTableName();
//    abstract function addItem();
    //abstract function updateItem( $item, $id );
    abstract function removeItem( $id );
    abstract function selectItem( $id );
}