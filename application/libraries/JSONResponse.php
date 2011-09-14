<?php

class JSONResponse {

    public function __construct() {
    }

    public function ok($data = array()) {
        header("HTTP/1.1 200 OK");
        header("Cache-Control: no-store");
        if (!empty($data)) {
            header('Content-type: application/json');
            echo json_encode($data);
        }
    }

    public function bad_request($data = array()) {
        header("HTTP/1.1 400 Bad Request");
        header("Cache-Control: no-store");
        if (!empty($data)) {
            header('Content-type: application/json');
            echo json_encode($data);
        }
    }

    public function unauthorized($data = array()) {
        header("HTTP/1.1 401 Unauthorized");
        header("Cache-Control: no-store");
        if (!empty($data)) {
            header('Content-type: application/json');
            echo json_encode($data);
        }
    }

    public function forbidden($data = array()) {
        header("HTTP/1.1 403 Forbidden");
        header("Cache-Control: no-store");
        if (!empty($data)) {
            header('Content-type: application/json');
            echo json_encode($data);
        }
    }

    public function not_found($data = array()) {
        header("HTTP/1.1 404 Not Found");
        header("Cache-Control: no-store");
        if (!empty($data)) {
            header('Content-type: application/json');
            echo json_encode($data);
        }
    }

    public function method_not_allowed($data = array()) {
        header("HTTP/1.1 405 Method Not Allowed");
        header("Cache-Control: no-store");
        if (!empty($data)) {
            header('Content-type: application/json');
            echo json_encode($data);
        }
    }

    public function invalid_request() {
        $data["error"] = "invalid_request";
        $this->bad_request($data);
    }

    public function invalid_client() {
        $data["error"] = "invalid_client";
        $this->bad_request($data);
    }

    public function invalid_grant() {
        $data["error"] = "invalid_grant";
        $this->bad_request($data);
    }

    public function unauthorized_client() {
        $data["error"] = "unauthorized_client";
        $this->unauthorized($data);
    }

    public function unsupported_grant_type() {
        $data["error"] = "unsupported_grant_type";
        $this->bad_request($data);
    }

    public function invalid_scope() {
        $data["error"] = "invalid_scope";
        $this->bad_request($data);
    }

    public function access_denied() {
        $data["error"] = "access_denied";
        $this->bad_request($data);
    }

    /**
     * Indents a flat JSON string to make it more human-readable.
     *
     * @param string $json The original JSON string to process.
     *
     * @return string Indented version of the original JSON string.
     */
    function indent($json) {

        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '  ';
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;

        for ($i = 0; $i <= $strLen; $i++) {

            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;

                // If this character is the end of an element, 
                // output a new line and indent the next line.
            } else if (($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element, 
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            $prevChar = $char;
        }

        return $result;
    }

}
