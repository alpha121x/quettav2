<?php
// Include the database connection configuration
require_once 'db_config.php';

if (isset($_POST['zone_code'])) {
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
}
?>


