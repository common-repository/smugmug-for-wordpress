<?php
/**
 * The exif data for the edit gallery pages
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<h2>EXIF Data for <?php echo $this->fileName; ?></h2>
			
<table class="exif-data">
	<thead>
		<tr>
			<th>Exif Data Type</th>
			<th>Information</th>
		</tr>
	</thead>
	<?php foreach($this->image_exif as $key => $value): ?>
		<?php if($key != 'id' && $key != 'Key') : ?>
		<tr>
			<td><?php preg_match_all('/[A-Z][^A-Z]*/',$key,$results); echo implode(' ',$results[0]);  ?></td>
			<td><?php echo $value; ?></td>
		</tr>
		<?php endif; ?>
	<?php endforeach; ?>
</table>