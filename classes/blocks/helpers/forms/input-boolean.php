<?php
/**
 * Creates a boolean input field
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<?php
    extract( $this->args );
?>

<tr valign="top" <?php echo ($section_class ? 'class="'.$section_class.'"' : ''); echo ($section_id ? "id='{$section_id}'" : ''); ?>>
    <?php if($this->columns == 1): ?>
    <td>
        <label for="<?php echo $name; ?>"><strong><?php echo $title; ?></strong> <?php echo $required ? '<span class="required">*</span>' : ''; ?></label>
    <?php elseif($this->columns == 2): ?>
    <th scope="row"><label for="<?php echo $name; ?>"><strong><?php echo $title; ?></strong> <?php echo $required ? '<span class="required">*</span>' : ''; ?></label></th>
    <td>
    <?php endif; ?>
        Yes <input type="radio" name="<?php echo $name; ?>" value="1" <?php if ( htmlentities( $this->value, ENT_QUOTES ) == 1 ) echo ' checked="checked"'; ?> />
        No <input type="radio" name="<?php echo $name; ?>" value="0" <?php if ( htmlentities( $this->value, ENT_QUOTES ) == 0 ) echo ' checked="checked"'; ?> />
    <?php if($description): ?>
        &nbsp;&nbsp;<span class="description"><?php echo $description; ?></span>
    <?php endif; ?>
    <?php if($this->is_post): ?>
        <input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( $name ); ?>" />
    <?php endif; ?>
    </td>
</tr>