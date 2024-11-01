<?php
/**
 * The service galleries
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
                <option class="hide-if-no-js" value="addGalleries">Add Galleries</option>
            </select>
            <input type="submit" value="Apply" class="button-secondary action smw-doaction" id="bulk-service" name=""/>
        </div>
    </div>
    <button style="display: block; float:right; margin-top:4px;" class="button-secondary" id="get-service">Refresh <?php echo SMW_SERVICE; ?> Galleries</button>
</div>
<table class="widefat">
    <thead>
        <tr>
            <th class="check-column smw-column-header"><input name="bulk-service[]" type="checkbox" value="1" /></th>
            <th><?php echo SMW_SERVICE; ?> ID</th>
            <th>Album Title</th>
            <th>Category</th>
            <th>SubCategory</th>
            <th># of Images</th>
            <th>Passworded</th>
            <th><?php echo SMW_SERVICE; ?> Link</th>
            <?php /*<th>Delete Album</th>*/ ?>
            <th>Add Album to Wordpress</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th class="check-column smw-column-header"><input name="bulk-service[]" type="checkbox" value="1" /></th>
            <th><?php echo SMW_SERVICE; ?> ID</th>
            <th>Album Title</th>
            <th>Category</th>
            <th>SubCategory</th>
            <th># of Images</th>
            <th>Passworded</th>
            <th><?php echo SMW_SERVICE; ?> Link</th>
            <?php /*<th>Delete Album</th>*/ ?>
            <th>Add Gallery to Wordpress</th>
        </tr>
    </tfoot>
    <tbody>
        <?php foreach($this->service_galleries as $gallery): ?>
            <?php //print_r($gallery); ?>
            <tr id="<?php echo $gallery['id']; ?>-service">
                <td class="bulk-value"><input name="bulk-service[]" type="checkbox" value="<?php echo $gallery['id']; ?>|<?php echo $gallery['Key']; ?>" /></td>
                <td><?php echo $gallery['id']; ?></td>
                <td><?php echo $gallery['Title']; ?></td>
                <td><?php echo $gallery['Category']['Name']; ?></td>
                <td><?php echo ($gallery['SubCategory']['Name'] ? $gallery['SubCategory']['Name'] : '<em>None</em>'); ?></td>
                <td><?php echo $gallery['ImageCount']; ?></td>
                <td><?php echo ($gallery['Passworded'] == 1 ? 'Yes' : 'No'); ?></td>
                <td><a target="_blank" href="<?php echo $gallery['URL']; ?>?iframe=true&width=850&height=700" rel="prettyPhoto[Albums]">Go to <?php echo SMW_SERVICE; ?></a></td>
                <td><button type="submit" class="button-primary add-gallery">Add Gallery</button></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>