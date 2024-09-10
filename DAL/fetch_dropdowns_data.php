<?php
// Include the database connection configuration
require_once 'db_config.php';

// Check if 'land_type' is set in the POST request
if (isset($_POST['land_type'])) {
    $landType = $_POST['land_type'];

    try {
        // Create a query to fetch distinct land sub types based on the selected land type
        $stmt = $pdo->prepare("SELECT DISTINCT land_sub_type FROM public.tbl_landuse_f WHERE land_type = :land_type ORDER BY land_sub_type ASC");
        $stmt->bindParam(':land_type', $landType);
        $stmt->execute();

        // Fetch all the land sub types for the selected land type
        $landSubTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($landSubTypes)) {
            foreach ($landSubTypes as $landSubType) {
                echo '<option value="' . htmlspecialchars($landSubType['land_sub_type']) . '">' . htmlspecialchars($landSubType['land_sub_type']) . '</option>';
            }
        } else {
            echo '<option disabled>No Land Sub Types Available</option>';
        }

    } catch (PDOException $e) {
        echo '<option disabled>Error fetching land sub types</option>';
    }
} elseif (isset($_POST['zone_code'])) {
    $zoneCode = $_POST['zone_code'];

    try {
        // Create a query to fetch blocks (sheet_no) based on the selected zone
        $stmt = $pdo->prepare("SELECT DISTINCT sheet_no FROM public.tbl_landuse_f WHERE zone_code = :zoneCode ORDER BY sheet_no ASC");
        $stmt->execute(['zoneCode' => $zoneCode]);

        // Fetch all the blocks (sheet_no) for the selected zone
        $blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($blocks)) {
            foreach ($blocks as $block) {
                echo '<option value="' . htmlspecialchars($block['sheet_no']) . '">' . htmlspecialchars($block['sheet_no']) . '</option>';
            }
        } else {
            echo '<option disabled>No Blocks Available</option>';
        }

    } catch (PDOException $e) {
        echo '<option disabled>Error fetching blocks</option>';
    }
} else {
    echo '<option disabled>Invalid Request</option>';
}
?>
