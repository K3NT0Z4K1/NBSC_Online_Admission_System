<?php
include_once("../../functions/functions.php");

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No ID']);
    exit;
}

$id = intval($_GET['id']);

$stmt = $mycon->prepare("
    SELECT 
        ap.id AS application_id,
        ap.submitted_at,
        ap.application_status,
        ap.exam_date,
        ap.exam_site,
        
        a.firstname,
        a.lastname,
        a.middlename,
        a.suffix,
        a.gender AS gender_select,
        a.gender_other,
        a.dob,
        a.place_of_birth,
        a.nationality,
        a.email,
        a.contact,
        a.address,
        
        ag.parent_name,
        ag.parent_contact,
        
        ast.high_school,
        ast.year_graduated,
        ast.status_applicant AS status_applicant_select,
        ast.status_applicant_other,
        
        c.code AS course_code,
        c.name AS course,
        
        er.score AS exam_score,
        er.exam_taken_at

    FROM tbl_applications ap
    INNER JOIN tbl_applicants a ON ap.applicant_id = a.id
    LEFT JOIN tbl_applicant_guardians ag ON ag.applicant_id = a.id
    LEFT JOIN tbl_applicant_status ast ON ast.applicant_id = a.id
    LEFT JOIN tbl_courses c ON ap.course_id = c.id
    LEFT JOIN tbl_exam_results er ON ap.id = er.application_id

    WHERE ap.id = ?
    LIMIT 1
");

if (!$stmt) {
    echo json_encode(['error' => 'Prepare failed: ' . $mycon->error]);
    exit;
}

$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['error' => 'Not found']);
    $stmt->close();
    exit;
}

$data = $result->fetch_assoc();
echo json_encode($data);

$stmt->close();
$mycon->close();
