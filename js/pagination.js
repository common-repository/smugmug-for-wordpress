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
function smw_pagination(id,action,extra) {
    
    jQuery.address.init(function() {

    }).change(function(event) {
        var data = pagination_ajax_data(id,action);
        jQuery.extend(data,extra);
        //alert(data.gall_type);
        
        if(data.change) {
            data.type = 'pagination';
            jQuery("#" + id).nimbleLoader("show");
            jQuery.post(SMW.ajaxurl, data, function(response) {
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
        jQuery.post(SMW.ajaxurl, data, function(response) {
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