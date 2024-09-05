<?php
require 'db_config.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Retrieve filter parameters and pagination info
$zone_code = $_POST['zone_code'] ?? '';
$block = $_POST['block'] ?? '';
$category = $_POST['category'] ?? '';
$searchValue = $_POST['search']['value'] ?? '';  // Capture the search input from DataTables
$start = (int)($_POST['start'] ?? 0);   // Pagination start
$length = (int)($_POST['length'] ?? 10); // Number of records per page

// Base query
$query = "SELECT parcel_id, zone_code, land_type, land_sub_type, modification_type, building_height, building_condition 
          FROM public.tbl_landuse_f WHERE 1=1";

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
if ($searchValue) {
    $filters[] = "parcel_id = :searchValue"; // Exact match on parcel_id
}
if ($filters) {
    $query .= " AND " . implode(" AND ", $filters);
}

// Add pagination
$query .= " ORDER BY parcel_id LIMIT :length OFFSET :start";

// Prepare and execute the query
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
if ($searchValue) {
    $stmt->bindValue(':searchValue', $searchValue); // Bind the exact search value without wildcards
}
$stmt->bindValue(':length', $length, PDO::PARAM_INT);
$stmt->bindValue(':start', $start, PDO::PARAM_INT);

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// After fetching the data, check if it matches the search term
foreach ($data as &$row) {
    if ($searchValue && $row['parcel_id'] == $searchValue) {
        $row['highlight'] = true; // Mark row for highlighting
    } else {
        $row['highlight'] = false; // Default
    }
}

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
if ($searchValue) {
    $filteredRecordsStmt->bindValue(':searchValue', $searchValue);
}
$filteredRecordsStmt->execute();
$recordsFiltered = $filteredRecordsStmt->fetchColumn();

// Prepare and output response
$response = [
    "draw" => intval($_POST['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($recordsFiltered),
    "data" => $data
];

echo json_encode($response);
exit;
