function prettyPhoto_init() {
    jQuery("a[rel^='prettyPhoto']").prettyPhoto({
        default_width:600,
        default_height:600,
        allow_resize: false,
        deeplinking: false
    });
}
function smwLoading(id_class,type) {
    if( type == 'show' ) {
        jQuery(id_class).nimbleLoader("show");
    } else if ( type == 'hide' ) {
        jQuery(id_class).nimbleLoader("hide");
    }
}
function showValues(form_id) {
    var str = jQuery('#' + form_id).serialize();
    return str;
}
function getGalleries() {
    data = {
        action  : 'getGalleries',
        gallCount : 10,
        pageNumber : 1,
        type    : 'wordpress'
    }
    smwLoading('#wordpress-galleries','show');
    jQuery.post(ajaxurl,data, function(data) {
        jQuery('#wordpress-galleries').html(data);
        smwLoading('#wordpress-galleries','hide');
    });
}
function getService() {
    data = {
        action  : 'getGalleries',
        gall_type    : 'service'
    }
    smwLoading('#service-galleries','show');
    jQuery.post(ajaxurl,data, function(data) {
        jQuery('#service-galleries').html(data);
        smwLoading('#service-galleries','hide');
    });
}
function syncAllGalleries() {
    smwLoading('.wrap','show');
    var data = {
        action  : 'syncAllGalleries'
    }
    jQuery.post( ajaxurl, data, function( data ) {
        smwLoading('.wrap','show');
        getWPGalleries();
    });
}
function galleryName(id) {
    return
}
function smwDialog(dialog_id, type, dialog_function) {
    if( type == 'single' ) {
        jQuery( "#" + dialog_id ).dialog({
            resizable: false,
            width: 345,
            //height:140,
            modal: true,
            autoOpen: false,
            buttons: {
                "Continue": function() {
                    jQuery( this ).dialog( "close" );
                    dialog_function();
                },
                Cancel: function() {
                    jQuery( this ).dialog( "close" );
                }
            }
        });
        jQuery( "#" + dialog_id ).dialog( 'open' );
    } else if ( type == 'double' ) {
        jQuery( "#" + dialog_id ).dialog({
            resizable: false,
            width: 345,
            //height:140,
            modal: true,
            autoOpen: false,
            buttons: {
                "Continue": function() {
                    jQuery( this ).dialog( "close" );
                    jQuery( "#"+dialog_id+"-final" ).dialog( 'open' );
                },
                Cancel: function() {
                    jQuery( this ).dialog( "close" );
                }
            }
        });
        jQuery( "#"+dialog_id+"-final" ).dialog({
            resizable: false,
            width: 345,
            //height:140,
            modal: true,
            autoOpen: false,
            buttons: {
                "Are You Sure?": function() {
                    jQuery( this ).dialog( "close" );
                    dialog_function();
                    
                },
                Cancel: function() {
                    jQuery( this ).dialog( "close" );
                }
            }
        });
        jQuery("#" + dialog_id).dialog( 'open' );
    }
    
}
function excludeGalleries( type, id ) {
    
    if( type == 'single' ) {
        var excludeGalleries = function() {
            var data = {
                action  : 'exclude_galleries',
                type    : type,
                id      : id
            }
            smwLoading('.wrap','show');
            jQuery.post(ajaxurl,data,function(data) {
                smwLoading('.wrap','hide');
                jQuery('#' + id).fadeOut('slow', function() {
                    var html = jQuery(this).clone();
                    var name = jQuery(html).children('.gallery-name').text();
                    jQuery(this).remove();
                    if(jQuery('#service-galleries table').length != 0) {
                        jQuery('#service-galleries table').children('tbody').prepend(html);
                        jQuery('#' + id + ' .gallery-name').html(name);
                        jQuery('#' + id).fadeIn('slow');
                    }
                });
            });
        }
        smwDialog('dialog-exclude-' + id, 'double', excludeGalleries);
    } else if ( type == 'bulk' ) {
        var excludeGalleries = function() {
            var data = {
                action  : 'exclude_galleries',
                type    : type,
                id      : id
            }
            smwLoading('.wrap','show');
            jQuery.post(ajaxurl,data,function(data) {
                smwLoading('.wrap','hide');
                jQuery.each(id,function(index,value){
                    getWPGalleries();
                });
            });
        }
        smwDialog('exclude-galleries-bulk-dialog', 'double', excludeGalleries);
        //alert( type );
    } else if ( type == 'all' ) {
        var excludeGalleries = function() {
            var data = {
                action  : 'exclude_galleries',
                type    : type,
                id      : id
            }
            smwLoading('.wrap','show');
            jQuery.post(ajaxurl,data,function(data) {
                smwLoading('.wrap','hide');
                getWPGalleries();
            });
        }
        smwDialog('exclude-galleries-dialog', 'double', excludeGalleries);
    }
    
}
function addGalleries( type, id ) {
    if( type == 'single' ) {
        gall_id = id.substring(0, id.length - 7);
        var data = {
            action  : 'addGalleries',
            type    : type,
            id      : id
        }
        smwLoading('.wrap','show');
        jQuery.post(ajaxurl,data,function(data) {
            //alert(data);
            smwLoading('.wrap','hide');
            jQuery('#' + gall_id + '-service').fadeOut('slow', function() {
                getWPGalleries();
            });
        });
    } else if ( type == 'bulk' ) {
        var data = {
            action  : 'addGalleries',
            type    : type,
            id      : id
        }
        smwLoading('.wrap','show');
        jQuery.post(ajaxurl,data,function(data) {
            //alert(data);
            smwLoading('.wrap','hide');
            jQuery.each(id,function(index,value){
                var gall_id = value.substring(0, value.length - 7);
                jQuery('#' + gall_id + '-service').fadeOut('slow', function() {
                    getWPGalleries();
                });
            });
        });
    } else if ( type == 'all' ) {
        var data = {
            action  : 'addGalleries',
            type    : type,
            id      : id
        }
        smwLoading('.wrap','show');
        jQuery.post(ajaxurl,data,function(data) {
            smwLoading('.wrap','hide');
            jQuery('#service-galleries').fadeOut('slow', function() {
                jQuery(this).html(data);
                jQuery(this).fadeIn('slow');
                getWPGalleries();
                
            });
        });
   }
}
function getWPGalleries() {
    var extra = Array();
    extra.gall_type = "wordpress";
    var data = pagination_ajax_data('wordpress-galleries','getGalleries',extra);
    jQuery.extend(data,extra);
    data.type = 'pagination';
    jQuery("#wordpress-galleries").nimbleLoader("show");
    jQuery.post(ajaxurl, data, function(response) {
        jQuery("#wordpress-galleries").html(response);
        jQuery("#wordpress-galleries").nimbleLoader("hide");
    });
}
function timeout_fade($elem_id,$time) {
    setTimeout(function() {
        jQuery($elem_id).fadeOut('fast');
    }, $time);
}
function checkbox_val(group) {
    var values = new Array();
    jQuery.each(jQuery(".bulk-value input[name='" + group + "[]']:checked"), function() {
      values.push(jQuery(this).val());
      //alert(jQuery(this).val());
      // or you can do something to the actual checked checkboxes by working directly with  'this'
      // something like $(this).hide() (only something useful, probably) :P
    });
    return values;
}
function smw_pagination(id,action,extra) {
    jQuery.address.init(function() {

    }).change(function(event) {
        var data = pagination_ajax_data(id,action);
        jQuery.extend(data,extra);
        //alert(data.gall_type);
        
        if(data.change) {
            data.type = 'pagination';
            jQuery("#" + id).nimbleLoader("show");
            jQuery.post(ajaxurl, data, function(response) {
                jQuery("#" + id).html(response);
                prettyPhoto_init();
                jQuery("#" + id).nimbleLoader("hide");
            });
        }
    });
    jQuery('.wrap').delegate('select[name=imgPage]','change',function() {
        var data = pagination_ajax_data(id,action);
        jQuery.extend(data,extra);

        data.type = 'img_number';
        jQuery("#" + id).nimbleLoader("show");
        jQuery.post(ajaxurl, data, function(response) {
            //alert($.address.value().replace('/page/',''));
            if((data.count / data.number) < jQuery.address.value().replace('/page/','')) {
                jQuery.address.value('/page/' + data.page_number);
            }
            jQuery("#" + id).html(response);
            prettyPhoto_init();
            jQuery("#" + id).nimbleLoader("hide");
            //alert('Got this from the server: ' + data);
        });

    });
}
function pagination_ajax_data(id,action) {
    var data = {
        action: action,
        number: jQuery('select[name=imgPage]').val() ? jQuery('select[name=imgPage]').val() : 10,
        count: jQuery("#" + id).attr('count'),
        page_number: jQuery.address.value().replace('/page/',''),
        location: 'admin',
        change: true
    }
    if(data.count != 0) {
        if(Math.ceil(data.count / data.number) < data.page_number) {
            var page_number = Math.ceil(data.count / data.number);
            data.page_number = page_number;
            data.change = false;
        }
    }

    return data;
}
