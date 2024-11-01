<?php
/**
 * SmugMug Widget Template: Widget One Column
 *
 */

extract( $this->args['args'] );

?>
<?php echo $before_widget; ?>
  <?php if ( $this->args['title'] )
        echo $before_title . $this->args['title'] . $after_title; ?>
<ul class="smp-widget-images-list">  
    <?php foreach($this->images as $key => $image): ?>
        <li class="item photo">
            <a <?php echo $image['Lightbox']; ?> href="<?php echo $image['UsableURL']; ?>" class="image-link">
                <img src="<?php echo $image['ThumbURL']; ?>" alt="" class="image" />
            </a>
        </li>
    <?php endforeach; ?>
</ul>
<?php echo $after_widget; ?>