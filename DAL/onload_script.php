<?php
include 'db_config.php'; // Include your database connection

header('Content-Type: application/json'); // Set content type to JSON
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$type = $_GET['type'] ?? ''; // Check if 'type' is passed via GET request

switch ($type) {
    case 'zones':
        try {
            // Fetch distinct zone codes
            $stmt = $pdo->query("SELECT DISTINCT zone_code FROM public.tbl_landuse_f ORDER BY zone_code ASC");
            $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($zones);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error fetching zones: " . $e->getMessage()]);
        }
        break;

    case 'land_types':
        try {
            // Fetch distinct land types
            $stmt = $pdo->query("SELECT DISTINCT land_type FROM public.tbl_landuse_f ORDER BY land_type ASC");
            $land_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($land_types);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error fetching land types: " . $e->getMessage()]);
        }
        break;

    case 'categories':
        try {
            // Fetch distinct modification types (categories)
            $stmt = $pdo->query("SELECT DISTINCT modification_type FROM public.tbl_landuse_f ORDER BY modification_type ASC");
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($categories);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error fetching categories: " . $e->getMessage()]);
        }
        break;

    case 'parcel':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['parcel_id'])) {
            try {
                $parcelId = $_POST['parcel_id'];
                // Fetch parcel details by parcel_id
                $sql = "SELECT * FROM public.tbl_landuse_f WHERE parcel_id = :parcel_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['parcel_id' => $parcelId]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($data);
            } catch (PDOException $e) {
                echo json_encode(["error" => "Error fetching parcel: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["error" => "Invalid request"]);
        }
        break;

    case 'land_sub_type':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['land_type'])) {
            $landType = $_POST['land_type'];

            try {
                // Fetch distinct land sub types based on the selected land type
                $stmt = $pdo->prepare("SELECT DISTINCT land_sub_type FROM public.tbl_landuse_f WHERE land_type = :land_type ORDER BY land_sub_type ASC");
                $stmt->bindParam(':land_type', $landType);
                $stmt->execute();
                $landSubTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($landSubTypes)) {
                    echo json_encode($landSubTypes);
                } else {
                    echo json_encode(["error" => "No Land Sub Types Available"]);
                }
            } catch (PDOException $e) {
                echo json_encode(["error" => "Error fetching land sub types: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["error" => "Invalid Request"]);
        }
        break;

    case 'block':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['zone_code'])) {
            $zoneCode = $_POST['zone_code'];

            try {
                // Fetch blocks (sheet_no) based on the selected zone
                $stmt = $pdo->prepare("SELECT DISTINCT sheet_no FROM public.tbl_landuse_f WHERE zone_code = :zoneCode ORDER BY sheet_no ASC");
                $stmt->execute(['zoneCode' => $zoneCode]);
                $blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($blocks)) {
                    echo json_encode($blocks);
                } else {
                    echo json_encode(["error" => "No Blocks Available"]);
                }
            } catch (PDOException $e) {
                echo json_encode(["error" => "Error fetching blocks: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["error" => "Invalid Request"]);
        }
        break;

    case 'chart_data':
        try {
            // Fetch data for modification types
            $modificationTypesStmt = $pdo->query("SELECT modification_type, COUNT(*) AS count FROM public.tbl_landuse_f GROUP BY modification_type");
            $modificationTypes = $modificationTypesStmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch data for parcel distribution
            $parcelDistributionStmt = $pdo->query("SELECT zone_code, COUNT(*) AS parcel_count FROM public.tbl_landuse_f GROUP BY zone_code");
            $parcelDistribution = $parcelDistributionStmt->fetchAll(PDO::FETCH_ASSOC);
            $totalParcels = array_sum(array_column($parcelDistribution, 'parcel_count'));
            $parcelPercentages = array_map(fn($zone) => round(($zone['parcel_count'] / $totalParcels) * 100, 2), $parcelDistribution);
            $zoneLabels = array_map(fn($zone) => 'Zone ' . $zone['zone_code'], $parcelDistribution);

            // Fetch data for land types
            $landTypesStmt = $pdo->query("SELECT land_type, COUNT(*) AS land_count FROM public.tbl_landuse_f GROUP BY land_type ORDER BY land_count DESC");
            $landTypes = $landTypesStmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch modification counts grouped by zone_code and modification_type
            $modificationCountsStmt = $pdo->query("
                        SELECT zone_code, modification_type, COUNT(*) AS modification_count
                        FROM public.tbl_landuse_f
                        GROUP BY zone_code, modification_type
                        ORDER BY zone_code, modification_type");
            $modificationCounts = $modificationCountsStmt->fetchAll(PDO::FETCH_ASSOC);

            // Initialize arrays for storing modification counts
            $zones = [];
            $mergeCounts = [];
            $sameCounts = [];
            $splitCounts = [];

            // Process modification counts
            foreach ($modificationCounts as $row) {
                $zoneCode = $row['zone_code'];
                $modificationType = ucfirst(strtolower(trim($row['modification_type'])));
                if (!isset($mergeCounts[$zoneCode])) {
                    $zones[] = $zoneCode;
                    $mergeCounts[$zoneCode] = 0;
                    $sameCounts[$zoneCode] = 0;
                    $splitCounts[$zoneCode] = 0;
                }
                switch ($modificationType) {
                    case 'Merge':
                        $mergeCounts[$zoneCode] += (int)$row['modification_count'];
                        break;
                    case 'Same':
                        $sameCounts[$zoneCode] += (int)$row['modification_count'];
                        break;
                    case 'Split':
                        $splitCounts[$zoneCode] += (int)$row['modification_count'];
                        break;
                }
            }

            // New: Fetch block data (distinct sheet_no per zone)
            $blockStmt = $pdo->query("SELECT zone_code, COUNT(DISTINCT sheet_no) AS block_count 
                                          FROM public.tbl_landuse_f 
                                          GROUP BY zone_code");
            $blockData = $blockStmt->fetchAll(PDO::FETCH_ASSOC);

            // Calculate total blocks and prepare block data
            $total_blocks = array_sum(array_column($blockData, 'block_count'));
            $blockPercentages = array_map(fn($zone) => round(($zone['block_count'] / $total_blocks) * 100, 2), $blockData);
            $blockLabels = array_map(fn($zone) => 'Zone ' . $zone['zone_code'], $blockData);

            // Prepare the response
            $response = [
                'modificationTypes' => $modificationTypes,
                'parcelPercentages' => $parcelPercentages,
                'zoneLabels' => $zoneLabels,
                'landCounts' => array_column($landTypes, 'land_count'),
                'landTypes' => array_column($landTypes, 'land_type'),
                'mergeCounts' => array_values($mergeCounts),
                'sameCounts' => array_values($sameCounts),
                'splitCounts' => array_values($splitCounts),
                'zones' => $zones,
                'blockPercentages' => $blockPercentages, // New: Block data
                'blockLabels' => $blockLabels  // New: Block labels
            ];

            echo json_encode($response);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error fetching chart data: " . $e->getMessage()]);
        }
        break;


    default:
        echo json_encode(["error" => "Invalid type"]);
        break;
}
