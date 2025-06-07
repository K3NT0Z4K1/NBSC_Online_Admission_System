<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../../functions/db_connect.php'; // Your DB connection ($mycon)
require '../../components/vendor/PHPMailer-6.10.0/PHPMailer-6.10.0/src/Exception.php';
require '../../components/vendor/PHPMailer-6.10.0/PHPMailer-6.10.0/src/PHPMailer.php';
require '../../components/vendor/PHPMailer-6.10.0/PHPMailer-6.10.0/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $status = $_POST['status'] ?? '';

    if ($id <= 0 || !in_array($status, ['Approved', 'Declined'])) {
        echo "Invalid data";
        exit;
    }

    // Update application_status
    $stmt = $mycon->prepare("UPDATE tbl_applications SET application_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        if ($status === 'Approved') {
            // Fetch applicant info with JOIN to access email and name
            $query = $mycon->prepare("
                SELECT 
                    a.email, 
                    ap.exam_date, 
                    ap.exam_site, 
                    CONCAT(a.firstname, ' ', a.lastname) AS fullname
                FROM tbl_applications ap
                INNER JOIN tbl_applicants a ON ap.applicant_id = a.id
                WHERE ap.id = ?
                LIMIT 1
            ");
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();

            if ($result && $row = $result->fetch_assoc()) {
                $email = $row['email'];
                $examDate = $row['exam_date'];
                $examSite = $row['exam_site'];
                $fullname = $row['fullname'];

                if (!empty($email) && !empty($examDate) && !empty($examSite)) {
                    $mail = new PHPMailer(true);

                    try {
                        // SMTP Configuration
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'kentryanpagongpong@gmail.com';
                        $mail->Password = 'wkzjqmcsebmjoxhh';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Email Setup
                        $mail->setFrom('kentryanpagongpong@gmail.com', 'NBSC Online Admission');
                        $mail->addAddress($email, $fullname);
                        $mail->isHTML(true);
                        $mail->Subject = 'Your NBSC Admission Application Has Been Approved';
                        $mail->Body = "
                            <p>Dear $fullname,</p>
                            <p>Congratulations! Your admission application has been <strong>approved</strong>.</p>
                            <p>Your scheduled exam date is: <strong>" . date("F d, Y h:i A", strtotime($examDate)) . "</strong>.</p>
                            <p>Exam site/location: <strong>$examSite</strong></p>
                            <p>Please make sure to arrive on time and bring the necessary documents.</p>
                            <br>
                            <p>Best regards,<br>NBSC Online Admission Team</p>
                        ";

                        $mail->send();
                        echo "success";
                    } catch (Exception $e) {
                        echo "Email failed: {$mail->ErrorInfo}";
                    }
                } else {
                    echo "Missing email, exam date, or exam site.";
                }
            }
            $query->close();
        } else {
            echo "success";
        }
    } else {
        echo "Database update failed";
    }

    $stmt->close();
} else {
    echo "Invalid request method";
}
