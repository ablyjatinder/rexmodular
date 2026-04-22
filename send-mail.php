<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header('Content-Type: application/json');
// Get POST data safely
$name        = trim($_POST['name'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$email       = trim($_POST['email'] ?? '');
$description = trim($_POST['description'] ?? '');

// Basic validation (VERY IMPORTANT)
if ($name == '' || $phone == '' || $email == '' || $description == '') {
    echo json_encode(['status' => 'error', 'msg' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'msg' => 'Invalid email']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // SMTP config (use your Outlook/domain email settings)
    $mail->isSMTP();
    $mail->Host       = 'smtp.office365.com'; // or your hosting SMTP
    $mail->SMTPAuth   = true;
    $mail->Username   = 'Vicky@synergycd.com'; // your email
    $mail->Password   = 'Hellomengru123';      // your password / app password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Sender
    $mail->setFrom('Vicky@synergycd.com', 'Website Contact');

    // Send to YOU (admin)
    $mail->addAddress('Vicky@synergycd.com');
    $mail->addCC('brian@rexmodular.com');
    // Optional: Reply to user
    $mail->addReplyTo($email, $name);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Submission on Rexmodular.com';

    $mail->Body = "
        <h3>New Enquiry</h3>
        <p><b>Name:</b> {$name}</p>
        <p><b>Phone:</b> {$phone}</p>
        <p><b>Email:</b> {$email}</p>
        <p><b>Description:</b><br>{$description}</p>
    ";

    $mail->AltBody = "Name: $name\nPhone: $phone\nEmail: $email\nMessage: $description";

    if (!$mail->send()) {
        echo json_encode([
            'status' => 'error',
            'msg' => $mail->ErrorInfo
        ]);
    } else {
        echo json_encode(['status' => 'success']);
    }

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'msg' => $mail->ErrorInfo
    ]);
}
