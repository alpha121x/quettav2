<?php
// Include your database connection here
include("db_config.php");

try {
  // Query 1: Count of each modification type
  $stmt = $pdo->prepare("SELECT modification_type, COUNT(*) AS count FROM public.tbl_landuse_f GROUP BY modification_type");
  $stmt->execute();
  $modificationTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Query 2: Count of parcels in each zone
  $stmt2 = $pdo->prepare("
            SELECT 
                zone_code, 
                COUNT(*) AS parcel_count 
            FROM public.tbl_landuse_f 
            GROUP BY zone_code
        ");
  $stmt2->execute();
  $zoneData = $stmt2->fetchAll(PDO::FETCH_ASSOC);

  $totalParcels = array_sum(array_column($zoneData, 'parcel_count'));
  $percentages = [];
  $zoneLabels = [];
  foreach ($zoneData as $row) {
    $percentages[] = round(($row['parcel_count'] / $totalParcels) * 100, 2);
    $zoneLabels[] = 'Zone ' . $row['zone_code']; // Adding 'Zone ' prefix
  }

  // Query 3: Count of each land type
  $stmt3 = $pdo->prepare("
            SELECT 
                land_type,
                COUNT(*) AS land_count
            FROM public.tbl_landuse_f 
            GROUP BY land_type
            ORDER BY land_count DESC
        ");
  $stmt3->execute();
  $landTypesData = $stmt3->fetchAll(PDO::FETCH_ASSOC);

  $landTypes = [];
  $landCounts = [];
  foreach ($landTypesData as $row) {
    $landTypes[] = $row['land_type'];
    $landCounts[] = (int)$row['land_count'];
  }

  // Query 4: Count of modified parcels by zone and modification type
  $stmt4 = $pdo->prepare("
            SELECT 
                zone_code,
                modification_type,
                COUNT(*) AS modification_count
            FROM public.tbl_landuse_f 
            GROUP BY zone_code, modification_type
            ORDER BY zone_code, modification_type
        ");
  $stmt4->execute();
  $modificationData = $stmt4->fetchAll(PDO::FETCH_ASSOC);

  $zones = [];
  $modificationCounts = []; // To store counts per zone

  // Initialize modification types for each zone
  foreach ($modificationData as $row) {
    $zoneCode = $row['zone_code'];
    $modificationType = trim($row['modification_type']); // Trim whitespace
    $modificationType = ucfirst(strtolower($modificationType)); // Standardize case

    if (!in_array($zoneCode, $zones)) {
      $zones[] = $zoneCode; // Store unique zone codes
      $modificationCounts[$zoneCode] = [
        'Merge' => 0,
        'Same' => 0,
        'Split' => 0
      ];
    }

    if (array_key_exists($modificationType, $modificationCounts[$zoneCode])) {
      $modificationCounts[$zoneCode][$modificationType] = (int)$row['modification_count'];
    }
  }

  // Now extract the counts for each modification type into separate arrays
  $mergeCounts = [];
  $sameCounts = [];
  $splitCounts = [];

  foreach ($zones as $zone) {
    $mergeCounts[] = isset($modificationCounts[$zone]['Merge']) ? $modificationCounts[$zone]['Merge'] : 0;
    $sameCounts[] = isset($modificationCounts[$zone]['Same']) ? $modificationCounts[$zone]['Same'] : 0;
    $splitCounts[] = isset($modificationCounts[$zone]['Split']) ? $modificationCounts[$zone]['Split'] : 0;
  }

  // Pass data to JavaScript
  echo "<script>
            const modificationTypes = " . json_encode($modificationTypes) . ";
            const parcelPercentages = " . json_encode($percentages) . ";
            const zoneLabels = " . json_encode($zoneLabels) . ";
            const landTypes = " . json_encode($landTypes) . ";
            const landCounts = " . json_encode($landCounts) . ";
            const zones = " . json_encode(array_map(fn($zone) => 'Zone ' . $zone, $zones)) . ";
            const mergeCounts = " . json_encode($mergeCounts) . ";
            const sameCounts = " . json_encode($sameCounts) . ";
            const splitCounts = " . json_encode($splitCounts) . ";
        </script>";
} catch (PDOException $e) {
  echo "<script>console.error('Error: " . $e->getMessage() . "');</script>";
}
?>