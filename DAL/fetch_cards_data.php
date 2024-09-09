<?php
require "db_config.php";

// Initialize an array to hold the counts
$response = [];

// Fetch filters from POST data if available
$zone_code = isset($_POST['zone_code']) && $_POST['zone_code'] !== "Select Zone" ? $_POST['zone_code'] : null;
$sheet_no = isset($_POST['sheet_no']) && $_POST['sheet_no'] !== "Select Block" ? $_POST['sheet_no'] : null;
$land_type = isset($_POST['land_type']) && $_POST['land_type'] !== "Select Land Type" ? $_POST['land_type'] : null;
$land_sub_type = isset($_POST['land_sub_type']) && $_POST['land_sub_type'] !== "Select Land Sub Type" ? $_POST['land_sub_type'] : null;
$modification_type = isset($_POST['modification_type']) && $_POST['modification_type'] !== "Select Modification Type" ? $_POST['modification_type'] : null;

try {
    // Function to fetch distinct count with optional filters
    function getDistinctCount($pdo, $column, $table, $zone_code = null, $sheet_no = null, $land_type = null, $land_sub_type = null) {
        $query = "SELECT COUNT(DISTINCT $column) FROM $table WHERE 1=1";
        
        // Apply filters if available
        if ($zone_code) {
            $query .= " AND zone_code = :zone_code";
        }
        if ($sheet_no) {
            $query .= " AND sheet_no = :sheet_no";
        }
        if ($land_type) {
            $query .= " AND land_type = :land_type";
        }
        if ($land_sub_type) {
            $query .= " AND land_sub_type = :land_sub_type";
        }

        $stmt = $pdo->prepare($query);

        // Bind parameters
        if ($zone_code) {
            $stmt->bindParam(':zone_code', $zone_code);
        }
        if ($sheet_no) {
            $stmt->bindParam(':sheet_no', $sheet_no);
        }
        if ($land_type) {
            $stmt->bindParam(':land_type', $land_type);
        }
        if ($land_sub_type) {
            $stmt->bindParam(':land_sub_type', $land_sub_type);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Function to fetch simple count with optional filters
    function getCount($pdo, $column, $table, $modification_type = null, $zone_code = null, $sheet_no = null, $land_type = null, $land_sub_type = null) {
        $query = "SELECT COUNT($column) FROM $table WHERE 1=1";
        
        // Apply filters if available
        if ($modification_type) {
            $query .= " AND modification_type = :modification_type";
        }
        if ($zone_code) {
            $query .= " AND zone_code = :zone_code";
        }
        if ($sheet_no) {
            $query .= " AND sheet_no = :sheet_no";
        }
        if ($land_type) {
            $query .= " AND land_type = :land_type";
        }
        if ($land_sub_type) {
            $query .= " AND land_sub_type = :land_sub_type";
        }

        $stmt = $pdo->prepare($query);

        // Bind parameters
        if ($modification_type) {
            $stmt->bindParam(':modification_type', $modification_type);
        }
        if ($zone_code) {
            $stmt->bindParam(':zone_code', $zone_code);
        }
        if ($sheet_no) {
            $stmt->bindParam(':sheet_no', $sheet_no);
        }
        if ($land_type) {
            $stmt->bindParam(':land_type', $land_type);
        }
        if ($land_sub_type) {
            $stmt->bindParam(':land_sub_type', $land_sub_type);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Total Zones
    $response['totalZones'] = getDistinctCount($pdo, "zone_code", "tbl_landuse_f", $zone_code, $sheet_no, $land_type, $land_sub_type);

    // Total Blocks
    $response['totalBlocks'] = getDistinctCount($pdo, "sheet_no", "tbl_landuse_f", $zone_code, $sheet_no, $land_type, $land_sub_type);

    // Total Parcels
    $response['totalParcels'] = getDistinctCount($pdo, "parcel_id", "tbl_landuse_f", $zone_code, $sheet_no, $land_type, $land_sub_type);

    // Merge Parcels
    $response['mergeParcels'] = getCount($pdo, "*", "tbl_landuse_f", "MERGE", $zone_code, $sheet_no, $land_type, $land_sub_type);

    // Same Parcels
    $response['sameParcels'] = getCount($pdo, "*", "tbl_landuse_f", "SAME", $zone_code, $sheet_no, $land_type, $land_sub_type);

    // Split Parcels
    $response['splitParcels'] = getCount($pdo, "*", "tbl_landuse_f", "SPLIT", $zone_code, $sheet_no, $land_type, $land_sub_type);

    // Return the response as JSON
    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
