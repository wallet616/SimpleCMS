<?php

class Helpers {

    public static function sanitize_string($str = null, $convert_to_html = false, $max_length = 1000) : string {
        if (is_string($str))
        {
            $str = trim($str); // Remove spaces from begining and ending

            $str = preg_replace("/[\r\n]+/", "{br}", $str); // Multiple new lines into marker
            $str = preg_replace("/\s+/", " ", $str); // Multiple white characters to space
            $str = preg_replace("/</", "&lt;", $str); // < to \<
            $str = preg_replace("/>/", "&gt;", $str); // < to \<
            $str = preg_replace("/(\s*{br}\s*)/", "{br}", $str); // remove space bofore new line
            if ($convert_to_html === true) 
            {
                $str = preg_replace("/{br}/", "<br>\n", $str); // market into html new line
            } 
            else 
            {
                $str = preg_replace("/{br}/", "\n", $str); // remove white characters bofore new line
            }

            if (strlen($str) > $max_length) {
                $str = substr($str, 0, $max_length);
            } 
            
            return $str;
        } 
        else
        {
            return "";
        }
    }

}

?>