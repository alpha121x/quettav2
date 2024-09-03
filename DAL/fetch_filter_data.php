<?php
require 'db_config.php';

header('Content-Type: application/json');

// Retrieve filter parameters
$zone_code = $_GET['zone_code'] ?? '';
$block = $_GET['block'] ?? '';
$category = $_GET['category'] ?? '';

// Construct the SQL query based on filters
$query = "SELECT * FROM public.tbl_landuse_f WHERE 1=1";

if (!empty($zone_code)) {
    $query .= " AND zone_code = :zone_code";
}
if (!empty($block)) {
    $query .= " AND sheet_no = :block";
}
if (!empty($category)) {
    $query .= " AND modification_type = :category";
}

$stmt = $pdo->prepare($query);

// Bind parameters
if (!empty($zone_code)) {
    $stmt->bindParam(':zone_code', $zone_code);
}
if (!empty($block)) {
    $stmt->bindParam(':block', $block);
}
if (!empty($category)) {
    $stmt->bindParam(':category', $category);
}

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the response in the format expected by DataTables
$response = [
    "recordsTotal" => count($data),
    "recordsFiltered" => count($data),
    "data" => $data
];

echo json_encode($response);
?>
