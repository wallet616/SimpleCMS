<?php

require_once "../configuration.php";
require_once "../classes/User.php";
require_once "../classes/Response.php";
require_once "../classes/ErrorElement.php";
require_once "../classes/Product.php";

session_start();

$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

$_SESSION["user"] = $user = (isset($_SESSION["user"])) ? $_SESSION["user"] : new User();

if ($user->is_logged === false) {
    $response = new Response("NO_OK", new ErrorElement("not logged in", "user"));
    die(json_encode($response));
}

$command = (isset($_POST["command"])) ? $_POST["command"] : null;
$product_id = (isset($_POST["product_id"])) ? $_POST["product_id"] : null;
$category_id = (isset($_POST["category_id"])) ? $_POST["category_id"] : null;
$product_code = (isset($_POST["product_code"])) ? $_POST["product_code"] : null;
$name_pl = (isset($_POST["name_pl"])) ? $_POST["name_pl"] : null;
$name_en = (isset($_POST["name_en"])) ? $_POST["name_en"] : null;
$description_pl = (isset($_POST["description_pl"])) ? $_POST["description_pl"] : null;
$description_en = (isset($_POST["description_en"])) ? $_POST["description_en"] : null;
$response = null;

switch ($command) {
    case "create":
        $response = Product::create($db, $category_id, $product_code, $name_pl, $name_en, $description_pl, $description_en);
        break;
    case "delete":
        //$response = Product::delete($db, $product_id);
        break;
    case "update":
        //$response = Product::update($db, $id, $subcategory_of, $name_pl, $name_en);
        break;

    default:
        $response = new Response("NO_OK", new ErrorElement("unrecognised", "command"));
        die(json_encode($response));
}

die(json_encode($response));

?>