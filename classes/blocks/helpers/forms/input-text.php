<?php
/**
 * Creates a text input field
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
        <input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo wp_specialchars( $this->value, 1 ); ?>" size="<?php echo ($size ? $size : '30'); ?>" tabindex="30" />
    <?php if($description): ?>
        &nbsp;&nbsp;<span class="description"><?php echo $description; ?></span>
    <?php endif; ?>
    <?php if($this->is_post): ?>
        <input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( $name ); ?>" />
    <?php endif; ?>
    </td>
</tr>