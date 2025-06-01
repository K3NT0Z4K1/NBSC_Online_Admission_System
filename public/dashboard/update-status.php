<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once("../../functions/functions.php");  // Your DB connection ($mycon)
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

    // Update application_status in the database
    $stmt = $mycon->prepare("UPDATE tbl_applications SET application_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        // If Approved, send email notification
        if ($status === 'Approved') {
            // Fetch applicant email and exam_date
            $query = $mycon->prepare("SELECT email, exam_date, CONCAT(firstname, ' ', lastname) AS fullname FROM tbl_applications WHERE id = ?");
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();
            if ($result && $row = $result->fetch_assoc()) {
                $email = $row['email'];
                $examDate = $row['exam_date'];
                $fullname = $row['fullname'];

                if ($email && $examDate) {
                    $mail = new PHPMailer(true);

                    try {
                        // SMTP configuration
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';            // your SMTP server
                        $mail->SMTPAuth = true;
                        $mail->Username = 'kentryanpagongpong@gmail.com'; // your SMTP username
                        $mail->Password = 'ZNFiFiEFi';    // your SMTP app password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Email settings
                        $mail->setFrom('kentryanpagongpong@gmail.com', 'NBSC Online Admission');
                        $mail->addAddress($email, $fullname);
                        $mail->Subject = 'Your NBSC Admission Application Has Been Approved';
                        $mail->isHTML(true);
                        $mail->Body = "
                            <p>Dear $fullname,</p>
                            <p>Congratulations! Your admission application has been <strong>approved</strong>.</p>
                            <p>Your scheduled exam date is: <strong>" . date("F d, Y h:i A", strtotime($examDate)) . "</strong>.</p>
                            <p>Please make sure to arrive on time and bring the necessary documents.</p>
                            <br>
                            <p>Best regards,<br>NBSC Online Admission Team</p>
                        ";

                        $mail->send();
                    } catch (Exception $e) {
                        // You can log error if you want
                        // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
            }
            $query->close();
        }

        echo "success";
    } else {
        echo "Database update failed";
    }

    $stmt->close();
} else {
    echo "Invalid request method";
}
?>
