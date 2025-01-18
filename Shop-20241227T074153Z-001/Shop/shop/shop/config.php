<?php 
ob_start();
session_start();
// Set timezone 
date_default_timezone_set('Asia/Kolkata'); 
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

define('SITE_PATH',"http://localhost/shop");
$base_url="http://localhost/shop";
$DBhost="localhost";
$DBname="shop";
$DBuser="root";
$DBpass="Konta009%";

define('SITE_SEO',"");
define('SEO',"Admin Panel");
define('SITE_TITLE',"Shop");
define('SITE_ADMIN_TITLE',"Shop - Admin");
define('SITE_ADMIN_HEADER',"Shop Panel");

// Use the correct variable name for the database connection
$conn = new MySQLi($DBhost, $DBuser, $DBpass, $DBname);

// Check connection
if ($conn->connect_errno) { // Corrected this line
   die("ERROR : -> " . $conn->connect_error); // Corrected this line
}
?>
