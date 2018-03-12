<?php

require_once "../configuration.php";
require_once "../classes/User.php";
require_once "../classes/Response.php";
require_once "../classes/ErrorElement.php";
require_once "../classes/Cookies.php";
require_once "../classes/Product.php";


$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 


$link = (isset($_GET["link"])) ? $_GET["link"] : null;
$product_id = Product::parse_link($link);
$language = Cookies::get_language();
Cookies::set_language($language);


$response = Product::get($db, $product_id, $language);

die(json_encode($response));

?>