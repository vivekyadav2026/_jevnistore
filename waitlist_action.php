<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require Composer's autoloader
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($product_id <= 0 || empty($name) || empty($email)) {
        setFlash('Please fill out all required fields.', 'error');
        redirect('product.php?id=' . $product_id);
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO waitlists (product_id, name, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $product_id, $name, $email, $phone);
    
    if ($stmt->execute()) {
        // Fetch product name for the email
        $p_stmt = $conn->prepare("SELECT name FROM products WHERE id = ?");
        $p_stmt->bind_param("i", $product_id);
        $p_stmt->execute();
        $p_res = $p_stmt->get_result();
        $product_name = 'Unknown Product';
        if ($p_res->num_rows > 0) {
            $product_name = $p_res->fetch_assoc()['name'];
        }

        // Send Email Notification to Admin
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.example.com'; // Set the SMTP server to send through
                $mail->SMTPAuth   = true;
                $mail->Username   = 'your_email@example.com'; // SMTP username
                $mail->Password   = 'your_password'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
                $mail->Port       = 587; // TCP port to connect to

                // Recipients
                $mail->setFrom('noreply@norva.store', 'Nørva Store Waitlist');
                // The admin email where you want to receive these notifications
                $mail->addAddress('norvastorex@gmail.com', 'Admin');

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'New Waitlist Request for ' . $product_name;
                $mail->Body    = "
                    <h2>New Waitlist Request</h2>
                    <p><strong>Product:</strong> {$product_name} (ID: {$product_id})</p>
                    <p><strong>Name:</strong> {$name}</p>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Phone:</strong> " . ($phone ? $phone : 'N/A') . "</p>
                    <br>
                    <p>You can view this request in your admin dashboard under the Waitlist section.</p>
                ";

                $mail->send();
            } catch (Exception $e) {
                // Log email error or ignore so user flow isn't interrupted
                error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }
        }

        setFlash('You have successfully joined the waiting list! We will notify you when it is available.', 'success');
        redirect('product.php?id=' . $product_id);
    } else {
        setFlash('There was an error joining the waiting list. Please try again.', 'error');
        redirect('product.php?id=' . $product_id);
    }
} else {
    redirect('index.php');
}
