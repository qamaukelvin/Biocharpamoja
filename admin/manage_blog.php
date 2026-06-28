<?php
// admin/manage_blog.php
session_start();
// Adjust path to reach assets/php from admin folder
include '../assets/php/db_connect.php'; 

// Check login (Uncomment if you have session checks)
// if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }

$title = "";
$content = "";
$category = "";
$id = "";
$update_mode = false;

// Handle GET request to fetch data for Editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update_mode = true;
    $result = $conn->query("SELECT * FROM blogs WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $content = $row['content'];
        $category = $row['category'];
    }
}

// Handle POST request (Create or Update)
if (isset($_POST['save_blog'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $category = $conn->real_escape_string($_POST['category']);

    if (isset($_POST['id']) && $_POST['id'] != '') {
        // UPDATE
        $id = $_POST['id'];
        $sql = "UPDATE blogs SET title='$title', content='$content', category='$category' WHERE id=$id";
        $conn->query($sql);
        $blog_id = $id; // For image logic
    } else {
        // INSERT
        $sql = "INSERT INTO blogs (title, content, category) VALUES ('$title', '$content', '$category')";
        $conn->query($sql);
        $blog_id = $conn->insert_id;
    }

    // Handle Multiple Image Uploads
    if (!empty($_FILES['images']['name'][0])) {
        // Create uploads dir if not exists (path relative to admin)
        $target_dir = "../assets/uploads/"; 
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

        foreach ($_FILES['images']['name'] as $key => $val) {
            $image_name = basename($_FILES['images']['name'][$key]);
            $target_file = $target_dir . time() . "_" . $image_name;
            
            if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
                // Store path relative to root for easier display on frontend
                $db_path = "assets/uploads/" . time() . "_" . $image_name;
                $conn->query("INSERT INTO blog_images (blog_id, image_path) VALUES ($blog_id, '$db_path')");
            }
        }
    }

    header("Location: dashboard.php"); // Redirect back to dashboard
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $update_mode ? 'Edit' : 'Add'; ?> Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><?php echo $update_mode ? 'Edit Blog Post' : 'Add New Blog Post'; ?></h4>
        </div>
        <div class="card-body">
            <form action="manage_blog.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="mb-3">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label>Category</label>
                    <input type="text" name="category" class="form-control" value="<?php echo $category; ?>" placeholder="e.g. Technology, Updates">
                </div>

                <div class="mb-3">
                    <label>Content</label>
                    <textarea name="content" class="form-control" rows="10" required><?php echo $content; ?></textarea>
                    <small class="text-muted">You can use HTML tags here for formatting.</small>
                </div>

                <div class="mb-3">
                    <label>Images (Select Multiple)</label>
                    <input type="file" name="images[]" class="form-control" multiple>
                    <?php if ($update_mode): ?>
                        <div class="mt-2">
                            <small>Existing images are kept. New uploads will be added.</small>
                        </div>
                    <?php endif; ?>
                </div>

                <button type="submit" name="save_blog" class="btn btn-success">
                    <?php echo $update_mode ? 'Update Post' : 'Publish Post'; ?>
                </button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>