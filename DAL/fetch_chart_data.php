<?php
// Include your database connection here
include("db_config.php");

try {
    // Query for fetching modification types count
    $query1 = "SELECT modification_type, COUNT(*) AS count FROM public.tbl_landuse_f GROUP BY modification_type";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute();
    $modificationTypes = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Query for fetching parcel count and percentages by zone
    $query2 = "SELECT zone_code, COUNT(*) AS parcel_count FROM public.tbl_landuse_f GROUP BY zone_code";
    $stmt2 = $pdo->prepare($query2);
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
    $query3 = "SELECT land_type, COUNT(*) AS land_count FROM public.tbl_landuse_f GROUP BY land_type ORDER BY land_count DESC";
    $stmt3 = $pdo->prepare($query3);
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
        GROUP BY zone_code, modification_type 
        ORDER BY zone_code, modification_type";

    $stmt4 = $pdo->prepare($query4);
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
