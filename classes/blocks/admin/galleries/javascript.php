<?php
/**
 * The javascript block for the  manage galleries section of the admin
 * 
 * @package SMW
 * @subpackage Blocks
 * @author  Anthony Humes
 **/
?>
<script type="text/javascript" >
    jQuery(function($) {
    var extra = Array();
    extra.gall_type = "wordpress";
    
    //alert(data.page_number);
    smw_pagination('wordpress-galleries','getGalleries',extra);
    $('.wrap').delegate('#get-service','click', function() {
        getService();
    });
    $('.wrap').delegate(".smw-column-header input[type='checkbox']",'click', function() {
        var name = $(this).attr('name');
        $("input[name='"+name+"']").attr('checked', $(this).is(':checked'));    
    });
    $('.wrap').delegate(".checkbox input[type='checkbox']",'click', function() {
        var name = $(this).attr('name');
        $(".smw-column-header input[name='"+name+"']").attr('checked', $(this).is(':checked'));    
    });
    $('.wrap').delegate(".smw-doaction",'click', function() {
        var action = $(this).siblings("select[name='action']").val();
        if(action != -1) {
            var id = $(this).attr('id');
            //alert(action);
            var values = checkbox_val(id);
            if(values != '') {
                if( action == 'excludeGalleries' ) {
                    excludeGalleries( 'bulk', values );
                } else if ( action == 'addGalleries') {
                    addGalleries('bulk',values);
                }
            }
            
        }  
    });
    jQuery('.wrap').delegate('.exclude-gallery','click',function() {
        var type = $( this ).attr( 'type_id' );
        if(type == 'single') {
            var id = $( this ).parent().parent('tr').attr( 'id' );
            excludeGalleries(type,id);
        } else if ( type == 'all' ) {
            var id = 'all';
            excludeGalleries(type,id);
        }
    });
    jQuery('.wrap').delegate('#add-all-service','click',function() {
        addGalleries('all',true);
    });
    jQuery('.wrap').delegate('.add-gallery','click',function() {
        var id = $(this).parent().siblings('.bulk-value').children('input').val();
        addGalleries('single',id);
    });
    jQuery('.wrap').delegate('#sync-wordpress','click',function() {
        syncAllGalleries();
    });
    
});
</script>
