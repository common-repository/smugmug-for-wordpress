<?php
/**
 * The block which creates the form table
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<table class="form-table">
    <?php 
        foreach ( $this->meta_boxes as $meta ) {
            $this->generateTable($meta,$this->columns,$this->is_post);
        }    
    ?>
</table>