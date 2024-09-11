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
        // Ensure it's a POST request and has a 'parcel_id' parameter
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
        // Ensure it's a POST request with 'land_type' parameter
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
        // Ensure it's a POST request with 'zone_code' parameter
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

    default:
        echo json_encode(["error" => "Invalid type"]);
        break;
}
?>
