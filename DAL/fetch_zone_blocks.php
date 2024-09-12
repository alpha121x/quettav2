<?php
// Include database connection
include 'db_config.php'; // Adjust the path as needed

try {
    // Prepare SQL query to count distinct sheet_no per zone_code
    $query = "SELECT zone_code, COUNT(DISTINCT sheet_no) AS block_count 
              FROM public.tbl_landuse_f 
              GROUP BY zone_code";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch data
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare data for the chart
    $data = [
        'labels' => [],
        'series' => []
    ];

    // Calculate total blocks
    $total_blocks = 0;
    foreach ($result as $row) {
        $total_blocks += $row['block_count'];
    }

    // Populate labels and series with percentages
    foreach ($result as $row) {
        $data['labels'][] = 'Zone ' . $row['zone_code'];
        $percentage = ($row['block_count'] / $total_blocks) * 100;
        $data['series'][] = round($percentage, 2); // Round to 2 decimal places
    }

    // Return data as JSON
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
