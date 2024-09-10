<?php
// Include your database connection here
include("db_config.php");

// Fetch filters from POST data if available
$zone_code = isset($_POST['zone_code']) && $_POST['zone_code'] !== "Select Zone" ? $_POST['zone_code'] : null;
$sheet_no = isset($_POST['sheet_no']) && $_POST['sheet_no'] !== "Select Block" ? $_POST['sheet_no'] : null;
$land_type = isset($_POST['land_type']) && $_POST['land_type'] !== "Select Land Type" ? $_POST['land_type'] : null;
$land_sub_type = isset($_POST['land_sub_type']) && $_POST['land_sub_type'] !== "Select Land Sub Type" ? $_POST['land_sub_type'] : null;
$modification_type = isset($_POST['modification_type']) && $_POST['modification_type'] !== "Select Modification Type" ? $_POST['modification_type'] : null;



try {
    // Function to fetch modification types count with filters
    function getModificationTypesCount($pdo, $zone_code = null, $sheet_no = null, $land_type = null, $land_sub_type = null, $modification_type = null)
    {
        $query = "SELECT modification_type, COUNT(*) AS count FROM public.tbl_landuse_f WHERE 1=1";

        // Add filters to the query if parameters are set
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
        if ($modification_type) {
            $query .= " AND modification_type = :modification_type";
        }

        $query .= " GROUP BY modification_type";
        $stmt = $pdo->prepare($query);

        // Bind parameters
        if ($zone_code) $stmt->bindParam(':zone_code', $zone_code);
        if ($sheet_no) $stmt->bindParam(':sheet_no', $sheet_no);
        if ($land_type) $stmt->bindParam(':land_type', $land_type);
        if ($land_sub_type) $stmt->bindParam(':land_sub_type', $land_sub_type);
        if ($modification_type) $stmt->bindParam(':modification_type', $modification_type);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to fetch parcel count and percentages by zone with filters
    function getZoneParcelData($pdo, $zone_code = null, $sheet_no = null, $land_type = null, $land_sub_type = null, $modification_type = null)
    {
        $query = "SELECT zone_code, COUNT(*) AS parcel_count FROM public.tbl_landuse_f WHERE 1=1";

        // Add filters to the query if parameters are set
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
        if ($modification_type) {
            $query .= " AND modification_type = :modification_type";
        }

        $query .= " GROUP BY zone_code";
        $stmt = $pdo->prepare($query);

        // Bind parameters
        if ($zone_code) $stmt->bindParam(':zone_code', $zone_code);
        if ($sheet_no) $stmt->bindParam(':sheet_no', $sheet_no);
        if ($land_type) $stmt->bindParam(':land_type', $land_type);
        if ($land_sub_type) $stmt->bindParam(':land_sub_type', $land_sub_type);
        if ($modification_type) $stmt->bindParam(':modification_type', $modification_type);

        $stmt->execute();
        $zoneData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalParcels = array_sum(array_column($zoneData, 'parcel_count'));
        $percentages = [];
        $zoneLabels = [];
        foreach ($zoneData as $row) {
            $percentages[] = round(($row['parcel_count'] / $totalParcels) * 100, 2);
            $zoneLabels[] = 'Zone ' . $row['zone_code'];
        }

        return ['percentages' => $percentages, 'zoneLabels' => $zoneLabels];
    }

    // Function to fetch land type counts with filters
    function getLandTypesCount($pdo, $zone_code = null, $sheet_no = null, $land_type = null, $land_sub_type = null, $modification_type = null)
    {
        $query = "SELECT land_type, COUNT(*) AS land_count FROM public.tbl_landuse_f WHERE 1=1";

        // Add filters to the query if parameters are set
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
        if ($modification_type) {
            $query .= " AND modification_type = :modification_type";
        }

        $query .= " GROUP BY land_type ORDER BY land_count DESC";
        $stmt = $pdo->prepare($query);

        // Bind parameters
        if ($zone_code) $stmt->bindParam(':zone_code', $zone_code);
        if ($sheet_no) $stmt->bindParam(':sheet_no', $sheet_no);
        if ($land_type) $stmt->bindParam(':land_type', $land_type);
        if ($land_sub_type) $stmt->bindParam(':land_sub_type', $land_sub_type);
        if ($modification_type) $stmt->bindParam(':modification_type', $modification_type);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Function to fetch modification counts by zone with filters
    function getModificationCountsByZone($pdo, $zone_code = null, $sheet_no = null, $land_type = null, $land_sub_type = null, $modification_type = null)
    {
        $query = "
            SELECT 
                zone_code,
                modification_type,
                COUNT(*) AS modification_count
            FROM public.tbl_landuse_f 
            WHERE 1=1";

        // Add filters to the query if parameters are set
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
        if ($modification_type) {
            $query .= " AND modification_type = :modification_type";
        }

        $query .= " GROUP BY zone_code, modification_type ORDER BY zone_code, modification_type";
        $stmt = $pdo->prepare($query);

        // Bind parameters
        if ($zone_code) $stmt->bindParam(':zone_code', $zone_code);
        if ($sheet_no) $stmt->bindParam(':sheet_no', $sheet_no);
        if ($land_type) $stmt->bindParam(':land_type', $land_type);
        if ($land_sub_type) $stmt->bindParam(':land_sub_type', $land_sub_type);
        if ($modification_type) $stmt->bindParam(':modification_type', $modification_type);

        $stmt->execute();
        $modificationData = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

        // Extract counts for each modification type into separate arrays
        $mergeCounts = [];
        $sameCounts = [];
        $splitCounts = [];

        foreach ($zones as $zone) {
            $mergeCounts[] = isset($modificationCounts[$zone]['Merge']) ? $modificationCounts[$zone]['Merge'] : 0;
            $sameCounts[] = isset($modificationCounts[$zone]['Same']) ? $modificationCounts[$zone]['Same'] : 0;
            $splitCounts[] = isset($modificationCounts[$zone]['Split']) ? $modificationCounts[$zone]['Split'] : 0;
        }

        return ['zones' => $zones, 'mergeCounts' => $mergeCounts, 'sameCounts' => $sameCounts, 'splitCounts' => $splitCounts];
    }


    // Fetching all data with filters
    $modificationTypes = getModificationTypesCount($pdo, $zone_code, $sheet_no, $land_type, $land_sub_type, $modification_type);

    $zoneParcelData = getZoneParcelData($pdo, $zone_code, $sheet_no, $land_type, $land_sub_type, $modification_type);

    $percentages = $zoneParcelData['percentages'];
    $zoneLabels = $zoneParcelData['zoneLabels'];

    $landTypesData = getLandTypesCount($pdo, $zone_code, $sheet_no, $land_type, $land_sub_type, $modification_type);
    $landTypes = array_column($landTypesData, 'land_type');
    $landCounts = array_map('intval', array_column($landTypesData, 'land_count'));

    $modificationCountsByZone = getModificationCountsByZone($pdo, $zone_code, $sheet_no, $land_type, $land_sub_type, $modification_type);
    $zones = array_map(fn($zone) => 'Zone ' . $zone, $modificationCountsByZone['zones']);
    $mergeCounts = $modificationCountsByZone['mergeCounts'];
    $sameCounts = $modificationCountsByZone['sameCounts'];
    $splitCounts = $modificationCountsByZone['splitCounts'];


    $array_result = array();

    array_push($array_result, $modificationTypes);
    array_push($array_result, $percentages);
    array_push($array_result, $zoneLabels);
    array_push($array_result, $landTypes);
    array_push($array_result, $landCounts);
    array_push($array_result, $zones);
    array_push($array_result, $mergeCounts);
    array_push($array_result, $sameCounts);
    array_push($array_result, $splitCounts);

    echo json_encode($array_result);


    // Pass data to JavaScript
    // echo "<script>
    //         const modificationTypes = " . json_encode($modificationTypes) . ";
    //         const parcelPercentages = " . json_encode($percentages) . ";
    //         const zoneLabels = " . json_encode($zoneLabels) . ";
    //         const landTypes = " . json_encode($landTypes) . ";
    //         const landCounts = " . json_encode($landCounts) . ";
    //         const zones = " . json_encode($zones) . ";
    //         const mergeCounts = " . json_encode($mergeCounts) . ";
    //         const sameCounts = " . json_encode($sameCounts) . ";
    //         const splitCounts = " . json_encode($splitCounts) . ";
    //     </script>";
} catch (PDOException $e) {
    echo "<script>console.error('Error: " . $e->getMessage() . "');</script>";
}
