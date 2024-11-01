<?php

class SMW_Views_Render {
    private static $instance;
    
    private function __construct() {
        
    }
    
    public static function getInstance() {
        if( !isset( self::$instance ) ) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    
    function class_to_template($class) {
        $classes = explode('_',$class);

        foreach($classes as $class) {
            preg_match_all('/[A-Z][^A-Z]*/',$class,$new_classes[]);
        }
        foreach($new_classes as $key => $new_class) {
            $final_classes[] = strtolower(implode('-',$new_class[0]));
        }
        $template_path = implode('/',$final_classes);
        return $template_path;
    }
    function render_template_path($class,$type,$layout) {
        if($type) {
            $file = SMW_TEMPLATE_DIR . $type . '/' . $layout . '.php';
            if(file_exists($file)) {
                $layoutFolder = SMW_TEMPLATE_DIR . $type . '/';
            } else {
                $layoutFolder = SMW_PLUGIN_DIR . 'templates/' . $type . '/';
            }
        } else {
            $template_path = $this->class_to_template($class);
            $layoutFolder = SMW_PLUGIN_DIR . 'view/'.$template_path.'/';
        }
        return $layoutFolder;
    }
    function layout_file($layout,$type = null) {
        if($layout == 'default') {
            switch($type) {
                case 'page':
                    $layout = 'page';
                    break;
                case 'widgets':
                    $layout = 'widget';
                    break;
                case 'albums':
                    $layout = 'album';
                default:
                    $layout = $layout;
                    break;
            }
        } else {
            $layout = $layout;
        }
        return $layout;
    }

}