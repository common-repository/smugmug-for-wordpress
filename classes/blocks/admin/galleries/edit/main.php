<?php
/**
 * The main edit galleries page
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<div class="wrap">
    <?php /*<h2>Edit Gallery</h2>

    <form action="" id="edit-gallery">
        <?php $this->control->getForm() ?>
    </form>*/ ?>

    <h2>Edit Gallery '<?php echo urldecode($this->gallery['title']); ?>'&nbsp;&nbsp;&nbsp;<a href="<?php echo $this->gallery['url']; ?>?iframe=true&width=815&height=700" title="<?php echo $this->gallery['title']; ?>" rel="prettyPhoto" class="button-secondary">Visit <?php echo SMW_SERVICE; ?></a> <a href="admin.php?page=smugmug-manage-galleries" class="button-secondary">Go Back</a></h2>
    <p class="submit">
            <button id="edit-gallery-toggle" class="button-primary">Edit Gallery</button>

            <a class="button-primary" rel="prettyPhoto" href="http://www.smugmug.com/photos/new_add.mg?AlbumID=<?php echo $this->gallery['gallery_id']; ?>&uploader=html5&iframe=true&width=850&height=600">Upload Images</a>
    </p>
    <div id="edit">
        <div id="edit-gallery">
            <button id="edit-gallery-submit" class="button-primary"><?php esc_attr_e('Save Changes'); ?></button>
            <form action="" id="edit-gallery-form">
                <?php echo $this->control->getForm(); ?>
            </form>
            <button id="edit-gallery-submit" class="button-primary" style="margin-top:15px;"><?php esc_attr_e('Save Changes'); ?></button>
        </div>
    </div>


    <div id="images">
        <div id="admin-image-list" gal_id="<?php echo $this->gallery['gallery_id']; ?>" count="<?php echo $this->gallery['image_count']; ?>">
                &nbsp;
        </div>
    </div>
</div>
