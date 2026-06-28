<?php
// admin/delete_blog.php
include '../assets/php/db_connect.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Optional: Delete actual image files from server before deleting DB record
    $imgs = $conn->query("SELECT image_path FROM blog_images WHERE blog_id=$id");
    while($row = $imgs->fetch_assoc()) {
        $file = "../" . $row['image_path'];
        if(file_exists($file)) unlink($file);
    }

    // Delete Blog (Cascading delete removes DB image records automatically)
    $conn->query("DELETE FROM blogs WHERE id=$id");
}

header("Location: dashboard.php");
exit();
?>