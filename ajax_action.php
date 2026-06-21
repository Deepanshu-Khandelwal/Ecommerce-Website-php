<?php
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'cart_add':
        include 'cart_add.php';
        break;
    case 'cart_delete':
        include 'cart_delete.php';
        break;
    case 'cart_update':
        include 'cart_update.php';
        break;
    case 'cart_fetch':
        include 'cart_fetch.php';
        break;
    case 'cart_total':
        include 'cart_total.php';
        break;
    case 'cart_details':
        include 'cart_details.php';
        break;
    case 'transaction_details':
        include 'transaction_details.php';
        break;
    default:
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => true, 'message' => 'Invalid action']);
        break;
}
?>
