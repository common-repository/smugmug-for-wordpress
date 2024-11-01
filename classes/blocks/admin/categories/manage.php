<?php
/**
 * The manage category admin block
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<div class="wrap">

    <h2><?php echo SMW_SERVICE; ?> Custom Categories</h2>
    <p>Click on one of the links below to start editing</p>
    <button id="category" class="button-secondary">Edit Categories</button>
    <div id="categories" style="display:none">
        <div id="create-category-wrapper">
            <div id="ajax-response"></div>
            <h3>Create Your Own Category:</h3>
            <form id="create-category" method="post" accept-charset="utf-8">
                <?php $this->getForm('category-create'); ?>
                <input name="FormType" type="hidden" value="category-create" />

            </form>
            <p class="submit">
                <button id="category-create-submit" class="button-primary"><?php esc_attr_e('Create Category'); ?></button>
            </p>
        </div>
        <hr />
        <div id="delete-category-wrapper">
            <div id="ajax-response"></div>
            <h3>Delete a Category</h3>

            <form id="delete-category" method="post" accept-charset="utf-8">
                <?php $this->getForm('category-delete'); ?>
                <input name="FormType" type="hidden" value="category-delete" />
            </form>
            <p class="submit">
                <button id="category-delete-submit" class="button-primary"><?php esc_attr_e('Delete Category'); ?></button>
            </p>
            <div style="display:none;" id="dialog-delete-category" title="Are You Sure?">
                <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>You are about to delete a category from both <?php echo SMW_SERVICE; ?> and Wordpress.</p><h4>Are you sure?</h4>
            </div>
        </div>
            <hr />
        <div id="rename-category-wrapper">
            <h3>Rename a Category</h3>
            
            <div id="message"><p>Under the current version of the <?php echo SMW_SERVICE; ?> API this <strong>ONE</strong> API call is broken.  It will be fixed as soon as <?php echo SMW_SERVICE; ?>'s API is fixed.</p></div>
            <?php /*
             * <form id="rename-category" method="post" accept-charset="utf-8">
                <?php $this->getForm('category-rename'); ?>
                <input name="FormType" type="hidden" value="category-rename" />
            </form>
             * <p class="submit">
                <button id="category-rename-submit" class="button-primary"><?php esc_attr_e('Rename Category'); ?></button>
            </p>
             * 
             */
            ?>
            </div>
        </div>
    <hr />
    <button id="subcategory" class="button-secondary">Edit Subcategories</button>
    <div id="sub-categories" style="display:none">
        <div id="create-sub-category-wrapper">
            <div id="ajax-response"></div>
            <h3>Create a New Sub-Category:</h3>
            <form id="create-sub-category" method="post" accept-charset="utf-8">
                <?php $this->getForm('sub-category-create'); ?>
                <input name="FormType" type="hidden" value="subcategory-create" />
                
            </form>
            <p class="submit">
                <button id="sub-category-create-submit" class="button-primary"><?php esc_attr_e('Create Sub-Category'); ?></button>
            </p>
       </div>
            <hr />
       <div id="delete-sub-category-wrapper">
            <div id="ajax-response"></div>
            <h3>Delete a Sub-Category</h3>

            <form id="delete-sub-category" method="post" accept-charset="utf-8">
                <?php $this->getForm('sub-category-delete'); ?>
                <input name="FormType" type="hidden" value="subcategory-delete" />
                
            </form>
            <p class="submit">
                <button id="sub-category-delete-submit" class="button-primary"><?php esc_attr_e('Delete Sub-Category'); ?></button>
            </p>
            <div style="display:none;" id="dialog-delete-sub-category" title="Are You Sure?">
                <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>You are about to delete a sub-category from both <?php echo SMW_SERVICE; ?> and Wordpress.</p><h4>Are you sure?</h4>
            </div>
        </div>
            <hr />
        <div id="rename-sub-category-wrapper">
            <div id="ajax-response"></div>
            <h3>Rename a Sub-Category</h3>

            <form id="rename-sub-category" method="post" accept-charset="utf-8">
                <?php $this->getForm('sub-category-rename'); ?>
                <input name="FormType" type="hidden" value="subcategory-rename" />
                
            </form>
            <p class="submit">
                <button id="sub-category-rename-submit" class="button-primary"><?php esc_attr_e('Rename Sub-Category'); ?></button>
            </p>
        </div>
    </div>
	
</div>
