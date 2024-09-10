<?php
include "db_config.php";  // Include your database connection

header('Content-Type: application/json'); // Set content type to JSON

// Fetch Land Types
if ($_GET['type'] == 'land_types') {
    try {
        // Fetch distinct land types from the table
        $stmt = $pdo->query("SELECT DISTINCT land_type FROM public.tbl_landuse_f ORDER BY land_type ASC");
        $land_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output the result as JSON
        echo json_encode($land_types);
    } catch (PDOException $e) {
        // Handle any errors that occur
        echo json_encode(["error" => "Error fetching land types: " . $e->getMessage()]);
    }
}
?>
