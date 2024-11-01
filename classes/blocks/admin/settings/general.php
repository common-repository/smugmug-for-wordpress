<?php
/**
 * The general settings block on the admin settings page
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<hr />
    
<div id="ajax-front-general" style="display: none">

</div>

<h3 id="general-settings-title">General Settings</h3>

<div id="general-settings-form-wrapper">

<form action="" id="general-settings-form">
    <?php $this->forms->createFormTable( $this->form_items, 2, false ); ?>
</form>

<p class="submit">
    <button id="general-settings" class="button-primary">Save Changes</button>
</p>

</div>