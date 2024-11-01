<?php
/**
 * The lightbox settings
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<hr />
    
<div id="ajax-front-lightbox" style="display: none">

</div>

<h3 id="lightbox-settings-title">Lightbox Settings</h3>

<div id="lightbox-settings-form-wrapper">

<form action="" id="lightbox-settings-form">
    <?php $this->forms->createFormTable( $this->form_items, 2, false ); ?>
</form>

<p class="submit">
    <button id="lightbox-settings" class="button-primary">Save Changes</button>
</p>

</div>