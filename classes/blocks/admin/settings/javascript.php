<?php
/**
 * The admin settings page javascript block
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
    function timeout_fade($elem_id,$time) {
        setTimeout(function() {
            jQuery('#' + $elem_id).fadeOut('fast');
        }, $time);
    }
    jQuery(function($) {
        $('#general-settings-title').click(function() {
            $('#general-settings-form-wrapper').slideToggle('slow');
        });
        $('#lightbox-settings-title').click(function() {
            $('#lightbox-settings-form-wrapper').slideToggle('slow');
        });
        $('#custom-post-settings-title').click(function() {
            $('#custom-post-settings-form-wrapper').slideToggle('slow');
        });
        $('#remove-auth').click(function () {
            var data_auth = {
                action: 'remove_auth'
            }
            $( "#dialog-remove-auth" ).dialog({
                resizable: false,
                width: 345,
                //height:140,
                modal: true,
                buttons: {
                    "Remove Authorization": function() {
                        $.post(ajaxurl, data_auth, function(data) {
                            $( '#dialog-remove-auth' ).dialog( "close" );
                            window.location.reload();
                        });
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        });
        $('#lightbox-settings').click(function(){
            
            var form_id = jQuery(this).parent().siblings('form').attr('id');
            var form_data = showValues(form_id);
            var section_id = jQuery(this).parent().parent().attr('id');
            jQuery("#" + section_id).nimbleLoader("show");
            var data_settings = {
                action: 'settingsAjax',
                type:'lightbox',
                form_data: form_data
            }
            
            $.post(ajaxurl, data_settings, function(data) {
                $( '#ajax-front-lightbox' ).html(data).fadeIn();
                jQuery("#" + section_id).nimbleLoader("hide");
                timeout_fade('ajax-front-lightbox',2000);
            });
        });
        $('#lightbox-settings-reset').click(function(){
            var data_settings_reset = {
                action: 'settings_frontend_reset',
                type:'lightbox'
            }
            $.post(ajaxurl, data_settings_reset, function(data) {
                location.reload(true);
            });
        });
        $('#general-settings-reset').click(function(){
            var data_settings_reset = {
                action: 'settings_frontend_reset',
                type:'general'
            }
            $.post(ajaxurl, data_settings_reset, function(data) {
                location.reload(true);
            });
        });
        $('#general-settings').click(function(){
            
            var form_id = jQuery(this).parent().siblings('#general-settings-form').attr('id');
            var form_data = showValues(form_id);
            var section_id = jQuery(this).parent().parent().attr('id');
            jQuery("#" + section_id).nimbleLoader("show");
            var data_settings = {
                action: 'settingsAjax',
                type:'general',
                form_data: form_data
            }
            $.post(ajaxurl, data_settings, function(data) {
                $( '#ajax-front-general' ).html(data).fadeIn();
                jQuery("#" + section_id).nimbleLoader("hide");
                timeout_fade('ajax-front-general',2000);
            });
        });
        $('#custom-post-settings').click(function(){
            
            var form_id = jQuery(this).parent().siblings('form').attr('id');
            var form_data = showValues(form_id);
            var section_id = jQuery(this).parent().parent().attr('id');
            jQuery("#" + section_id).nimbleLoader("show");
            var data_settings = {
                action: 'settingsAjax',
                type:'custom-post',
                form_data: form_data
            }
            $.post(ajaxurl, data_settings, function(data) {
                $( '#ajax-front-custom-post' ).html(data).fadeIn();
                jQuery("#" + section_id).nimbleLoader("hide");
                timeout_fade('ajax-front-custom-post',2000);
            });
        });
        if($('input[name=smw_lightbox_type]:checked').val() == 'prettyphoto') {
            $('.prettyPhoto_settings').each(function() {
               $('.prettyPhoto_settings').fadeIn('slow');
           });
        }
        $('input[name=smw_lightbox_type]').change(function() {
           if($(this).val() == 'prettyphoto') {
               $('.prettyPhoto_settings').each(function() {
                   $('.prettyPhoto_settings').fadeIn('slow');
               });
               
           } else {
               $('.prettyPhoto_settings').each(function() {
                   $('.prettyPhoto_settings').fadeOut('slow');
               });
           }
        });
        var params1 = {
            loaderClass :   "loading_bar",
            debug       :   true,
            speed       :   'fast'
        };
        $.fn.nimbleLoader.setSettings(params1);
    });
</script>