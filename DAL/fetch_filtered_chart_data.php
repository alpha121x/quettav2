<?php
// Include your database connection here
include("db_config.php");

try {
    // Fetch filters from POST data if available
    $zone_code = isset($_POST['zone_code']) && $_POST['zone_code'] !== "Select Zone" ? $_POST['zone_code'] : null;
    $sheet_no = isset($_POST['sheet_no']) && $_POST['sheet_no'] !== "Select Block" ? $_POST['sheet_no'] : null;
    $land_type = isset($_POST['land_type']) && $_POST['land_type'] !== "Select Land Type" ? $_POST['land_type'] : null;
    $land_sub_type = isset($_POST['land_sub_type']) && $_POST['land_sub_type'] !== "Select Land Sub Type" ? $_POST['land_sub_type'] : null;
    $modification_type = isset($_POST['modification_type']) && $_POST['modification_type'] !== "Select Modification Type" ? $_POST['modification_type'] : null;

    // Fetch data using helper functions with applied filters
    $modificationTypes = getModificationTypesCount($pdo, $zone_code, $sheet_no, $land_type, $land_sub_type, $modification_type);

    $zoneParcelData = getZoneParcelData($pdo, $zone_code, $sheet_no, $land_type, $land_sub_type, $modification_type);
    $percentages = $zoneParcelData['percentages'];
    $zoneLabels = $zoneParcelData['zoneLabels'];

    $landTypesData = getLandTypesCount($pdo, $zone_code, $sheet_no, $land_type, $land_sub_type, $modification_type);

    $modificationCountsData = getModificationCountsByZone($pdo, $zone_code, $sheet_no, $land_type, $land_sub_type, $modification_type);

    // Return the fetched data as JSON
    echo json_encode([
        'modificationTypes' => $modificationTypes,
        'parcelPercentages' => $percentages,
        'zoneLabels' => $zoneLabels,
        'landTypes' => $landTypesData['landTypes'],
        'landCounts' => $landTypesData['landCounts'],
        'zones' => $modificationCountsData['zones'],
        'mergeCounts' => $modificationCountsData['mergeCounts'],
        'sameCounts' => $modificationCountsData['sameCounts'],
        'splitCounts' => $modificationCountsData['splitCounts']
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
