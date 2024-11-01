<?php
/**
 * The javascript for the category pages
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<script type="text/javascript" charset="utf-8">
    function showValues(form_id) {
      var str = jQuery('#' + form_id).serialize();
      //jQuery("#results").text(str);
      return str;
    }
    function timeout_fade(par_id,elem_id,time) {
        setTimeout(function() {
            jQuery('#' + par_id).children(elem_id).fadeOut('slow');
        }, time);
    }
    function subcategory_start(cat_id,subcat_id,form_id) {
    	var category_id = jQuery(cat_id).val();
        var data_subcategory = {
            action:'subcategories_get',
            page: 'category',
            category_id: category_id
        };
        jQuery.post(ajaxurl,data_subcategory, function(data) {
            jQuery(subcat_id).html(data);
            //$(this).parentsUntil('tr').siblings('#subcategory').children('td').children('select').html(data);
            subcategory_get(cat_id,subcat_id,form_id);
        });
    }
    function subcategory_get(cat_id,subcat_id,form_id) {
    	jQuery(cat_id).change(function() {
            jQuery(form_id).nimbleLoader("show");
            var category_id = jQuery(this).val();
            var data_subcategory = {
                action:'subcategories_get',
                page: 'category',
                category_id: category_id
            };
            jQuery.post(ajaxurl,data_subcategory, function(data) {
                jQuery(form_id).nimbleLoader("hide");
                jQuery(subcat_id).html(data);
            });
    		
    	});
    }
    function slide_down(link_id,slide_id,open_text,close_text) {
    	jQuery(link_id).click(function() {
            jQuery(this).next().slideToggle(function() {
                if (jQuery(link_id).hasClass('open')) {
                    jQuery(link_id).text(close_text);
                    jQuery(link_id).removeClass('open');
                    
                } else {
                    jQuery(link_id).text(open_text);
                    jQuery(link_id).addClass('open');
                    
                }
            });
    	});
    }
    function categoryDelete() {
        jQuery( "#dialog-delete-category" ).dialog({
            
            resizable: false,
            width: 345,
            //height:140,
            modal: true,
            autoOpen: false,
            buttons: {
                "Delete": function() {
                    jQuery( this ).dialog( "close" );
                    var form_id = 'delete-category';
                    var section_id = 'delete-category-wrapper';
                    categoryPost(form_id,section_id);
                },
                Cancel: function() {
                    jQuery( this ).dialog( "close" );
                }
            }
        });
        jQuery('button#category-delete-submit').click(function() {
            if(jQuery('select[name=CategoryID]').val() == 'select') {
                alert('Please select a category to delete.');
            } else {
                jQuery("#dialog-delete-category").dialog('open');
            }
        });
    }
    function categoryCreate() {
        jQuery('button#category-create-submit').click(function() {
            if(!jQuery('input[name=Name]').val()) {
                alert('Please enter the name for your new Category.');
                jQuery('input[name=Name]').css('border-color', 'rgb(255,0,0)');
                jQuery('input[name=Name]').focus();
            } else {
                var form_id = jQuery(this).parent().siblings('form').attr('id');
                var section_id = jQuery(this).parent().parent().attr('id');
                categoryPost(form_id,section_id);
            }
        });
    }
    function categoryRename() {
        jQuery('button#category-rename-submit').click(function() {
            var form_id = jQuery(this).parent().siblings('form').attr('id');
            var section_id = jQuery(this).parent().parent().attr('id');
            categoryPost(form_id,section_id);
        });
    }
    function categoryPost(form_id,section_id) {
        jQuery("#" + section_id).nimbleLoader("show");
        var form_data = showValues(form_id);
        var category_data = {
            action      :   'categoryChange',
            form_data   :   form_data
        };
        jQuery.post(ajaxurl,category_data, function(data) {
            jQuery('#' + section_id).children('#ajax-response').html(data['message']).fadeIn('fast');
            jQuery('#CategoryID').html(data['user_categories']);
            jQuery('#CategoryIDRename').html(data['user_categories']);
            jQuery('#CategoryIDNewSub').html(data['categories']);
            jQuery('#CategoryIDDeleteSub').html(data['categories']);
            jQuery('#CategoryIDRenameSub').html(data['categories']);
            jQuery('#' + form_id).formReset();
            jQuery("#" + section_id).nimbleLoader("hide");
            timeout_fade(section_id,'#ajax-response',3000);
        }, "json");
    }
    function subCategoryCreate() {
        jQuery('button#sub-category-create-submit').click(function() {
            if(!jQuery('input[name=SubCategoryNameNew]').val()) {
                alert('Please enter the name for your new Sub-Category.');
                jQuery('input[name=SubCategoryNameNew]').css('border-color', 'rgb(255,0,0)');
                jQuery('input[name=SubCategoryNameNew]').focus();
            } else {
                var form_id = jQuery(this).parent().siblings('form').attr('id');
                var section_id = jQuery(this).parent().parent().attr('id');
                subCategoryPost(form_id,section_id);
            }
        });
    }
    function subCategoryDelete() {
        jQuery( "#dialog-delete-sub-category" ).dialog({
            
            resizable: false,
            width: 345,
            //height:140,
            modal: true,
            autoOpen: false,
            buttons: {
                "Delete": function() {
                    jQuery( this ).dialog( "close" );
                    var form_id = 'delete-sub-category';
                    var section_id = 'delete-sub-category-wrapper';
                    subCategoryPost(form_id,section_id);
                },
                Cancel: function() {
                    jQuery( this ).dialog( "close" );
                }
            }
        });
        jQuery('button#sub-category-delete-submit').click(function() {
            if(!jQuery('select[name=SubCategoryIDDelete]').val()) {
                alert('Please select a sub-category to delete.');
            } else {
                jQuery("#dialog-delete-sub-category").dialog('open');
            }
        }); 
    }
    function subCategoryPost(form_id,section_id) {
        jQuery("#" + section_id).nimbleLoader("show");
        var form_data = showValues(form_id);
        var category_data = {
            action      :   'subCategoryChange',
            form_data   :   form_data
        };
        jQuery.post(ajaxurl,category_data, function(data) {
            jQuery('#' + section_id).children('#ajax-response').html(data['message']).fadeIn('fast');
            jQuery('#' + form_id).formReset();
            jQuery("#" + section_id).nimbleLoader("hide");
            timeout_fade(section_id,'#ajax-response',3000);
        }, "json");
    }
    function subCategoryRename() {
        jQuery('button#sub-category-rename-submit').click(function() {
            var form_id = jQuery(this).parent().siblings('form').attr('id');
            var section_id = jQuery(this).parent().parent().attr('id');
            jQuery("#" + section_id).nimbleLoader("show");
            var form_data = showValues(form_id);
            var category_data = {
                action      :   'subCategoryChange',
                form_data   :   form_data
            };
            jQuery.post(ajaxurl,category_data, function(data) {
                jQuery('#' + section_id).children('#ajax-response').html(data['message']).fadeIn('fast');
                jQuery("#" + section_id).nimbleLoader("hide");
                //alert(data);
                //jQuery(subcat_id).html(data);
                timeout_fade(section_id,'#ajax-response',3000);
            }, "json");
        });
    }
    
    jQuery(function($) {
        $.fn.formReset = function () {
          $(this).each (function() { this.reset(); });
        }
        
        categoryDelete();
        categoryCreate();
        categoryRename();
        subCategoryCreate();
        subCategoryDelete();
        subCategoryRename();
        slide_down('#category','#categories','Stop Editing Categories','Edit Categories');
        slide_down('#subcategory','#sub-categories','Stop Editing Subcategories','Edit Subcategories');
        subcategory_start('select[name=CategoryIDRenameSub]','select[name=SubCategoryIDRename]','#rename-sub-category');
        subcategory_start('select[name=CategoryIDDeleteSub]','select[name=SubCategoryIDDelete]','#delete-sub-category');
        var params1 = {
            loaderClass :   "loading_bar",
            debug       :   true,
            speed       :   'fast'
        };
        $.fn.nimbleLoader.setSettings(params1);
        
    });
</script>