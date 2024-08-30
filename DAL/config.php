<?php
// Define the protocol (HTTP or HTTPS)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Define the base URL
define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . '/task_folder/');
?>
