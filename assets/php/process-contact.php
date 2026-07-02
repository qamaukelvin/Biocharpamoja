<?php
// assets/php/process-contact.php
session_start();

/* ── Namespaces MUST be declared at the top of the file ── */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ── Only accept POST ── */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../contact.php'); exit();
}

/* ── CSRF check ── */
if (
    empty($_SESSION['csrf_token']) ||
    empty($_POST['csrf_token'])    ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    header('Location: ../../contact.php?status=invalid'); exit();
}

/* ── Sanitize & validate ── */
$name    = trim(strip_tags($_POST['Firstname'] ?? ''));
$name    = str_replace(["\r", "\n"], [' ', ' '], $name);
$email   = trim(filter_var($_POST['Email'] ?? '', FILTER_SANITIZE_EMAIL));
$subject = trim(strip_tags($_POST['Subject'] ?? 'General Inquiry'));
$message = trim(strip_tags($_POST['Message'] ?? ''));

if (
    empty($name) || empty($message) ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    mb_strlen($name) > 100 ||
    mb_strlen($subject) > 200 ||
    mb_strlen($message) > 3000
) {
    header('Location: ../../contact.php?status=invalid'); exit();
}

/* ── 1. Save to database ── */
// Connect to the DB only after validation passes
include 'db_connect.php';

$stmt = $conn->prepare(
    "INSERT INTO messages (name, email, subject, message, is_read, created_at)
     VALUES (?, ?, ?, ?, 0, NOW())"
);
$stmt->bind_param("ssss", $name, $email, $subject, $message);
if (!$stmt->execute()) {
    header('Location: ../../contact.php?status=error'); exit();
}
$stmt->close();

/* ── 2. Send emails ── */

// Site settings
$info_email    = "info@biocharpamoja.co.ke";        // receives the contact
$noreply_email = "no-reply@biocharpamoja.co.ke";    // sends auto-reply to visitor
$site_name     = "Biochar Pamoja";

// PHPMailer path — cPanel vendor folder
$phpmailer_path = __DIR__ . '/../../assets/vendor/vendor/PHPMailer/src/';

$mail_sent = false;

