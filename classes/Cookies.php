<?php

require_once "../configuration.php";

class Cookies {

    public static function set_language($language) : bool 
    {
        setcookie("language", $language, time() + (86400 * 365), "/");

        return true; 
    }

    public static function get_language() : string 
    {
        $langs = $GLOBALS["languages"];
        $response = $langs[0]; // First language is default.

        $language = (isset($_COOKIE["language"])) ? $_COOKIE["language"] : null;
        foreach ($langs as $l) 
        {
            if ($l == $language) 
            {
                $response = $l; // As language from cookie is the array, use it.
                break;
            }
        }

        return $response; 
    }

}

?>