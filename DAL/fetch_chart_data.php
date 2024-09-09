<?php
// Include your database connection here
include("db_config.php");

try {
    $stmt = $pdo->prepare("SELECT modification_type, COUNT(*) AS count FROM public.tbl_landuse_f GROUP BY modification_type");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data in JSON format
    echo json_encode($data);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
