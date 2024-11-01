<?php
/**
 * SMW_Controllers_Admin_Widgets - Extends the WP_Widget class to provide widget support for SmugMug for Wordpress
 * 
 * @package SMW_Controllers
 * @subpackage Admin
 * @author Anthony Humes
 **/

class SMW_Controllers_Admin_Widgets extends WP_Widget {
    /**
     * This variable is set in __construct and provides access to the image item model
     * 
     * @access protected
     * @var Object
     */
    protected $imageDb;
    /**
     * This variable is set in __construct and provides access to the gallery item model
     * 
     * @access protected
     * @var Object
     */
    protected $gallerDb;
    /**
     * This variable is set in __construct and provides access to the templates helper
     * 
     * @access protected
     * @var Object
     */
    protected $templates;
    
    /**
     * Instaniates this class which gives the variables needs to allow the widget to function correctly
     * 
     * @access  public
     * @return  void
     */
    
    public function __construct() {
        parent::__construct('smugmug-widget-images', 'SmugMug Widget', array('description' => 'Display a set number of random images from a SmugMug gallery.'));
        $this->imageDb = SMW::getItem('images');
        $this->galleryDb = SMW::getItem('galleries');
        $this->templates = SMW::getHelper('templates');
    }
    
    /**
     * Returns the array used by the widget output
     * 
     * @access  public
     * @param   array $instance $instance variable passed by the Widget class to provide results.
     * @return  array $images
     */
    
    public function widgetImages( $instance ) {
        $gallery = $instance['gallery'];
        $image_number = $instance['image_number'];
        $images = $this->imageDb->getRand( $gallery, $image_number, $instance['thumb_size'], $instance['viewable_size'] );
        return $images;

    }
    
    /**
     * Prints the widget data to the frontend
     * 
     * @access  public
     * @param   array $instance $instance variable passed by the Widget class to provide results.
     * @param   array $args An array of arguments to be used by te frontend
     */
    
    function widget($args, $instance) {
        
        $args_instance['instance'] = $instance;
        
        $args_instance['args'] = $args;
        
        $args_instance['title'] = apply_filters('widget_title', $instance['title']);
        
        $this->args = $args_instance;
        
        $this->images = $this->widgetImages($this->args['instance']);
        
        if($instance['template'] == 'default') {
            SMW::getTemplate('widgets/widget');
        } else {
            SMW::getTemplate('widgets/' . $instance['template']);
        }
    }

    /**
     * Updates the widget data
     * 
     * @access  public
     * @param   array $new_instance The old instance
     * @param   array $old_instance The new instance
     * @see WP_Widget::update
     */
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
        $instance['gallery'] = strip_tags($new_instance['gallery']);
        $instance['template'] = strip_tags($new_instance['template']);
        $instance['image_number'] = strip_tags($new_instance['image_number']);
        $instance['thumb_size'] = strip_tags($new_instance['thumb_size']);
        $instance['viewable_size'] = strip_tags($new_instance['viewable_size']);
        return $instance;
    }

    /**
     * Creates the admin form
     * 
     * @access  public
     * @param   array $instance Values to use for the widget that have been stored in the database
     * @see WP_Widget::form
     */
    function form($instance) {
        $galleries = $this->galleryDb->getAllForms();
        $templates = $this->templates->getTemplates( SMW_TEMPLATE_DIR, 'widget', 'SmugMug Widget Template' );
        $thumb_sizes = array(
            array( 'name' => 'Thumb', 'value' => 'thumb_url' ),
            array( 'name' => 'Tiny', 'value' => 'tiny_url' ),
            array( 'name' => 'Small', 'value' => 'small_url' ),
            array( 'name' => 'Medium', 'value' => 'medium_url' ),
        );
        $viewable_sizes = array(
            array( 'name' => 'Large', 'value' => 'large_url' ),
            array( 'name' => 'Extra Large', 'value' => 'xlarge_url' ),
            array( 'name' => 'Extra Large 2', 'value' => 'x2large_url' ),
            array( 'name' => 'Extra Large 3', 'value' => 'x3large_url' ),
            array( 'name' => 'Original', 'value' => 'original_url' ),
        );
        $seleted_template = esc_attr($instance['template']);
        $selected_thumb_size = esc_attr($instance['thumb_size']);
        $selected_viewable_size = esc_attr($instance['viewable_size']);
        $title = esc_attr($instance['title']);
        $gallery = esc_attr($instance['gallery']);
        $image_number = esc_attr($instance['image_number']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <?php //print_r($instance); ?>
          <label for="<?php echo $this->get_field_id('gallery'); ?>"><?php _e('Gallery:'); ?></label> 
          <select class="widefat" id="<?php echo $this->get_field_id('gallery'); ?>" name="<?php echo $this->get_field_name('gallery'); ?>" type="text">
              <option value="">Select Gallery</option>
              <?php foreach($galleries as $gallery_new):
                  $gallery_id = $gallery_new['value'];
                  ?>
                <option value="<?php echo $gallery_new['value']; ?>" <?php echo ($gallery_id == $gallery) ? 'selected="selected"' : ''; ?>><?php echo $gallery_new['name']; ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        <p>
          <?php //print_r($instance); ?>
          <label for="<?php echo $this->get_field_id('template'); ?>"><?php _e('Template:'); ?></label> 
          <select class="widefat" id="<?php echo $this->get_field_id('template'); ?>" name="<?php echo $this->get_field_name('template'); ?>" type="text">
              <option value="default">Default</option>
              <?php foreach($templates as $template): ?>
                <option value="<?php echo $template['value']; ?>" <?php echo ($template['value'] == $seleted_template) ? 'selected="selected"' : ''; ?>><?php echo $template['name']; ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        
        <p>
          <label for="<?php echo $this->get_field_id('image_number'); ?>"><?php _e('Number of Images:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('image_number'); ?>" name="<?php echo $this->get_field_name('image_number'); ?>" type="text" value="<?php echo $image_number; ?>" />
          <span class="description">The Number of Images to Display</span>
        </p>
        <p>
          <?php //print_r($instance); ?>
          <label for="<?php echo $this->get_field_id('thumb_size'); ?>"><?php _e('Thumbnail Size:'); ?></label> 
          <select class="widefat" id="<?php echo $this->get_field_id('thumb_size'); ?>" name="<?php echo $this->get_field_name('thumb_size'); ?>" type="text">
              <?php foreach($thumb_sizes as $thumb_size): ?>
                <option value="<?php echo $thumb_size['value']; ?>" <?php echo ($thumb_size['value'] == $selected_thumb_size) ? 'selected="selected"' : ''; ?>><?php echo $thumb_size['name']; ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        <p>
          <?php //print_r($instance); ?>
          <label for="<?php echo $this->get_field_id('viewable_size'); ?>"><?php _e('Lightbox Image Size:'); ?></label> 
          <select class="widefat" id="<?php echo $this->get_field_id('viewable_size'); ?>" name="<?php echo $this->get_field_name('viewable_size'); ?>" type="text">
              <?php foreach($viewable_sizes as $viewable_size): ?>
                <option value="<?php echo $viewable_size['value']; ?>" <?php echo ($viewable_size['value'] == $selected_viewable_size) ? 'selected="selected"' : ''; ?>><?php echo $viewable_size['name']; ?></option>
              <?php endforeach; ?>
          </select>
        </p>
        <?php 
    }
}
