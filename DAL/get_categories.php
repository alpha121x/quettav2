<?php
include "db_config.php";  // Include your database connection

header('Content-Type: application/json'); // Set content type to JSON

// Fetch Categories
if ($_GET['type'] == 'categories') {
    try {
        // Fetch distinct modification types (categories)
        $stmt = $pdo->query("SELECT DISTINCT modification_type FROM public.tbl_landuse_f ORDER BY modification_type ASC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output the result as JSON
        echo json_encode($categories);
    } catch (PDOException $e) {
        // Handle any errors that occur
        echo json_encode(["error" => "Error fetching categories: " . $e->getMessage()]);
    }
}
?>
