<?php
require "db_config.php";

// Get the request parameters from DataTables
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = $_POST['search']['value']; // Search value

// Fetch total records count without filtering
$stmt = $pdo->query("SELECT COUNT(*) FROM public.tbl_landuse_f");
$totalRecords = $stmt->fetchColumn();

// Prepare the SQL query with search filtering
$query = "SELECT parcel_id, zone_code, land_type, land_sub_type, modification_type, building_height, building_condition 
          FROM public.tbl_landuse_f";

// Add search condition if needed
if (!empty($searchValue)) {
    $query .= " WHERE zone_code LIKE :search OR land_type LIKE :search";
}

$query .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($query);

// Bind search parameter
if (!empty($searchValue)) {
    $stmt->bindValue(':search', '%' . $searchValue . '%', PDO::PARAM_STR);
}

// Bind limit and offset parameters
$stmt->bindValue(':limit', (int)$length, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$start, PDO::PARAM_INT);

// Execute the query
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch the total number of records with filtering
$recordsFiltered = $totalRecords;
if (!empty($searchValue)) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM public.tbl_landuse_f WHERE zone_code LIKE :search OR land_type LIKE :search");
    $stmt->bindValue(':search', '%' . $searchValue . '%', PDO::PARAM_STR);
    $stmt->execute();
    $recordsFiltered = $stmt->fetchColumn();
}

// Prepare the JSON response
$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($recordsFiltered),
    "data" => $data
];

// Send the JSON response
echo json_encode($response);
?>
