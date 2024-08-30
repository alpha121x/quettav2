<?php 

require "DAL/db_config.php";

// Initialize variables
$districts = [];
$tehsils = [];
$circles = [];
$mozahs = [];
$applicants = [];

// Fetch districts
function getDistricts($pdo)
{
    $stmt = $pdo->query("SELECT * FROM district ORDER BY district_name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch tehsils based on district_id
function getTehsils($pdo, $district_id)
{
    $stmt = $pdo->prepare("SELECT * FROM tehsil WHERE district_id = :district_id ORDER BY tehsil_name");
    $stmt->execute(['district_id' => $district_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch circles based on tehsil_id
function getCircles($pdo, $tehsil_id)
{
    $stmt = $pdo->prepare("SELECT * FROM circle WHERE tehsil_id = :tehsil_id ORDER BY circle_name");
    $stmt->execute(['tehsil_id' => $tehsil_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch mozahs based on circle_id
function getMozahs($pdo, $circle_id)
{
    $stmt = $pdo->prepare("SELECT * FROM mozah WHERE circle_id = :circle_id ORDER BY mozah_name");
    $stmt->execute(['circle_id' => $circle_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch applicants
function getApplicants($pdo)
{
    $stmt = $pdo->query("SELECT * FROM applicants ORDER BY applicant_name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$districts = getDistricts($pdo);
$applicants = getApplicants($pdo);

?>