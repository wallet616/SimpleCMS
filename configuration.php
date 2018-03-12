<?php

/////////////////////////////////////////////////////////
// Debug mode

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

/////////////////////////////////////////////////////////
// Database values

header('Access-Control-Allow-Origin: *');

$use_local_host = true;

$localhost_database_host_name = "localhost";
$localhost_database_host_address = "localhost";
$localhost_database_name = "themeplate";
$localhost_database_username = "root";
$localhost_database_password = "";


$server_database_host_name = "";
$server_database_host_address = "localhost";
$server_database_name = "";
$server_database_username = "";
$server_database_password = "";

$languages = array("pl", "en");

$db_table_accounts = "accounts";
$db_table_categories = "categories";
$db_table_products = "products";

$db_password_encoding = "sha256";
$db_date_format = "Y-m-d H:i:s";


/////////////////////////////////////////////////////////
// Assigments

if ($use_local_host == true)
{
    $db_host_name = $localhost_database_host_name;
    $db_host = $localhost_database_host_address;
    $db_name = $localhost_database_name;
    $db_username = $localhost_database_username;
    $db_password = $localhost_database_password;
}
else
{
    $db_host_name = $server_database_host_name;
    $db_host = $server_database_host_address;
    $db_name = $server_database_name;
    $db_username = $server_database_username;
    $db_password = $server_database_password;
}

?>