<?php
// razorpay_webhook.php
include 'includes/session.php'; // if this outputs anything, remove includes and just connect DB directly
include 'config/razorpay.php';

// Replace with your webhook secret set in Razorpay Dashboard
const RAZORPAY_WEBHOOK_SECRET = '@EG9iz@@F2MC_KA';

$body    = file_get_contents('php://input');
$payload = $body;
$sig     = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

function verify_webhook($payload, $sig, $secret) {
    $expected = hash_hmac('sha256', $payload, $secret);
    return hash_equals($expected, $sig);
}

http_response_code(200); // respond 200 to avoid retries after validation

try {
    if (!verify_webhook($payload, $sig, RAZORPAY_WEBHOOK_SECRET)) {
        error_log('Razorpay webhook: signature mismatch');
        exit;
    }

    $event = json_decode($payload, true);
    if (!$event || empty($event['event'])) exit;

    $type = $event['event'];

    // Handle payment.captured
    if ($type === 'payment.captured') {
        $paymentId = $event['payload']['payment']['entity']['id'] ?? null;
        $orderId   = $event['payload']['payment']['entity']['order_id'] ?? null;

        if ($paymentId && $orderId) {
            $conn = $pdo->open();
            $stmt = $conn->prepare("UPDATE sales
                                    SET status='paid', razorpay_payment_id=:pid, pay_id=:pid, updated_at=NOW()
                                    WHERE razorpay_order_id=:oid AND status <> 'paid'");
            $stmt->execute(['pid' => $paymentId, 'oid' => $orderId]);
        }
    }

    // Add other event handlers as needed
} catch (Throwable $e) {
    error_log('Razorpay webhook error: ' . $e->getMessage());
}
