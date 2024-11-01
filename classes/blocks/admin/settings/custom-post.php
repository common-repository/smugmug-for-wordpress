<?php
/**
 * The custom post setting section
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<hr />
    
<div id="ajax-front-custom-post" style="display: none">

</div>

<h3 id="custom-post-settings-title">Custom Post Type Settings</h3>

<div id="custom-post-settings-form-wrapper">

<form action="" id="custom-post-settings-form">
    <?php $this->forms->createFormTable( $this->form_items, 2, false ); ?>
</form>

<p class="submit">
    <button id="custom-post-settings" class="button-primary">Save Changes</button>
</p>

</div>