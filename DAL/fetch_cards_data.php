<?php
  // Include the database configuration file
  require "./DAL/db_config.php";

  try {
    // Prepare the PDO queries
    $totalZonesQuery = "SELECT COUNT(DISTINCT zone_code) FROM tbl_landuse_f";
    $totalBlocksQuery = "SELECT COUNT(DISTINCT sheet_no) FROM tbl_landuse_f";
    $totalParcelsQuery = "SELECT COUNT(DISTINCT parcel_id) FROM tbl_landuse_f";

    // Execute the queries and fetch the counts using PDO
    $stmtZones = $pdo->prepare($totalZonesQuery);
    $stmtZones->execute();
    $totalZones = $stmtZones->fetchColumn();

    $stmtBlocks = $pdo->prepare($totalBlocksQuery);
    $stmtBlocks->execute();
    $totalBlocks = $stmtBlocks->fetchColumn();

    $stmtParcels = $pdo->prepare($totalParcelsQuery);
    $stmtParcels->execute();
    $totalParcels = $stmtParcels->fetchColumn();
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  ?>

  <?php
  // Include database configuration file
  include("./DAL/db_config.php");

  try {
    // Query to get the total parcels count
    $stmt = $pdo->prepare("SELECT COUNT(parcel_id) AS total_parcels FROM public.tbl_landuse_f");
    $stmt->execute();
    $totalParcelsResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalParcels = $totalParcelsResult['total_parcels'];

    // Query to get the count of merged parcels
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS merge_parcels
        FROM public.tbl_landuse_f
        WHERE modification_type = 'MERGE'
    ");
    $stmt->execute();
    $mergeParcelsResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $mergeParcels = $mergeParcelsResult['merge_parcels'];

    // Query to get the count of same parcels
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS same_parcels
        FROM public.tbl_landuse_f
        WHERE modification_type = 'SAME'
    ");
    $stmt->execute();
    $sameParcelsResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $sameParcels = $sameParcelsResult['same_parcels'];

    // Query to get the count of split parcels
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS split_parcels
        FROM public.tbl_landuse_f
        WHERE modification_type = 'SPLIT'
    ");
    $stmt->execute();
    $splitParcelsResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $splitParcels = $splitParcelsResult['split_parcels'];
  } catch (PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
  }
  ?>