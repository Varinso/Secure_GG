<?php
require_once 'db.php';

if (isset($_GET['booth_id'])) {
    $booth_id = intval($_GET['booth_id']);
    $query = "SELECT price FROM booths WHERE booth_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $booth_id);
    $stmt->execute();
    $stmt->bind_result($price);
    $stmt->fetch();
    $stmt->close();
    echo json_encode(['total_cost' => $price]);
}
?>
