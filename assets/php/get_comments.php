<?php
// assets/php/get_comments.php
header('Content-Type: application/json');
include 'db_connect.php';

$post_id = (int)($_GET['post_id'] ?? 0);
if ($post_id <= 0) { echo json_encode([]); exit(); }

$stmt = $conn->prepare(
    "SELECT name, comment, created_at FROM comments
     WHERE post_id = ? AND status = 'approved'
     ORDER BY created_at ASC"
);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

$out = [];
while ($row = $result->fetch_assoc()) {
    $out[] = [
        'name'    => htmlspecialchars($row['name']),
        'comment' => htmlspecialchars($row['comment']),
        'date'    => date('M j, Y', strtotime($row['created_at'])),
    ];
}
echo json_encode($out);
