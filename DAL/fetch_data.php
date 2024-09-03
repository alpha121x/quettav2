<?php
// Include the database connection configuration
require_once 'db_config.php';

$zoneCode = isset($_POST['zone_code']) ? $_POST['zone_code'] : '';
$block = isset($_POST['block']) ? $_POST['block'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';
$filter1 = isset($_POST['filter1']) ? $_POST['filter1'] : '';

try {
    // Create a base SQL query
    $sql = "SELECT parcel_id, zone_code, land_type, land_sub_type,modification_type, building_height, building_condition FROM public.tbl_landuse_f WHERE 1=1";

    // Append conditions based on the filters
    if (!empty($zoneCode)) {
        $sql .= " AND zone_code = :zoneCode";
    }
    if (!empty($block)) {
        $sql .= " AND sheet_no = :block";
    }
    if (!empty($category)) {
        $sql .= " AND modification_type = :category";
    }
    if (!empty($filter1)) {
        $sql .= " AND (parcel_id::text ILIKE :filter1 OR land_type ILIKE :filter1)";
    }

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    if (!empty($zoneCode)) {
        $stmt->bindParam(':zoneCode', $zoneCode, PDO::PARAM_STR);
    }
    if (!empty($block)) {
        $stmt->bindParam(':block', $block, PDO::PARAM_STR);
    }
    if (!empty($category)) {
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    }
    if (!empty($filter1)) {
        $searchTerm = '%' . $filter1 . '%';
        $stmt->bindParam(':filter1', $searchTerm, PDO::PARAM_STR);
    }

    $stmt->execute();

    // Fetch results and generate table rows
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($rows)) {
        foreach ($rows as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['parcel_id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['zone_code']) . '</td>';
            echo '<td>' . htmlspecialchars($row['land_type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['land_sub_type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['building_height']) . '</td>';
            echo '<td>' . htmlspecialchars($row['modification_type']) . '</td>';
            echo '<td>' . htmlspecialchars($row['building_condition']) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6">No data found</td></tr>';
    }

} catch (PDOException $e) {
    echo '<tr><td colspan="6">Error: ' . $e->getMessage() . '</td></tr>';
}
?>
