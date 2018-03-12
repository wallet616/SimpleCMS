<?php

require_once "../configuration.php";
require_once "../classes/Response.php";
require_once "../classes/Cookies.php";


$command = (isset($_POST["command"])) ? $_POST["command"] : null;
$language = (isset($_POST["language"])) ? $_POST["language"] : null;
$response = null;

switch ($command) {
    case "get":
        $lang = new stdClass();
        $lang->language = Cookies::get_language();

        $response = new Response("OK", $lang);
        break;
    case "set":
        $_COOKIE["language"] = $language;
        $language = Cookies::get_language();
        Cookies::set_language($language);

        $response = new Response("OK");
        break;

    default:
        $response = new Response("NO_OK", new ErrorElement("unrecognised", "command"));
        die(json_encode($response));
}

die(json_encode($response));

?>