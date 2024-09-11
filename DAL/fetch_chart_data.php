<?php
// Include your database connection here
include("db_config.php");

// Fetch filters from GET data if available
$zone_code = isset($_GET['zone_code']) && $_GET['zone_code'] !== "Select Zone" ? $_GET['zone_code'] : null;
$sheet_no = isset($_GET['sheet_no']) && $_GET['sheet_no'] !== "Select Block" ? $_GET['sheet_no'] : null;
$land_type = isset($_GET['land_type']) && $_GET['land_type'] !== "Select Land Type" ? $_GET['land_type'] : null;
$land_sub_type = isset($_GET['land_sub_type']) && $_GET['land_sub_type'] !== "Select Land Sub Type" ? $_GET['land_sub_type'] : null;
$modification_type = isset($_GET['modification_type']) && $_GET['modification_type'] !== "Select Modification Type" ? $_GET['modification_type'] : null;

try {
    // Query for fetching modification types count
    $query1 = "SELECT modification_type, COUNT(*) AS count FROM public.tbl_landuse_f WHERE 1=1";
    if ($zone_code) $query1 .= " AND zone_code = :zone_code";
    if ($sheet_no) $query1 .= " AND sheet_no = :sheet_no";
    if ($land_type) $query1 .= " AND land_type = :land_type";
    if ($land_sub_type) $query1 .= " AND land_sub_type = :land_sub_type";
    if ($modification_type) $query1 .= " AND modification_type = :modification_type";
    $query1 .= " GROUP BY modification_type";

    $stmt1 = $pdo->prepare($query1);
    if ($zone_code) $stmt1->bindParam(':zone_code', $zone_code);
    if ($sheet_no) $stmt1->bindParam(':sheet_no', $sheet_no);
    if ($land_type) $stmt1->bindParam(':land_type', $land_type);
    if ($land_sub_type) $stmt1->bindParam(':land_sub_type', $land_sub_type);
    if ($modification_type) $stmt1->bindParam(':modification_type', $modification_type);
    $stmt1->execute();
    $modificationTypes = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Query for fetching parcel count and percentages by zone
    $query2 = "SELECT zone_code, COUNT(*) AS parcel_count FROM public.tbl_landuse_f WHERE 1=1";
    if ($zone_code) $query2 .= " AND zone_code = :zone_code";
    if ($sheet_no) $query2 .= " AND sheet_no = :sheet_no";
    if ($land_type) $query2 .= " AND land_type = :land_type";
    if ($land_sub_type) $query2 .= " AND land_sub_type = :land_sub_type";
    if ($modification_type) $query2 .= " AND modification_type = :modification_type";
    $query2 .= " GROUP BY zone_code";

    $stmt2 = $pdo->prepare($query2);
    if ($zone_code) $stmt2->bindParam(':zone_code', $zone_code);
    if ($sheet_no) $stmt2->bindParam(':sheet_no', $sheet_no);
    if ($land_type) $stmt2->bindParam(':land_type', $land_type);
    if ($land_sub_type) $stmt2->bindParam(':land_sub_type', $land_sub_type);
    if ($modification_type) $stmt2->bindParam(':modification_type', $modification_type);
    $stmt2->execute();
    $zoneParcelData = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $totalParcels = array_sum(array_column($zoneParcelData, 'parcel_count'));
    $percentages = [];
    $zoneLabels = [];
    foreach ($zoneParcelData as $row) {
        $percentages[] = round(($row['parcel_count'] / $totalParcels) * 100, 2);
        $zoneLabels[] = 'Zone ' . $row['zone_code'];
    }

    // Query for fetching land type counts
    $query3 = "SELECT land_type, COUNT(*) AS land_count FROM public.tbl_landuse_f WHERE 1=1";
    if ($zone_code) $query3 .= " AND zone_code = :zone_code";
    if ($sheet_no) $query3 .= " AND sheet_no = :sheet_no";
    if ($land_type) $query3 .= " AND land_type = :land_type";
    if ($land_sub_type) $query3 .= " AND land_sub_type = :land_sub_type";
    if ($modification_type) $query3 .= " AND modification_type = :modification_type";
    $query3 .= " GROUP BY land_type ORDER BY land_count DESC";

    $stmt3 = $pdo->prepare($query3);
    if ($zone_code) $stmt3->bindParam(':zone_code', $zone_code);
    if ($sheet_no) $stmt3->bindParam(':sheet_no', $sheet_no);
    if ($land_type) $stmt3->bindParam(':land_type', $land_type);
    if ($land_sub_type) $stmt3->bindParam(':land_sub_type', $land_sub_type);
    if ($modification_type) $stmt3->bindParam(':modification_type', $modification_type);
    $stmt3->execute();
    $landTypesData = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    $landTypes = array_column($landTypesData, 'land_type');
    $landCounts = array_map('intval', array_column($landTypesData, 'land_count'));

    // Query for fetching modification counts by zone
    $query4 = "
        SELECT 
            zone_code,
            modification_type,
            COUNT(*) AS modification_count
        FROM public.tbl_landuse_f 
        WHERE 1=1";
    if ($zone_code) $query4 .= " AND zone_code = :zone_code";
    if ($sheet_no) $query4 .= " AND sheet_no = :sheet_no";
    if ($land_type) $query4 .= " AND land_type = :land_type";
    if ($land_sub_type) $query4 .= " AND land_sub_type = :land_sub_type";
    if ($modification_type) $query4 .= " AND modification_type = :modification_type";
    $query4 .= " GROUP BY zone_code, modification_type ORDER BY zone_code, modification_type";

    $stmt4 = $pdo->prepare($query4);
    if ($zone_code) $stmt4->bindParam(':zone_code', $zone_code);
    if ($sheet_no) $stmt4->bindParam(':sheet_no', $sheet_no);
    if ($land_type) $stmt4->bindParam(':land_type', $land_type);
    if ($land_sub_type) $stmt4->bindParam(':land_sub_type', $land_sub_type);
    if ($modification_type) $stmt4->bindParam(':modification_type', $modification_type);
    $stmt4->execute();
    $modificationData = $stmt4->fetchAll(PDO::FETCH_ASSOC);

    $zones = [];
    $modificationCounts = [];

    foreach ($modificationData as $row) {
        $zoneCode = $row['zone_code'];
        $modificationType = ucfirst(strtolower(trim($row['modification_type'])));
        if (!in_array($zoneCode, $zones)) {
            $zones[] = $zoneCode;
            $modificationCounts[$zoneCode] = ['Merge' => 0, 'Same' => 0, 'Split' => 0];
        }
        if (array_key_exists($modificationType, $modificationCounts[$zoneCode])) {
            $modificationCounts[$zoneCode][$modificationType] = (int)$row['modification_count'];
        }
    }

    $mergeCounts = [];
    $sameCounts = [];
    $splitCounts = [];

    foreach ($zones as $zone) {
        $mergeCounts[] = $modificationCounts[$zone]['Merge'] ?? 0;
        $sameCounts[] = $modificationCounts[$zone]['Same'] ?? 0;
        $splitCounts[] = $modificationCounts[$zone]['Split'] ?? 0;
    }

    $array_result = [
        'modificationTypes' => $modificationTypes,
        'parcelPercentages' => $percentages,
        'zoneLabels' => $zoneLabels,
        'landTypes' => $landTypes,
        'landCounts' => $landCounts,
        'zones' => array_map(fn($zone) => 'Zone ' . $zone, $zones),
        'mergeCounts' => $mergeCounts,
        'sameCounts' => $sameCounts,
        'splitCounts' => $splitCounts,
    ];

    echo json_encode($array_result);

} catch (PDOException $e) {
    echo "<script>console.error('Error: " . $e->getMessage() . "');</script>";
}
?>
