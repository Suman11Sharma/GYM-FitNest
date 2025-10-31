<?php
include "../../database/user_authentication.php";
ob_start();
require '../../vendor/autoload.php';
include '../../database/db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle POST: send reply
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $to_email = $_POST['to_email'];
    $subject = $_POST['subject'];
    $reply_message = $_POST['reply_message'];

    $mail = new PHPMailer(true);

    try {
        // PHPMailer SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sumanpoudelsharma@gmail.com';
        $mail->Password   = 'rwfobnfellifzinc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('sumanpoudelsharma@gmail.com', 'GYM FitNest');
        $mail->addAddress($to_email);
        $mail->Subject = $subject;
        $mail->Body    = $reply_message;

        if ($mail->send()) {
            // Save reply in DB
            $update = "UPDATE contact_queries SET reply = ?, status = 'replied', updated_at = NOW() WHERE query_id = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("si", $reply_message, $id);
            $stmt->execute();

            header("Location: index.php?status=success&msg=" . urlencode("Reply sent successfully to {$to_email}"));
            exit();
        } else {
            header("Location: index.php?status=warning&msg=" . urlencode("Reply saved but email failed to send"));
            exit();
        }
    } catch (Exception $e) {
        header("Location: index.php?status=error&msg=" . urlencode("Mailer Error: {$mail->ErrorInfo}"));
        exit();
    }
}

// Handle GET: show reply form
$data = [];
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM contact_queries WHERE query_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
}

require '../sidelayout.php';
?>

<div id="layoutSidenav_content">
    <div class="container py-5">
        <h2>Reply to Message</h2>

        <?php if (!empty($data)) : ?>
            <div class="card p-4 mb-4 shadow-sm">
                <p><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
                <p><strong>Subject:</strong> <?= htmlspecialchars($data['subject']) ?></p>
                <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($data['message'])) ?></p>
            </div>

            <form method="POST">
                <input type="hidden" name="id" value="<?= $data['query_id'] ?>">
                <input type="hidden" name="to_email" value="<?= htmlspecialchars($data['email']) ?>">
                <input type="hidden" name="subject" value="Reply: <?= htmlspecialchars($data['subject']) ?>">

                <div class="mb-3">
                    <label for="reply_message" class="form-label"><strong>Your Reply</strong></label>
                    <textarea class="form-control" id="reply_message" name="reply_message" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-success">Send Reply</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </form>
        <?php else : ?>
            <p class="text-danger">No data found for this query.</p>
            <a href="index.php" class="btn btn-secondary">Back</a>
        <?php endif; ?>
    </div>
</div>

<?php require("../assets/link.php"); ?>