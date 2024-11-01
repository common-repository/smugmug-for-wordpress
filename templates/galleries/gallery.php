<?php 
/**
 * SmugMug Default Gallery Template
 * 
 */

$imgDimensions = array( 'width' => 144, 'height' => 108 );

?>
<?php if (!empty ($this->images)) : ?>
    
<div id="smp-images" class="four-columns">
	<div class="smp-pagination top">
		<?php echo $this->pagination->create_pagination('static',2) ?><br />
                <a href="<?php echo $this->gallery_buy_url; ?>" target="_blank">Buy Photos from This Gallery</a>
	</div>
        
	<ul id="smp-imagelist" style="clear:both;">
            <?php foreach ($this->images as $image) : ?>
                <li class="item photo" style="top: 0px;">
                    <a href="<?php echo $image['UsableURL']; ?>" <?php echo $image['Lightbox']; //echo $this->lightbox_code('images',$image); ?>>
                            <img width="<?php echo $imgDimensions['width'] ?>" class="fade-image" src="<?php echo $image['ThumbURL'] ?>" style="opacity: 1;">
                    </a>
                    <a style="line-height:25px;text-align: center;" href="<?php echo $image['BuyLink']; ?>" target="_blank">Buy Photo</a>
                </li>
            <?php endforeach; ?>
	</ul>

 	<div class="smp-pagination bottom">
		<?php echo $this->pagination->create_pagination('static',2) ?>
	</div>
    </div>
	

<?php endif; ?>