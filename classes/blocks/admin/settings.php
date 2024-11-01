<?php
/**
 * The admin settings page block
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<div class="wrap">

    <h1><?php echo SMW_SERVICE; ?> Settings</h1>
<?php

    if(!get_option('smw_access_token')) {
        $this->auth->addAuth();
    } else {
        $this->auth->removeAuth();
    }
    
    if(get_option('smw_access_token')):
?>
    
    <div id="general" class="settings-wrapper">
    <?php $this->general->settingsTable( 'general' ); ?>
    </div>
    <div id="lightbox" class="settings-wrapper">
    <?php $this->general->settingsTable( 'lightbox' ); ?>
    </div>
    <div id="custom-post" class="settings-wrapper">
    <?php $this->general->settingsTable( 'custom-post' ); ?>
    </div>
    <?php endif; ?>
    
</div>