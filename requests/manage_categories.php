<?php

require_once "../configuration.php";
require_once "../classes/User.php";
require_once "../classes/Response.php";
require_once "../classes/ErrorElement.php";
require_once "../classes/Categories.php";

session_start();

$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

$_SESSION["user"] = $user = (isset($_SESSION["user"])) ? $_SESSION["user"] : new User();

if ($user->is_logged === false) {
    $response = new Response("NO_OK", new ErrorElement("not logged in", "user"));
    die(json_encode($response));
}

$command = (isset($_POST["command"])) ? $_POST["command"] : null;
$id = (isset($_POST["id"])) ? $_POST["id"] : null;
$subcategory_of = (isset($_POST["subcategory_of"])) ? $_POST["subcategory_of"] : null;
$name_pl = (isset($_POST["name_pl"])) ? $_POST["name_pl"] : null;
$name_en = (isset($_POST["name_en"])) ? $_POST["name_en"] : null;
$response = null;

switch ($command) {
    case "create":
        $response = Categories::create($db, $subcategory_of, $name_pl, $name_en);
        break;
    case "delete":
        $response = Categories::delete($db, $id);
        break;
    case "update":
        $response = Categories::update($db, $id, $subcategory_of, $name_pl, $name_en);
        break;

    default:
        $response = new Response("NO_OK", new ErrorElement("unrecognised", "command"));
        die(json_encode($response));
}

die(json_encode($response));

?>