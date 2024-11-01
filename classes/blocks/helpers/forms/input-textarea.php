<?php
/**
 * Creates a textarea input field
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
    <td><label for="<?php echo $name; ?>"><strong><?php echo $title; ?></strong> <?php echo $required ? '<span class="required">*</span>' : ''; ?></label></td>
    <td>
    <?php endif; ?>
        <textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="4" tabindex="30" style="width: 97%;"><?php echo wp_specialchars( stripslashes($this->value), 1 ); ?></textarea>
    <?php if($this->is_post): ?>
        <input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( $name ); ?>" />
    <?php endif; ?>
    </td>
</tr>