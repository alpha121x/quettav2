<?php

require 'db_config.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$parcelId = $_POST['parcel_id'];

// Prepare and execute the query
$sql = "SELECT *
        FROM public.tbl_landuse_f 
        WHERE parcel_id = :parcel_id"; // Corrected SQL query

$stmt = $pdo->prepare($sql);
$stmt->execute(['parcel_id' => $parcelId]);

$data = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($data);
exit
?>
