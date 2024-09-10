<?php
include 'db_config.php'; // Include your database connection

header('Content-Type: application/json'); // Set content type to JSON

if ($_GET['type'] == 'zones') {
    try {
        // Fetch distinct zone codes from the table
        $stmt = $pdo->query("SELECT DISTINCT zone_code FROM public.tbl_landuse_f ORDER BY zone_code ASC");
        $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output the result as JSON
        echo json_encode($zones);
    } catch (PDOException $e) {
        // Handle any errors that occur
        echo json_encode(["error" => "Error fetching zones: " . $e->getMessage()]);
    }
}
?>
