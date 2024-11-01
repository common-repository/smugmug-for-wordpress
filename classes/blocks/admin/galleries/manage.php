<?php
/**
 * The manage galleries section of the admin
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<div class="wrap">
    <h2><?php echo SMW_SERVICE; ?> Galleries <a href="admin.php?page=smugmug-create-gallery" class="button-secondary">Add New Gallery</a></h2>
    <p>Click on the name of a gallery to manage that gallery.  <button style="float:right" class="button-secondary exclude-gallery" type_id="all" id="exclude-all-wordpress">Exclude All Galleries</button> <button style="float:right" class="button-secondary" id="sync-wordpress">Sync Galleries with <?php echo SMW_SERVICE; ?></button></p>
    
    <div id="wordpress-galleries" count="<?php echo $this->control->db->getCount(); ?>">
        
    </div>
    <br />
    <hr />
    
    <h2><?php echo SMW_SERVICE; ?> Galleries Not in Wordpress Database <button class="button-primary" id="add-all-service">Add All Galleries</button></h2>
    <div id="service-galleries">
        <div id="smw-message">
            <p><strong>Click the button to see the SmugMug Galleries not in Wordpress</strong></p>
            <p>Due to long load times from loading a lot of <?php echo SMW_SERVICE; ?> Galleries through the <?php echo SMW_SERVICE; ?> API, the list of <?php echo SMW_SERVICE; ?> galleries not in the Wordpress Database isn't loaded by default.</p>
            <button style="display: block; margin: 0 auto 10px;" class="button-secondary" id="get-service">Get <?php echo SMW_SERVICE; ?> Galleries</button>
        </div>
        
    </div>
    <div style="display:none;" id="exclude-galleries-dialog" title="Exlude All Galleries?">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>You are about to exclude all galleries from Wordpress. You will not be able to edit or show any images or galleries from <?php echo SMW_SERVICE; ?>.</p><h4>Are you sure?</h4>
    </div>

    <div style="display:none;" id="exclude-galleries-dialog-final" title="Are You Sure?">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This is the last chance to not exclude all galleries from Wordpress. You will not be able to edit or show any images or galleries from <?php echo SMW_SERVICE; ?></p><h4>Are you sure?</h4>
    </div>
    <div style="display:none;" id="exclude-galleries-bulk-dialog" title="Exlude Galleries?">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>You are about to exclude the chosen galleries from Wordpress. You will not be able to edit or show any images or galleries from <?php echo SMW_SERVICE; ?>.</p><h4>Are you sure?</h4>
    </div>

    <div style="display:none;" id="exclude-galleries-bulk-dialog-final" title="Are You Sure?">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This is the last chance to not exclude these galleries from Wordpress. You will not be able to edit or show any images or galleries from <?php echo SMW_SERVICE; ?></p><h4>Are you sure?</h4>
    </div>
</div>

