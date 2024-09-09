<?php
require "db_config.php";

// Initialize an array to hold the counts
$response = [];

try {
    // Total Zones
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT zone_code) AS total_zones FROM tbl_landuse_f");
    $stmt->execute();
    $response['totalZones'] = $stmt->fetchColumn();

    // Total Blocks
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT sheet_no) AS total_blocks FROM tbl_landuse_f");
    $stmt->execute();
    $response['totalBlocks'] = $stmt->fetchColumn();

    // Total Parcels
    $stmt = $pdo->prepare("SELECT COUNT(parcel_id) AS total_parcels FROM tbl_landuse_f");
    $stmt->execute();
    $response['totalParcels'] = $stmt->fetchColumn();

    // Merge Parcels
    $stmt = $pdo->prepare("SELECT COUNT(*) AS merge_parcels FROM public.tbl_landuse_f WHERE modification_type = 'MERGE'");
    $stmt->execute();
    $response['mergeParcels'] = $stmt->fetchColumn();

    // Same Parcels
    $stmt = $pdo->prepare("SELECT COUNT(*) AS same_parcels FROM public.tbl_landuse_f WHERE modification_type = 'SAME'");
    $stmt->execute();
    $response['sameParcels'] = $stmt->fetchColumn();

    // Split Parcels
    $stmt = $pdo->prepare("SELECT COUNT(*) AS split_parcels FROM public.tbl_landuse_f WHERE modification_type = 'SPLIT'");
    $stmt->execute();
    $response['splitParcels'] = $stmt->fetchColumn();

    // Return the response as JSON
    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
