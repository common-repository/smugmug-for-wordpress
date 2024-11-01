<?php
/**
 * The javascript for the edit gallery pages
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<script type="text/javascript" >
	function smw_ajax_start(ajaxElementId, loadingId, element_call) {
		var bind_var = 'ajaxStart.' + element_call;
		//alert(loadingId);
		jQuery(ajaxElementId).bind(bind_var, function() {
			jQuery(loadingId).fadeIn(500);
			jQuery(this).fadeOut(600);
		});
	}
	function smw_ajax_unbind(ajaxElementId, element_call) {
		jQuery(ajaxElementId).unbind(element_call);
	}
	function smw_ajax_stop(ajaxElementId, loadingId, element_call) {
		var bind_var = 'ajaxStart.' + element_call;
		
		jQuery(ajaxElementId).ajaxStop(function() {
			jQuery(loadingId).fadeOut(500);
			jQuery(this).fadeIn(600);
		});
	}
	
	function exif_data_open() {
		jQuery('.info .image-exif').click(function () {
			jQuery('#admin-image-list').unbind('.loadingStart');
			
			var exif_id = jQuery(this).attr('id');
			var exif_key = jQuery(this).attr('key');
			var filename = jQuery(this).attr('filename');
			
			var data_exif = {
				//pass_ajaxurl: ajaxurl,
				action: 'imageEXIF',
				id: exif_id,
				key: exif_key,
				filename: filename
			};
			
			jQuery.post(ajaxurl, data_exif, function(data) {
				jQuery('#exif-interior').html(data);
				jQuery.prettyPhoto.open('#exif-data');
				exif_data_open();
				
			});
		});
	}
	
	function unbind_onhover(click_element, unbind_element, unbind_call) {
		jQuery(click_element).hover(function() {
			jQuery(unbind_element).unbind(unbind_call);
		});
	}
	
	function image_hide(hide_id) {
            var id = jQuery("#" + hide_id).closest("tr").attr("id");
            var imagehide = jQuery("#" + hide_id).children('.image-hide').attr('imagehide');
            var data_hide = {
                    action: 'imageHide',
                    id: id,
                    hidden: imagehide
            };
            jQuery('#' + hide_id).fadeOut('slow');
            jQuery.post(ajaxurl, data_hide, function(data) {
                    jQuery('#' + hide_id).html(data);
                    jQuery('#' + hide_id).fadeIn('slow');
            });
	}
        
        function caption_save(id) {
            var hide_id = jQuery("#" + id).parent().attr('id');
            var image_id = jQuery("#" + id).closest("tr").attr("id");
            var caption = jQuery("#" + id).siblings('textarea').val();
            jQuery('#' + hide_id).fadeOut(600);
            //alert(hide_id);
            var data_caption = {
                action: 'caption_save',
                image_id: image_id,
                caption: caption
            };
            jQuery.post(ajaxurl,data_caption, function(data) {
                jQuery('#caption-' + image_id + ' textarea').html(data);
                jQuery('#' + hide_id).fadeIn(600);
            });
        }
        function subcategory_new() {
            
        }

        function subcategory_get() {
            jQuery('select[name=CategoryID]').change(function() {
                subcategory_function();
            });
        }
        function subcategory_function() {
            var category_id = jQuery('select[name=CategoryID]').val();
            var data_subcategory = {
                action:'subcategories_get',
                <?php if(!empty($this->gallery['sub_category_id'])): ?>
                subcategory_id: <?php echo $this->gallery['sub_category_id']; ?>,
                <?php endif; ?>
                category_id: category_id,
            };
            jQuery.post(ajaxurl,data_subcategory, function(data) {
                jQuery('select[name=SubCategoryID]').html(data);
                //subcategory_new();
            });
        }
	function gallery_edit() {
            jQuery('.wrap').delegate('button#edit-gallery-submit','click',function() {
                jQuery("#edit-gallery").nimbleLoader("show");
                var form_id = jQuery(this).siblings('form').attr('id');
                var check_boxes = new Array();

                jQuery(':checkbox', '#' + form_id).each(function(i) {
                    if (jQuery(this).attr('type') == 'checkbox') {
                            var key = jQuery(this).attr('name');
                    }
                    check_boxes[i] = key;
                });
                var form_data = showValues(form_id);
                var data3 = {
                    action: 'editGallery',
                    id: '<?php echo $this->id; ?>',
                    form_data: form_data,
                    check_boxes: check_boxes
                };
                jQuery.post(ajaxurl,data3, function(data) {
                    prettyPhoto_init();
                    subcategory_get();
                    jQuery("#edit-gallery").nimbleLoader("hide");
                });
            });
	}
        
	jQuery(function($) {
            var extra = Array();
            extra.id = jQuery("#admin-image-list").attr('gal_id'),
            smw_pagination('admin-image-list','imagesAjax',extra);
            $('.wrap').delegate('.caption-save','click',function() {
                var id = $(this).attr('id');
                caption_save(id);
            });
            
            $('.wrap').delegate('.image-hide','click',function() {
                var id = $(this).parent().attr('id');
                image_hide(id);
            });
            
            gallery_edit();
            subcategory_get();
            subcategory_new();
            jQuery('.wrap').delegate('#edit-gallery-toggle','click',function() {
                subcategory_function();
                if ($('#edit-gallery-toggle').hasClass('open')) {
                    $('#edit').slideToggle('slow',function() {
                        $('#edit-gallery-toggle').text('Edit Gallery');
                        $('#edit-gallery-toggle').removeClass('open');
                    });
                } else {
                    $('#edit').slideToggle('slow',function() {
                        $('#edit-gallery-toggle').text('Stop Editing Gallery');
                        $('#edit-gallery-toggle').addClass('open');
                    });
                }

            });
            jQuery('.wrap').delegate('select[name=SubCategoryID]','change',function() {
                    if(jQuery(this).val() == 'new') {
                            jQuery('#new-subcategory').slideDown();
                    } else {
                            jQuery('#new-subcategory').slideUp();
                    }
            });
	});
</script>