<?php

class URI {
    
    public function filterUri($str, $config){
        if ($str != '' && $config['permitted_uri_chars'] != '' && $config['enable_query_strings'] == FALSE){
	        // preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
	        // compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
	        if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($config['permitted_uri_chars'], '-'))."]+$|i", $str)){
		        //show_error('The URI you submitted has disallowed characters.', 400);
                header("HTTP/1.1 400 Bad Request");
                header("Cache-Control: no-store");
                echo "The URI you submitted has disallowed characters.";
                exit();
                //header('Content-type: application/json');
                //echo json_encode($data);
	        }
        }

        // Convert programatic characters to entities
        $bad	= array('$',		'(',		')',		'%28',		'%29');
        $good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

        return str_replace($bad, $good, $str);
    }
}
