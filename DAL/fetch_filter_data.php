<?php
require 'db_config.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Retrieve filter parameters
$zone_code = $_GET['zone_code'] ?? '';
$block = $_GET['block'] ?? '';
$category = $_GET['category'] ?? '';
$start = $_GET['start'] ?? 0;   // Pagination start
$length = $_GET['length'] ?? 10; // Number of records per page

// Construct the SQL query based on filters
$query = "SELECT * FROM public.tbl_landuse_f WHERE 1=1";

// Validate and bind parameters using bindValue, ignoring invalid selections like 'Select Block'
if (!empty($zone_code) && $zone_code !== 'Select Zone') {
    $query .= " AND zone_code = :zone_code";
}
if (!empty($block) && $block !== 'Select Block') {
    $query .= " AND sheet_no = :block";
}
if (!empty($category) && $category !== 'Select Category') {
    $query .= " AND modification_type = :category";
}

// Add pagination with LIMIT and OFFSET for PostgreSQL
$query .= " LIMIT :length OFFSET :start";

$stmt = $pdo->prepare($query);

// Bind parameters conditionally based on selection
if (!empty($zone_code) && $zone_code !== 'Select Zone') {
    $stmt->bindValue(':zone_code', $zone_code);
}
if (!empty($block) && $block !== 'Select Block') {
    $stmt->bindValue(':block', $block);
}
if (!empty($category) && $category !== 'Select Category') {
    $stmt->bindValue(':category', $category);
}

// Bind pagination parameters
$stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
$stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of records without filters
$totalRecordsQuery = "SELECT COUNT(*) FROM public.tbl_landuse_f";
$totalRecordsStmt = $pdo->query($totalRecordsQuery);
$totalRecords = $totalRecordsStmt->fetchColumn();

// Get the number of filtered records
$filteredRecordsQuery = "SELECT COUNT(*) FROM public.tbl_landuse_f WHERE 1=1";
if (!empty($zone_code) && $zone_code !== 'Select Zone') {
    $filteredRecordsQuery .= " AND zone_code = :zone_code";
}
if (!empty($block) && $block !== 'Select Block') {
    $filteredRecordsQuery .= " AND sheet_no = :block";
}
if (!empty($category) && $category !== 'Select Category') {
    $filteredRecordsQuery .= " AND modification_type = :category";
}

$filteredRecordsStmt = $pdo->prepare($filteredRecordsQuery);

// Bind the same parameters for filtered records count conditionally
if (!empty($zone_code) && $zone_code !== 'Select Zone') {
    $filteredRecordsStmt->bindValue(':zone_code', $zone_code);
}
if (!empty($block) && $block !== 'Select Block') {
    $filteredRecordsStmt->bindValue(':block', $block);
}
if (!empty($category) && $category !== 'Select Category') {
    $filteredRecordsStmt->bindValue(':category', $category);
}

$filteredRecordsStmt->execute();
$recordsFiltered = $filteredRecordsStmt->fetchColumn();

// Prepare the response in the format expected by DataTables
$response = [
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
];

echo json_encode($response);
exit;
?>
