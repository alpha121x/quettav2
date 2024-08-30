<?php

// Handle AJAX requests
if (isset($_POST['action'])) {
    $response = '';

    switch ($_POST['action']) {
        case 'get_tehsils':
            $district_id = isset($_POST['district_id']) ? (int)$_POST['district_id'] : 0;
            if ($district_id > 0) {
                $tehsils = getTehsils($pdo, $district_id);
                $response .= '<option value="">Select Tehsil</option>';
                foreach ($tehsils as $row) {
                    $response .= '<option value="' . htmlspecialchars($row['tehsil_id']) . '">' . htmlspecialchars($row['tehsil_name']) . '</option>';
                }
            }
            break;

        case 'get_circles':
            $tehsil_id = isset($_POST['tehsil_id']) ? (int)$_POST['tehsil_id'] : 0;
            if ($tehsil_id > 0) {
                $circles = getCircles($pdo, $tehsil_id);
                $response .= '<option value="">Select Circle</option>';
                foreach ($circles as $row) {
                    $response .= '<option value="' . htmlspecialchars($row['circle_id']) . '">' . htmlspecialchars($row['circle_name']) . '</option>';
                }
            }
            break;

        case 'get_mozahs':
            $circle_id = isset($_POST['circle_id']) ? (int)$_POST['circle_id'] : 0;
            if ($circle_id > 0) {
                $mozahs = getMozahs($pdo, $circle_id);
                $response .= '<option value="">Select Mozah</option>';
                foreach ($mozahs as $row) {
                    $response .= '<option value="' . htmlspecialchars($row['mozah_id']) . '">' . htmlspecialchars($row['mozah_name']) . '</option>';
                }
            }
            break;

        case 'get_applicants':
            $applicants = getApplicants($pdo);
            $response .= '<option value="">Select Applicant</option>';
            foreach ($applicants as $row) {
                $response .= '<option value="' . htmlspecialchars($row['applicant_id']) . '">' . htmlspecialchars($row['applicant_name']) . '</option>';
            }
            break;
    }

    echo $response;
    exit;
}

?>