if (is_dir($phpmailer_path)) {
    /* ── PHPMailer (SMTP / cPanel mail) ── */
    require $phpmailer_path . 'Exception.php';
    require $phpmailer_path . 'PHPMailer.php';
    require $phpmailer_path . 'SMTP.php';

    /* ─────────────────────────────────────────
       EMAIL 1: Notification to info@
    ───────────────────────────────────────── */
    try {
        $mail = new PHPMailer(true);

        // Use cPanel's local sendmail — no SMTP credentials needed
        // on cPanel shared hosting this is the most reliable method
        $mail->isSendmail();

        $mail->setFrom($info_email, $site_name);
        $mail->addReplyTo($email, $name);          // reply goes straight to visitor
        $mail->addAddress($info_email, $site_name);

        $mail->Subject = "New Contact: [$subject] from $name";
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family:Segoe UI,Arial,sans-serif;background:#f4f4f4;margin:0;padding:0;'>
          <table width='100%' cellpadding='0' cellspacing='0' style='background:#f4f4f4;padding:30px 0;'>
            <tr><td align='center'>
              <table width='600' cellpadding='0' cellspacing='0' style='background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);'>
                <tr>
                  <td style='background:linear-gradient(135deg,#198754,#008B8B);padding:28px 32px;'>
                    <h1 style='color:#ffffff;margin:0;font-size:1.4rem;font-weight:900;letter-spacing:1px;'>BIOCHAR PAMOJA</h1>
                    <p style='color:rgba(255,255,255,0.8);margin:4px 0 0;font-size:0.85rem;'>New Contact Form Submission</p>
                  </td>
                </tr>
                <tr>
                  <td style='padding:32px;'>
                    <table width='100%' cellpadding='0' cellspacing='0'>
                      <tr>
                        <td style='padding:10px 0;border-bottom:1px solid #f0f0f0;'>
                          <span style='font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;color:#888;font-weight:600;'>From</span><br>
                          <span style='font-size:1rem;color:#212529;font-weight:600;'>" . htmlspecialchars($name) . "</span>
                        </td>
                      </tr>
                      <tr>
                        <td style='padding:10px 0;border-bottom:1px solid #f0f0f0;'>
                          <span style='font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;color:#888;font-weight:600;'>Email</span><br>
                          <a href='mailto:" . htmlspecialchars($email) . "' style='color:#198754;font-size:1rem;'>" . htmlspecialchars($email) . "</a>
                        </td>
                      </tr>
                      <tr>
                        <td style='padding:10px 0;border-bottom:1px solid #f0f0f0;'>
                          <span style='font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;color:#888;font-weight:600;'>Subject</span><br>
                          <span style='font-size:1rem;color:#212529;'>" . htmlspecialchars($subject) . "</span>
                        </td>
                      </tr>
                      <tr>
                        <td style='padding:10px 0;'>
                          <span style='font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;color:#888;font-weight:600;'>Message</span><br>
                          <p style='font-size:0.95rem;color:#444;line-height:1.8;background:#f8f9fa;border-left:4px solid #198754;padding:14px 18px;border-radius:0 8px 8px 0;margin:10px 0 0;'>"
                          . nl2br(htmlspecialchars($message)) .
                          "</p>
                        </td>
                      </tr>
                    </table>

                    <div style='margin-top:28px;padding-top:20px;border-top:1px solid #f0f0f0;'>
                      <a href='mailto:" . htmlspecialchars($email) . "?subject=Re: " . htmlspecialchars($subject) . "'
                         style='display:inline-block;background:linear-gradient(135deg,#198754,#008B8B);color:#ffffff;
                                text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:700;font-size:0.9rem;'>
                        Reply to " . htmlspecialchars($name) . "
                      </a>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td style='background:#f8f9fa;padding:18px 32px;text-align:center;'>
                    <p style='color:#888;font-size:0.78rem;margin:0;'>Received " . date('F j, Y \a\t H:i') . " · Biochar Pamoja Website</p>
                  </td>
                </tr>
              </table>
            </td></tr>
          </table>
        </body>
        </html>";

        $mail->AltBody = "New contact from $name ($email)\nSubject: $subject\n\nMessage:\n$message\n\nReceived: " . date('Y-m-d H:i');

        $mail->send();
        $mail_sent = true;

    } catch (Exception $e) {
        error_log("Contact notification mail failed: " . $e->getMessage());
    }

    /* ─────────────────────────────────────────
       EMAIL 2: Auto-acknowledgement to visitor
    ───────────────────────────────────────── */
    try {
        $ack = new PHPMailer(true);
        $ack->isSendmail();

        $ack->setFrom($noreply_email, $site_name);
        $ack->addReplyTo($info_email, $site_name);   // if they reply, goes to info@
        $ack->addAddress($email, $name);

        $ack->Subject = "We received your message – $site_name";
        $ack->isHTML(true);
        $ack->CharSet = 'UTF-8';

        $ack->Body = "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family:Segoe UI,Arial,sans-serif;background:#f4f4f4;margin:0;padding:0;'>
          <table width='100%' cellpadding='0' cellspacing='0' style='background:#f4f4f4;padding:30px 0;'>
            <tr><td align='center'>
              <table width='600' cellpadding='0' cellspacing='0' style='background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);'>
                <tr>
                  <td style='background:linear-gradient(135deg,#198754,#008B8B);padding:32px;text-align:center;'>
                    <h1 style='color:#ffffff;margin:0;font-size:1.5rem;font-weight:900;letter-spacing:2px;'>BIOCHAR PAMOJA</h1>
                    <p style='color:rgba(255,255,255,0.8);margin:6px 0 0;font-size:0.85rem;'>We Fix Carbon</p>
                  </td>
                </tr>
                <tr>
                  <td style='padding:36px 32px;'>
                    <h2 style='color:#198754;font-size:1.2rem;margin:0 0 12px;'>Hello, " . htmlspecialchars($name) . "!</h2>
                    <p style='color:#444;line-height:1.8;font-size:0.95rem;margin:0 0 18px;'>
                      Thank you for reaching out to us. We have received your message and a member of our team will get back to you within <strong>2 business days</strong>.
                    </p>
                    <div style='background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:18px 20px;margin-bottom:24px;'>
                      <p style='font-size:0.78rem;text-transform:uppercase;letter-spacing:1px;color:#166534;font-weight:700;margin:0 0 10px;'>Your message summary</p>
                      <p style='margin:0 0 6px;font-size:0.88rem;color:#444;'><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
                      <p style='margin:0;font-size:0.88rem;color:#444;white-space:pre-line;'><strong>Message:</strong><br>" . htmlspecialchars(mb_substr($message, 0, 200)) . (mb_strlen($message) > 200 ? '…' : '') . "</p>
                    </div>
                    <p style='color:#444;line-height:1.8;font-size:0.95rem;margin:0 0 24px;'>
                      In the meantime, you can explore our projects, read the latest updates from the field, or follow us on social media.
                    </p>
                    <div style='text-align:center;'>
                      <a href='https://biocharpamoja.co.ke/projects.php'
                         style='display:inline-block;background:linear-gradient(135deg,#198754,#008B8B);color:#ffffff;
                                text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:700;font-size:0.9rem;'>
                        Explore Our Projects
                      </a>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td style='background:#f8f9fa;padding:20px 32px;'>
                    <table width='100%'>
                      <tr>
                        <td>
                          <p style='color:#888;font-size:0.78rem;margin:0 0 4px;'>Biochar Pamoja · Bungoma, Kenya</p>
                          <p style='color:#888;font-size:0.78rem;margin:0;'>
                            <a href='mailto:info@biocharpamoja.co.ke' style='color:#198754;'>info@biocharpamoja.co.ke</a>
                            &nbsp;·&nbsp;
                            <a href='tel:+254723545858' style='color:#198754;'>+254 723 545 858</a>
                          </p>
                        </td>
                      </tr>
                    </table>
                    <p style='color:#bbb;font-size:0.72rem;margin:12px 0 0;'>
                      This is an automated message — please do not reply directly to this email.
                      To contact us, email <a href='mailto:info@biocharpamoja.co.ke' style='color:#198754;'>info@biocharpamoja.co.ke</a>.
                    </p>
                  </td>
                </tr>
              </table>
            </td></tr>
          </table>
        </body>
        </html>";

        $ack->AltBody = "Hello $name,\n\nThank you for contacting Biochar Pamoja! We received your message (Subject: $subject) and will reply within 2 business days.\n\nContact us: info@biocharpamoja.co.ke | +254 723 545 858\n\n-- Biochar Pamoja Team\nThis is an automated message — please do not reply to this email.";

        $ack->send();

    } catch (Exception $e) {
        error_log("Contact auto-reply mail failed: " . $e->getMessage());
    }

} else {
    /* ── FALLBACK: PHP mail() if PHPMailer not found ──
       This works on cPanel but with basic formatting */
    error_log("PHPMailer not found at $phpmailer_path — falling back to mail()");

    // Notification to info@
    $headers  = "From: $site_name <$info_email>\r\n";
    $headers .= "Reply-To: $name <$email>\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    $body  = "New contact form submission\n";
    $body .= "============================\n";
    $body .= "Name:    $name\n";
    $body .= "Email:   $email\n";
    $body .= "Subject: $subject\n";
    $body .= "Date:    " . date('Y-m-d H:i') . "\n\n";
    $body .= "Message:\n$message\n";

    @mail($info_email, "New Contact: [$subject] from $name", $body, $headers);
    $mail_sent = true;

    // Auto-reply to visitor
    $ack_headers  = "From: $site_name <$noreply_email>\r\n";
    $ack_headers .= "Reply-To: $info_email\r\n";
    $ack_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $ack_body  = "Hello $name,\n\n";
    $ack_body .= "Thank you for contacting Biochar Pamoja!\n\n";
    $ack_body .= "We have received your message regarding \"$subject\" and will ";
    $ack_body .= "reply within 2 business days.\n\n";
    $ack_body .= "Your message:\n$message\n\n";
    $ack_body .= "---\n";
    $ack_body .= "Biochar Pamoja · Bungoma, Kenya\n";
    $ack_body .= "info@biocharpamoja.co.ke | +254 723 545 858\n\n";
    $ack_body .= "This is an automated message — please do not reply to this email.";

    @mail($email, "We received your message – $site_name", $ack_body, $ack_headers);
}

/* ── Redirect ── */
header('Location: ../../contact.php?status=success'); exit();