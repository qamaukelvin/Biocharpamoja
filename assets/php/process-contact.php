<?php
include 'db_connect.php'; // Include DB connection
// SETTINGS
$recipient = "info@biocharpamoja.co.ke"; 
// IMPORTANT: This MUST be an email created on your hosting (e.g. cPanel)
// Do not use the visitor's email here, or the server will block it as spam/spoofing.
$sender_email = "info@biocharpamoja.co.ke"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Sanitize & Get Data
    $name = strip_tags(trim($_POST["Firstname"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["Email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["Message"]);

    // 2. Validate Data
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.php?status=invalid");
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    $stmt->execute();
    $stmt->close();

    // 3. Email Configuration
    $subject = "New Contact from Website: $name";

    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";

    // 4. Headers (The Fix for Server Blocking)
    // We send FROM your site, but set Reply-To as the visitor.
    $email_headers = "From: Biochar Website <$sender_email>\r\n";
    $email_headers .= "Reply-To: $name <$email>\r\n";
    $email_headers .= "X-Mailer: PHP/" . phpversion();


    if (true) { 
    // mail($recipient, ...); // Comment this out on localhost
    header("Location: ../../contact.php?status=success");
}
} else {
    header("Location: ../../contact.php");
    exit;
}
?>