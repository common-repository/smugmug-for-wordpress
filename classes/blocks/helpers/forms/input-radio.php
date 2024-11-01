<?php
/**
 * Creates a radio input field
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
        <label for="<?php echo $name; ?>"><strong><?php echo $title; ?></strong> <?php echo $required ? '<span class="required">*</span>' : ''; echo $this->value ?></label>
    <?php elseif($this->columns == 2): ?>
    <th scope="row"><label for="<?php echo $name; ?>"><strong><?php echo $title; ?></strong></label> <?php echo $required ? '<span class="required">*</span>' : ''; ?></th>
    <td>
    <?php endif; ?>
    <?php foreach( $options as $option ): ?>
        <?php if($option['checked']): ?>
            <input id="<?php echo $option['value']; ?>" type="radio" name="<?php echo $name; ?>" value="<?php echo $option['value']; ?>" checked="checked" /> <?php echo $option['name']; ?>
        <?php else: ?>
            <input id="<?php echo $option['value']; ?>" type="radio" name="<?php echo $name; ?>" value="<?php echo $option['value']; ?>" <?php if ( htmlentities( $this->value, ENT_QUOTES ) == $option['value'] ) echo ' checked="checked"'; ?> /> <?php echo $option['name']; ?>
        <?php endif; ?>
        <?php if($option['description']): ?>
            &nbsp;&nbsp;<span class="description"><?php echo stripslashes($option['description']); ?></span>
        <?php endif; ?>
        <br />
    <?php endforeach; ?>
    <?php if($description): ?>
        &nbsp;&nbsp;<span class="description"><?php echo $description; ?></span>
    <?php endif; ?>
    <?php if($this->is_post): ?>
        <input type="hidden" name="<?php echo $name; ?>_noncename" id="<?php echo $name; ?>_noncename" value="<?php echo wp_create_nonce( $name ); ?>" />
    <?php endif; ?>
    </td>
</tr>