<?php
/**
 * The images block for the edit galleries page
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<?php if($this->totalPages != 0): ?>
	<div id="paging_button">
		<?php
			$this->pagination->create_pagination_ajax($this->currentPage,2,$this->totalPages)
		?>
	</div>
<?php endif; ?>
<?php if($this->paged_images) : ?>
<div class="num-per-page">
	<?php
		$images_per_page_array = array(10,20,30,40,50,'All');
		$this->pagination->items_per_page($images_per_page_array);
	?>
</div>

<div id="exif-data">
	<div id="exif-interior">
	</div>
</div>

<table id="smw-imagelist" class="widefat fixed" cellspacing="0" >

	<thead>
		<tr>
			<th class="image">Image</th>
                        <th class="info">Image Info</th>
			<th class="caption">Caption</th>
			<th class="exclude">Exclude</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th class="image">Image</th>
                        <th class="info">Image Info</th>
			<th class="caption">Caption</th>
			<th class="exclude">Exclude</th>
		</tr>
	</tfoot>
	<tbody>
		<?php //print_r($paged_images[0]); ?>
		<?php foreach ( $this->paged_images as $image ) : ?>
			<tr id="<?php echo $image['image_id']; ?>">
				<?php /*<td class="id"></td>*/ ?>
				<td class="image"><a id="prettyPhoto" href="<?php echo $image['large_url']; ?>" rel="prettyPhoto[main]"><img src="<?php echo $image['thumb_url']; ?>" alt="" /></a></td>
                                <td class="info">
                                    <table style="width:100%; border-collapse: collapse;">
                                        <tbody>
                                            <tr>
                                                <td><strong><?php echo SMW_SERVICE; ?> ID</strong></td>
                                                <td><em><?php echo $image['image_id']; ?></em></td>
                                            </tr>
                                            <tr>
                                                <td><strong>View</strong></td>
                                                <td><a href="<?php echo $image['url']; ?>?iframe=true&width=815&height=700" rel="prettyPhoto">View in <?php echo SMW_SERVICE; ?></a></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Sizes</strong></td>
                                                <td>
                                                    <a href="<?php echo $image['original_url']; ?>" rel="prettyPhoto[original]" title="View Size: Orignal">Original</a><br />
                                                    <a href="<?php echo $image['x3large_url']; ?>" rel="prettyPhoto[xlarge3]" title="View Size: Extra Large 3">Extra Large 3</a><br />
                                                    <a href="<?php echo $image['x2large_url']; ?>" rel="prettyPhoto[xlarge2]" title="View Size: Extra Large 2">Extra Large 2</a><br />
                                                    <a href="<?php echo $image['xlarge_url']; ?>" rel="prettyPhoto[xlarge]" title="View Size: Extra Large">Extra Large</a><br />
                                                    <a href="<?php echo $image['large_url']; ?>" rel="prettyPhoto[large]" title="View Size: Large">Large</a><br />
                                                    <a href="<?php echo $image['medium_url']; ?>" rel="prettyPhoto[medium]" title="View Size: Medium">Medium</a><br />
                                                    <a href="<?php echo $image['small_url']; ?>" rel="prettyPhoto[small]" title="View Size: Small">Small</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Exif-Data</strong></td>
                                                <td><span class="image-exif" filename="<?php echo $image['filename']; ?>" key="<?php echo $image['image_key']; ?>" id="<?php echo $image['image_id']; ?>">View Exif-Data</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                </td>
				<td class="caption">
                                    <div id="caption-<?php echo $image['image_id']; ?>">
                                        <textarea id="<?php echo $image['image_id']; ?>" name="image-caption"><?php echo stripslashes($image['caption']); ?></textarea>
					<br />
					<button id="caption-save-<?php echo $image['image_id']; ?>" class="button-secondary caption-save">Save</button>
                                    </div>
				</td>
				<td class="exclude">
                                    <div id="image-hide-<?php echo $image['image_id']; ?>">
                                        <?php
                                            if($image['hidden'] == 1) {
                                                echo '<button class="button-secondary image-hide" imagehide="'.$image['hidden'].'">Show Image</button>';
                                                echo '<h3>Hidden</h3>';
                                            } else {
                                                echo '<button class="button-secondary image-hide" imagehide="'.$image['hidden'].'">Hide Image</button>';
                                                echo '<h3>Not Hidden</h3>';

                                            }
                                        ?>
                                    </div>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php if($this->totalPages != 0): ?>
	<div id="paging_button">
		<?php
			$this->pagination->create_pagination_ajax($this->currentPage,2,$this->totalPages)
		?>
	</div>
<?php endif; ?>
<div class="num-per-page">
	<?php
		$images_per_page_array = array(10,20,30,40,50,'All');
		$this->pagination->items_per_page($images_per_page_array);
	?>
</div>
<?php else: ?>
<div id="message" class="error">
    <p><strong>No Images</strong></p>
    <p>There are no images currently in this gallery.  If there are images in this gallery in <?php echo SMW_SERVICE; ?>, please go to <a href="admin.php?page=smugmug-manage-galleries">Manage Galleries</a>, and click "<strong>Sync Galleries with <?php echo SMW_SERVICE; ?></strong>."</p>
</div>
<?php endif; ?>
