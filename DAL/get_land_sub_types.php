<?php
require "db_config.php";

header('Content-Type: application/json');

$land_type = isset($_POST['land_type']) ? $_POST['land_type'] : null;

try {
    if ($land_type) {
        $stmt = $pdo->prepare("SELECT DISTINCT land_sub_type FROM public.tbl_landuse_f WHERE land_type = :land_type ORDER BY land_sub_type ASC");
        $stmt->bindParam(':land_type', $land_type);
        $stmt->execute();
        $landSubTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($landSubTypes);
    } else {
        echo json_encode([]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
