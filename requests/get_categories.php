<?php

require_once "../configuration.php";
require_once "../classes/User.php";
require_once "../classes/Response.php";
require_once "../classes/ErrorElement.php";
require_once "../classes/Categories.php";
require_once "../classes/Cookies.php";


$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 


$category_name = (isset($_GET["category_name"])) ? $_GET["category_name"] : null;
$language = Cookies::get_language();
Cookies::set_language($language);

$response = Categories::get_tree($db, $language, $category_name);

die(json_encode($response));

?>