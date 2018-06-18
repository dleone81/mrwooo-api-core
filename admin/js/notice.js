var notice = {
    new: function(o) {
        var _i = jQuery.extend(true, {}, this);
        jQuery.extend(true, _i.options, o);
        return _i;
    },
    init: function(){
        ts = this;
    },
    getNotice: function(method, status, msg = null, data = null){

        var notice = jQuery('div.notice');
        if(notice.length > 0)
            notice.remove();

        // admin notice
        var container = jQuery('<div>');
        container.addClass('notice');

        var p = jQuery('<p>');
        container.append(p);

        /* 
        *   notice class wp legacy
        *   notice-info (blue)
        *   notice-success (green)
        *   notice-warning (orange)
        *   notice-error (red)
        */

        if(method == 'exportUsersData'){
            switch(status){
                case 200:
                case 201:
                    var msg = "<?php __('File ready. Download now!', 'mrwooo'); ?>";
                    var a = jQuery('<a>');

                    container.addClass('notice-info');
                    a.html(msg);
                    p.append(a);
                    jQuery('div#wpbody-content').prepend(container);
                    csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(data);
                    jQuery(".notice a").attr({
                        "href": csvData,
                        "download": "export.csv"
                    });
                    break;
                default:
                    break;
            }

        } else if(method == 'importUsersData') {
            switch(status){
                case 202:
                    container.addClass('notice-success');
                    jQuery(p).html(msg);
                    jQuery('div#wpbody-content').prepend(container);
                    break;
                case 400:
                    container.addClass('notice-warning');
                    jQuery(p).html(msg);
                    jQuery('div#wpbody-content').prepend(container);
                    break;
                case 406:
                    container.addClass('notice-error');
                    jQuery(p).html(msg);
                    jQuery('div#wpbody-content').prepend(container);
                    break;
                default:
                    break;
            }
        }
    }
}
jQuery(document).ready(function($){
    notice.new({}).init();
});