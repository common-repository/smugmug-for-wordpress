<?php
/**
 * The manage galleries block that returns the wordpress galleries
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<div id="smw-manage-header">
    <div class="tablenav top">
        <div class="alignleft actions">
            <select name="action">
                <option selected="selected" value="-1">Bulk Actions</option>
                <option class="hide-if-no-js" value="excludeGalleries">Exclude Galleries</option>
            </select>
            <input type="submit" value="Apply" class="button-secondary action smw-doaction" id="bulk-wordpress" name=""/>
        </div>
    </div>
    <div class="num-per-page">
        <?php
            $images_per_page_array = array(10,20,30,40,50,'All');
            $this->pagination->items_per_page($images_per_page_array);
        ?>
    </div>
    <?php if($this->totalPages != 0): ?>
        <div id="paging_button">
            <?php echo $this->pagination->create_pagination('ajax',2); ?>
        </div>
    <?php endif; ?>
</div>

<table class="widefat">
    <thead>
        <tr>
            <th class="check-column smw-column-header"><input name="bulk-wordpress[]" type="checkbox" value="1" /></th>
            <th><?php echo SMW_SERVICE; ?> ID</th>
            <th>Gallery Title</th>
            <th>Category</th>
            <th>SubCategory</th>
            <th># of Images</th>
            <th>Passworded</th>
            <th><?php echo SMW_SERVICE; ?> Link</th>
            <th>Exclude from Wordpress</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th class="check-column smw-column-header"><input name="bulk-wordpress[]" type="checkbox" value="1" /></th>
            <th><?php echo SMW_SERVICE; ?> ID</th>
            <th>Gallery Title</th>
            <th>Category</th>
            <th>SubCategory</th>
            <th># of Images</th>
            <th>Passworded</th>
            <th><?php echo SMW_SERVICE; ?> Link</th>
            <th>Exclude from Wordpress</th>
        </tr>
    </tfoot>
    <tbody>
        <?php foreach($this->galleries as $gallery): ?>
        <tr id="<?php echo $gallery->gallery_id; ?>">
            <td class="bulk-value"><input name="bulk-wordpress[]" type="checkbox" value="<?php echo $gallery->gallery_id; ?>" /></td>
            <td><?php echo $gallery->gallery_id; ?></td>
            <td class="gallery-name"><a href="admin.php?page=smugmug-edit-gallery&key=<?php echo $gallery->gallery_key; ?>&id=<?php echo $gallery->gallery_id; ?>"><?php echo urldecode($gallery->title); ?></a></td>
            <td><?php echo $gallery->category_name; ?></td>
            <td><?php echo ($gallery->sub_category_name ? $gallery->sub_category_name : '<em>None</em>'); ?></td>
            <td><?php echo $gallery->image_count; ?></td>
            <td><?php echo ($gallery->passworded == 1 ? 'Yes' : 'No'); ?></td>
            <td><a target="_blank" href="<?php echo $gallery->url; ?>?iframe=true&width=850&height=700" rel="prettyPhoto[Albums]">Go to <?php echo SMW_SERVICE; ?></a></td>
            <td>
                <form action="" id="<?php echo $gallery->gallery_id; ?>" method="post" accept-charset="utf-8">
                    <input type="hidden" name="smw_exclude_gallery" value="1" />
                    <input type="hidden" name="smw_exclude_gallery_id" value="<?php echo $gallery->gallery_id; ?>" />
                </form>
                <button type_id="single" service="wordpress" class="button-secondary exclude-gallery">Exclude Gallery</button>
                <div style="display:none;" id="dialog-exclude-<?php echo $gallery->gallery_id; ?>" title="Exclude '<?php echo $gallery->title; ?>'?">
                <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>You are about to exclude the gallery '<?php echo $gallery->title ?>'. You will not be able to add it to any Wordpress pages or posts.</p><h4>Are you sure?</h4>
                </div>

                <div style="display:none;" id="dialog-exclude-<?php echo $gallery->gallery_id; ?>-final" title="Are You Sure?">
                    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>This is the last chance to not exclude the gallery '<?php echo $gallery->title ?>'. You will not be able to add it to any Wordpress pages or posts.</p><h4>Are you sure?</h4>
                </div>
                
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>