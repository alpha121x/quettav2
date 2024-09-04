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
$start = (int)($_GET['start'] ?? 0);   // Pagination start
$length = (int)($_GET['length'] ?? 10); // Number of records per page

// Base query
$query = "SELECT * FROM public.tbl_landuse_f WHERE 1=1";

// Add filters to query if they are valid
$filters = [];
if ($zone_code && $zone_code !== 'Select Zone') {
    $filters[] = "zone_code = :zone_code";
}
if ($block && $block !== 'Select Block') {
    $filters[] = "sheet_no = :block";
}
if ($category && $category !== 'Select Category') {
    $filters[] = "modification_type = :category";
}
if ($filters) {
    $query .= " AND " . implode(" AND ", $filters);
}

// Add pagination
$query .= " LIMIT :length OFFSET :start";

$stmt = $pdo->prepare($query);

// Bind parameters
if ($zone_code && $zone_code !== 'Select Zone') {
    $stmt->bindValue(':zone_code', $zone_code);
}
if ($block && $block !== 'Select Block') {
    $stmt->bindValue(':block', $block);
}
if ($category && $category !== 'Select Category') {
    $stmt->bindValue(':category', $category);
}
$stmt->bindValue(':length', $length, PDO::PARAM_INT);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total records count
$totalRecordsStmt = $pdo->query("SELECT COUNT(*) FROM public.tbl_landuse_f");
$totalRecords = $totalRecordsStmt->fetchColumn();

// Get filtered records count
$filteredQuery = "SELECT COUNT(*) FROM public.tbl_landuse_f WHERE 1=1";
if ($filters) {
    $filteredQuery .= " AND " . implode(" AND ", $filters);
}
$filteredRecordsStmt = $pdo->prepare($filteredQuery);
if ($zone_code && $zone_code !== 'Select Zone') {
    $filteredRecordsStmt->bindValue(':zone_code', $zone_code);
}
if ($block && $block !== 'Select Block') {
    $filteredRecordsStmt->bindValue(':block', $block);
}
if ($category && $category !== 'Select Category') {
    $filteredRecordsStmt->bindValue(':category', $category);
}
$filteredRecordsStmt->execute();
$recordsFiltered = $filteredRecordsStmt->fetchColumn();

// Prepare and output response
$response = [
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $recordsFiltered,
    "data" => $data
];

echo json_encode($response);
exit;
?>
