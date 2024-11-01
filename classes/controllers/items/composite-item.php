<?php

abstract class SMW_Controllers_Items_CompositeItem extends SMW_Controllers_Items_Item {
    abstract function addItem( $id );
    abstract function removeItem( $id );
    abstract function getTemplate();
    
}
