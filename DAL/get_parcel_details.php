<?php

require "./DAL/db_config.php";

try {
  // Query to fetch parcel details (customize columns based on your needs)
  $parcelDetailsQuery = "SELECT parcel_id, zone_code, land_type, land_sub_type, building_height, building_condition FROM tbl_landuse_f";

  // Execute the query
  $stmt = $pdo->prepare($parcelDetailsQuery);
  $stmt->execute();

  // Fetch all rows
  $parcelDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>