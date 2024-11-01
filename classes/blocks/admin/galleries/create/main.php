<?php
/**
 * The main page for the create gallery admin page
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<div class="wrap">
    <h1>Create <?php echo SMW_SERVICE; ?> Gallery</h1>
    
    <div id="ajax-response" style="display:none;">
        
    </div>
    
    <p><em><span class="required">*</span> Specifies a Required Field</em></p>
    
    <form id="create-gallery-form" action="" method="post" accept-charset="utf-8">
        <?php $this->obj->getForm(); ?>
    </form>

    <p class="submit">
        <button id="create-gallery" class="button-primary">Create Gallery</button>
    </p>
</div>