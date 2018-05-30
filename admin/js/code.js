jQuery(document).ready(function($){
    // https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
    var code = {
        100: 'Continue',
        101: 'Switching protocol',
        200: 'OK',
        201: 'Created',
        202: 'Accepted',
        203: 'Non-Authoritative Information',
        204: 'No Content',
        205: 'Reset Content',
        206: 'Partial Content',
        300: 'Multiple Choices',
        301: 'Moved Permanently',
        302: 'Found',
        303: 'See Other',
        304: 'Not Modified',
        305: 'Use Proxy',
        306: 'Unused',
        307: 'Temporary Redirect',
        400: 'Bad Request',
        401: 'Unauthorized',
        402: 'Payment Required',
        403: 'Forbidden',
        404: 'Not Found',
        405: 'Method Not Allowed',
        406: 'Not Accettable',
        407: 'Proxy Authentication Required',
        408: 'Request Timeout',
        409: 'Conflict',
        410: 'Gone',
        411: 'Length Required',
        412: 'Precondition Failed',
        413: 'Request Entity Too Large',
        414: 'Request-URI Too Long',
        415: 'Unsupported Media Type',
        416: 'Requested Range Not Satisfiable',
        417: 'Expectation Failed',
        500: 'Internal Server Error',
        501: 'Not Implemented',
        502: 'Bad Gateway',
        503: 'Service Unavailable',
        504: 'Gateway Timeout',
        505: 'HTTP Version Not Supported'
    };
    var td = $('td.code.column-code');
    $.each(td, function(i,v){
        {
            var status = $(v).text();
            $.each(code, function(ii,vv){
                var s = $('span');
                if(ii == status){
                    var n = status.charAt(0);
                    if(n == 1){
                        $(v).css('color','lightblue');
                    }
                    else if(n == 2){
                        $(v).css('color','green');
                    }
                    else if(n == 3){
                        $(v).css('color','orange');
                    }  
                    else if(n == 4){
                        $(v).css('color','red');
                    } else {
                        $(v).css('color','red');
                    }   
                }
            });
        }
    });
    function addStatusLabel(label, ){

    }
});