<?php

require_once "../configuration.php";
require_once "../classes/User.php";
require_once "../classes/Response.php";
require_once "../classes/ErrorElement.php";

session_start();

$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

$_SESSION["user"] = $user = (isset($_SESSION["user"])) ? $_SESSION["user"] : new User();

$login = (isset($_POST["login"])) ? $_POST["login"] : null;
$password = (isset($_POST["password"])) ? $_POST["password"] : null;

$response = $user->login($db, $login, $password);

die(json_encode($response));

?>