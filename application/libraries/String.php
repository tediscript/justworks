<?php

class String {
    
    public function __construct(){     
    }
    
    function startsWith($prefix, $string, $caseSensitive = true) {
        if(!$caseSensitive) {
            return stripos($string, $prefix, 0) === 0;
        }
        return strpos($string, $prefix, 0) === 0;
    }

    function endsWith($postfix, $string, $caseSensitive = true) {
        $expectedPostition = strlen($string) - strlen($postfix);
        if(!$caseSensitive) {
            return strripos($string, $postfix, 0) === $expectedPostition;
        }
        return strrpos($string, $postfix, 0) === $expectedPostition;
    }

}
