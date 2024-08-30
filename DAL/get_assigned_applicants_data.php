<?php
require("./DAL/db_config.php");

// Fetch data from assigned_applicants table and join with applicants table
$query = "
                                SELECT a.id, a.applicant_id, ap.applicant_name, m.mozah_name, c.circle_name, t.tehsil_name, d.district_name
                                FROM assigned_applicants a
                                JOIN mozah m ON a.mozah_id = m.mozah_id
                                JOIN circle c ON m.circle_id = c.circle_id
                                JOIN tehsil t ON c.tehsil_id = t.tehsil_id
                                JOIN district d ON t.district_id = d.district_id
                                JOIN applicants ap ON a.applicant_id = ap.applicant_id
                            ";
$stmt = $pdo->prepare($query);
$stmt->execute();
$assigned_applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>