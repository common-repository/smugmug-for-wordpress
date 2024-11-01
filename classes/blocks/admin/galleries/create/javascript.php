<?php
/**
 * The javascript for the create gallery page
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<script type="text/javascript" >
    function showValues(form_id) {
      var str = jQuery('#' + form_id).serialize();
      //jQuery("#results").text(str);
      return str;
    }
    function timeout_fade(elem_id,time) {
        setTimeout(function() {
            jQuery(elem_id).fadeOut('slow');
        }, time);
    }
    function create_gallery() {
        jQuery('#create-gallery').click(function(){
            jQuery(".wrap").nimbleLoader("show");
            var form_id = jQuery(this).parent().siblings("#create-gallery-form").attr('id');
            var form_data = showValues(form_id);
            var data = {
                action: 'gallery_create',
                form_data: form_data
            }
            
            jQuery.post(ajaxurl, data, function(data) {
                if (data == 'No Title') {
                    jQuery('#ajax-response').html('<div class="message error"><p><strong>Title is Required</strong></p><p>You must fill out a title to create a <?php echo SMW_SERVICE; ?> Gallery</p></div>').fadeIn('slow');
                    jQuery('label[for=Title]').parent().css('background-color','#E44')
                    timeout_fade('#ajax-response',4000);
                } else if (data == 'Fail') {
                    
                } else {
                    jQuery(".wrap").nimbleLoader("hide");
                    window.location = "<?php echo SMW_CURRENT_URL; ?>/wp-admin/admin.php?page=smugmug-edit-gallery" + data;
                }
                //$( '#ajax-front-lightbox' ).html(data).fadeIn();
                
            });
        });
    }
    function subcategory_new() {
    jQuery('select[name=SubCategoryID]').change(function() {
            if(jQuery(this).val() == 'new') {
                jQuery('#new-subcategory').slideDown();
            } else {
                jQuery('#new-subcategory input[type=text]').val("");
                jQuery('#new-subcategory').slideUp();
                
            }
    });
    }
    
    function subcategory_get() {
    	jQuery('select[name=CategoryID]').change(function() {
            var category_id = jQuery(this).val();
            var data_subcategory = {
                action:'subcategories_get',
                category_id: category_id,
            };
            if(jQuery(this).val() == 'new') {
                jQuery('#new-category').slideDown();
            } else {
                jQuery('#new-category').slideUp();

            }
            jQuery.post(ajaxurl,data_subcategory, function(data) {
                jQuery('select[name=SubCategoryID]').html(data);
                subcategory_new();
            });
    	});
    }
    jQuery(function($) {
        create_gallery();
        var category_id = jQuery('select[name=CategoryID]').val();
        var data_subcategory = {
                action:'subcategories_get',
                category_id: category_id,
        };
        $.post(ajaxurl,data_subcategory, function(data) {
                jQuery('select[name=SubCategoryID]').html(data);
                subcategory_new();
                subcategory_get();
        });
        var params1 = {
            loaderClass :   "loading_bar",
            debug       :   true,
            speed       :   'medium'
        };
        $.fn.nimbleLoader.setSettings(params1);
    });
</script>