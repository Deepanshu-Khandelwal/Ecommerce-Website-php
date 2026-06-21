<?php
include 'includes/session.php';

$output = ['error' => false];
$id = $_POST['id'] ?? 0;

$conn = $pdo->open();
if (isset($_SESSION['user'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM cart WHERE id=:id");
        $stmt->execute(['id' => $id]);
        $output['message'] = 'Deleted';
    } catch (PDOException $e) {
        $output['error'] = true;
        $output['message'] = $e->getMessage();
    }
} else {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $row) {
            if ($row['productid'] == $id) {
                unset($_SESSION['cart'][$key]);
                $output['message'] = 'Deleted';
            }
        }
    }
}
$pdo->close();

header('Content-Type: application/json');
echo json_encode($output);
?>
