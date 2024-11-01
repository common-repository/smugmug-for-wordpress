<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of abstract
 *
 * @author anthony
 */
abstract class SMW_Models_Abstract {
    public function arraySearch( $needle, $haystack, $strict=false, $path=array() )
    {
        if( !is_array($haystack) ) {
            return false;
        }
        $i = 0;
        foreach( $haystack as $key => $val ) {
            if( is_array($val) && $subPath = $this->smw_array_search($needle, $val, $strict, $path) ) {
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
?>
