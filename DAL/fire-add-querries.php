<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "db_config.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $district_id = isset($_POST['district_id']) && is_numeric($_POST['district_id']) ? (int)$_POST['district_id'] : null;
    $tehsil_id = isset($_POST['tehsil_id']) && is_numeric($_POST['tehsil_id']) ? (int)$_POST['tehsil_id'] : null;
    $circle_id = isset($_POST['circle_id']) && is_numeric($_POST['circle_id']) ? (int)$_POST['circle_id'] : null;
    $mozah_ids = isset($_POST['mozah_id']) ? $_POST['mozah_id'] : []; // Handle multiple mozah_id values as an array
    $applicant_id = isset($_POST['applicant_id']) && is_numeric($_POST['applicant_id']) ? (int)$_POST['applicant_id'] : null;

    if (!empty($applicant_id)) {
        try {
            // Loop through each selected mozah_id and insert it into the database
            foreach ($mozah_ids as $mozah_id) {
                $mozah_id = (int)$mozah_id; // Ensure it's an integer
                $sql = "INSERT INTO assigned_applicants (district_id, tehsil_id, circle_id, mozah_id, applicant_id) VALUES (:district_id, :tehsil_id, :circle_id, :mozah_id, :applicant_id)";
                $params = [
                    'district_id' => $district_id,
                    'tehsil_id' => $tehsil_id,
                    'circle_id' => $circle_id,
                    'mozah_id' => $mozah_id,
                    'applicant_id' => $applicant_id
                ];

                $stmt = $pdo->prepare($sql);
                $success = $stmt->execute($params);

                if (!$success) {
                    throw new Exception("Failed to assign applicant.");
                }
            }

            require "config.php";

            echo "<script>
                alert('Applicant assigned successfully.');
                window.location.href = '" . BASE_URL . "assigned_applicants.php';
            </script>";


            exit;
        } catch (Exception $e) {
            echo "<script>
            alert('Error: {$e->getMessage()}');
            window.location.href = '" . BASE_URL . "add_applicants.php';
        </script>";

            exit;
        }
    }
}
