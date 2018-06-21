<?php
    class MRWOOO_API_LIBS_Ajax {
        public static function loader(){ ?>
            <script type="text/javascript">
                jQuery(document).ready(function($){

                    // create a loader div
                    var loader = $('<div>');
                    loader.addClass('mrwooo ajaxloader');
                    $('body').prepend(loader);
                    $('.mrwooo.ajaxloader').hide();

                    $('.mrwooo').ajaxStart(function() {
                        $('.mrwooo.ajaxloader').fadeIn(1000);
                    });
                    $('.mrwooo').ajaxStop(function() {
                        $('.mrwooo.ajaxloader').fadeOut(500);
                    });
                    
                });
            </script>
        <? }
    }
?>