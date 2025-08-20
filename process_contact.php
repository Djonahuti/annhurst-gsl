<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    $errors = [];
    
    // Validation
    if (empty($name)) {
        $errors[] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    }
    
    if (empty($message)) {
        $errors[] = 'Message is required';
    }
    
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("INSERT INTO contact_submissions (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$name, $email, $phone, $subject, $message]);
            
            if ($result) {
                // Send email notification (optional)
                $to = getSetting('contact_email', 'info@annhurst.com');
                $emailSubject = 'New Contact Form Submission - ' . ($subject ?: 'General Inquiry');
                $emailBody = "New contact form submission:\n\n";
                $emailBody .= "Name: $name\n";
                $emailBody .= "Email: $email\n";
                $emailBody .= "Phone: $phone\n";
                $emailBody .= "Subject: $subject\n";
                $emailBody .= "Message:\n$message\n";
                
                $headers = "From: $email\r\n";
                $headers .= "Reply-To: $email\r\n";
                
                mail($to, $emailSubject, $emailBody, $headers);
                
                $_SESSION['success_message'] = 'Thank you for your message! We will get back to you soon.';
            } else {
                $_SESSION['error_message'] = 'Sorry, there was an error sending your message. Please try again.';
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Sorry, there was an error sending your message. Please try again.';
        }
    } else {
        $_SESSION['error_message'] = 'Please correct the following errors: ' . implode(', ', $errors);
    }
    
    // Redirect back to contact page
    header('Location: index.php?page=contact');
    exit;
} else {
    // If not POST request, redirect to home
    header('Location: index.php');
    exit;
}
?>
