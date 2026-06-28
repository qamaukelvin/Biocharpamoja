<?php
// admin/dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
include '../assets/php/db_connect.php'; 

// --- 1. HANDLE NEWSLETTER SENDING ---
$newsletter_msg = "";
if (isset($_POST['send_newsletter'])) {
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);
    $headers = "From: no-reply@yourwebsite.com";

    $subs = $conn->query("SELECT email FROM subscribers");
    $count = 0;
    while($row = $subs->fetch_assoc()) {
        mail($row['email'], $subject, $message, $headers); // In production use PHPMailer
        $count++;
    }
    $newsletter_msg = "Newsletter sent to $count subscribers!";
}

// --- 2. HANDLE ACTIONS (Delete Post, Message, Comment & Approve Comment) ---
if (isset($_GET['delete_post'])) {
    $id = $_GET['delete_post'];
    $conn->query("DELETE FROM blog_posts WHERE id=$id");
    header("Location: dashboard.php"); exit();
}
if (isset($_GET['delete_msg'])) {
    $id = $_GET['delete_msg'];
    $conn->query("DELETE FROM messages WHERE id=$id");
    header("Location: dashboard.php?tab=messages"); exit();
}
// NEW: Comment Actions
if (isset($_GET['approve_comment'])) {
    $id = $_GET['approve_comment'];
    $conn->query("UPDATE comments SET status='approved' WHERE id=$id");
    header("Location: dashboard.php?tab=comments"); exit();
}
if (isset($_GET['delete_comment'])) {
    $id = $_GET['delete_comment'];
    $conn->query("DELETE FROM comments WHERE id=$id");
    header("Location: dashboard.php?tab=comments"); exit();
}

// --- 3. HANDLE BLOG SAVE ---
if (isset($_POST['save_post'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $content = $conn->real_escape_string($_POST['content']);
    $edit_id = $_POST['edit_id'];

    $db_image_path = $_POST['current_image'];
    if (!empty($_FILES['image']['name'])) {
        $target = "../assets/uploads/" . time() . "_" . basename($_FILES["image"]["name"]);
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $target)) {
            $db_image_path = "assets/uploads/" . time() . "_" . basename($_FILES["image"]["name"]);
        }
    }

    $gallery_json = $_POST['current_gallery'];
    if (!empty($_FILES['gallery']['name'][0])) {
        $paths = [];
        foreach ($_FILES['gallery']['name'] as $k => $name) {
            $t = "../assets/uploads/" . time() . "_" . basename($name);
            if(move_uploaded_file($_FILES['gallery']['tmp_name'][$k], $t)){
                $paths[] = "assets/uploads/" . time() . "_" . basename($name);
            }
        }
        $gallery_json = json_encode($paths);
    }

    if ($edit_id) {
        $sql = "UPDATE blog_posts SET title='$title', category='$category', content='$content', image='$db_image_path', gallery_images='$gallery_json' WHERE id=$edit_id";
    } else {
        $sql = "INSERT INTO blog_posts (title, category, content, image, gallery_images) VALUES ('$title', '$category', '$content', '$db_image_path', '$gallery_json')";
    }
    $conn->query($sql);
    header("Location: dashboard.php"); exit();
}

// --- METRICS ---
$count_blogs = $conn->query("SELECT COUNT(*) as c FROM blog_posts")->fetch_assoc()['c'];
$count_msgs  = $conn->query("SELECT COUNT(*) as c FROM messages")->fetch_assoc()['c'];
$count_subs  = $conn->query("SELECT COUNT(*) as c FROM subscribers")->fetch_assoc()['c'];
// NEW: Count Pending Comments
$count_comments = $conn->query("SELECT COUNT(*) as c FROM comments WHERE status='pending'")->fetch_assoc()['c'];

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit_data = $conn->query("SELECT * FROM blog_posts WHERE id=$id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar { background: #343a40; min-height: 100vh; color: white; }
        .sidebar a { color: #cfd2d6; text-decoration: none; padding: 12px 20px; display: block; border-left: 3px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background: #495057; border-left-color: #0d6efd; color: white; }
        .stat-card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .icon-box { font-size: 2rem; opacity: 0.8; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark d-md-none p-3">
    <span class="navbar-brand">Admin Panel</span>
    <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
        <i class="fas fa-bars"></i>
    </button>
</nav>

<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="nav flex-column mobile-nav-links">
            <a href="#tab-dashboard" class="nav-link mobile-trigger"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="#tab-blogs" class="nav-link mobile-trigger"><i class="fas fa-pen me-2"></i> Manage Blog</a>
            <a href="#tab-comments" class="nav-link mobile-trigger"><i class="fas fa-comments me-2"></i> Comments <?php if($count_comments>0) echo "<span class='badge bg-danger'>$count_comments</span>"; ?></a>
            <a href="#tab-messages" class="nav-link mobile-trigger"><i class="fas fa-envelope me-2"></i> Messages</a>
            <a href="#tab-newsletter" class="nav-link mobile-trigger"><i class="fas fa-paper-plane me-2"></i> Newsletter</a>
            <a href="logout.php" class="nav-link text-danger mt-4"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar-col sidebar p-0 d-none d-md-block">
            <h4 class="text-center py-4 border-bottom border-secondary">Admin Panel</h4>
            <div class="nav flex-column" id="v-pills-tab" role="tablist">
                <a class="nav-link active" id="v-dashboard-tab" data-bs-toggle="pill" href="#tab-dashboard"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                <a class="nav-link" id="v-blogs-tab" data-bs-toggle="pill" href="#tab-blogs"><i class="fas fa-pen me-2"></i> Manage Blog</a>
                <a class="nav-link" id="v-comments-tab" data-bs-toggle="pill" href="#tab-comments"><i class="fas fa-comments me-2"></i> Comments <?php if($count_comments>0) echo "<span class='badge bg-danger float-end'>$count_comments</span>"; ?></a>
                <a class="nav-link" id="v-messages-tab" data-bs-toggle="pill" href="#tab-messages"><i class="fas fa-envelope me-2"></i> Messages <span class="badge bg-secondary float-end"><?php echo $count_msgs; ?></span></a>
                <a class="nav-link" id="v-newsletter-tab" data-bs-toggle="pill" href="#tab-newsletter"><i class="fas fa-paper-plane me-2"></i> Newsletter</a>
                <a href="../index.php" target="_blank" class="nav-link mt-5"><i class="fas fa-external-link-alt me-2"></i> Visit Site</a>
                <a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            </div>
        </div>

        <div class="col-md-10 p-4">
            
            <div class="tab-content" id="v-pills-tabContent">
                
                <div class="tab-pane fade show active" id="tab-dashboard">
                    <h3 class="mb-4">Dashboard Overview</h3>
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card bg-primary text-white p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><h3><?php echo $count_blogs; ?></h3><small>Total Blogs</small></div>
                                    <i class="fas fa-file-alt icon-box"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card bg-danger text-white p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><h3><?php echo $count_comments; ?></h3><small>Pending Comments</small></div>
                                    <i class="fas fa-comments icon-box"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card bg-success text-white p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><h3><?php echo $count_msgs; ?></h3><small>Messages</small></div>
                                    <i class="fas fa-envelope icon-box"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card bg-warning text-dark p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><h3><?php echo $count_subs; ?></h3><small>Subscribers</small></div>
                                    <i class="fas fa-users icon-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-blogs">
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-header bg-white fw-bold"><?php echo $edit_data ? 'Edit Post' : 'Write New Post'; ?></div>
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="edit_id" value="<?php echo $edit_data['id'] ?? ''; ?>">
                                        <input type="hidden" name="current_image" value="<?php echo $edit_data['image'] ?? ''; ?>">
                                        <input type="hidden" name="current_gallery" value='<?php echo $edit_data['gallery_images'] ?? ''; ?>'>
                                        
                                        <div class="mb-2"><label>Title</label><input type="text" name="title" class="form-control" value="<?php echo $edit_data['title'] ?? ''; ?>" required></div>
                                        <div class="mb-2"><label>Category</label>
                                            <select name="category" class="form-select">
                                                <option>News</option><option>Projects</option><option>Events</option>
                                            </select>
                                        </div>
                                        <div class="mb-2"><label>Main Image</label><input type="file" name="image" class="form-control"></div>
                                        <div class="mb-2"><label>Gallery</label><input type="file" name="gallery[]" class="form-control" multiple></div>
                                        <div class="mb-3"><label>Content</label><textarea name="content" class="form-control" rows="5" required><?php echo $edit_data['content'] ?? ''; ?></textarea></div>
                                        <button type="submit" name="save_post" class="btn btn-dark w-100"><?php echo $edit_data ? 'Update' : 'Publish'; ?></button>
                                        <?php if($edit_data): ?><a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Cancel</a><?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-header bg-white fw-bold">Existing Posts</div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light"><tr><th>Title</th><th>Date</th><th>Action</th></tr></thead>
                                        <tbody>
                                            <?php 
                                            $posts = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
                                            while($p = $posts->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($p['title']); ?></td>
                                                <td><small><?php echo date('M d', strtotime($p['created_at'])); ?></small></td>
                                                <td>
                                                    <a href="dashboard.php?edit=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                                    <a href="dashboard.php?delete_post=<?php echo $p['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?');"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-comments">
                    <h4 class="mb-3">Pending Comments</h4>
                    <div class="card shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light"><tr><th>Author</th><th>Comment</th><th>Status</th><th>Action</th></tr></thead>
                                <tbody>
                                    <?php 
                                    // Fetch only Pending comments
                                    $coms = $conn->query("SELECT * FROM comments WHERE status = 'pending' ORDER BY created_at ASC");
                                    if ($coms->num_rows > 0):
                                    while($c = $coms->fetch_assoc()): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars($c['name']); ?></td>
                                        <td style="max-width: 400px;">
                                            <p class="mb-0 text-muted"><?php echo htmlspecialchars($c['comment']); ?></p>
                                            <small class="text-primary">Post ID: <?php echo $c['post_id']; ?></small>
                                        </td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                        <td>
                                            <a href="dashboard.php?approve_comment=<?php echo $c['id']; ?>" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Approve</a>
                                            <a href="dashboard.php?delete_comment=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete comment?');"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr><td colspan="4" class="text-center p-4">No pending comments. Good job!</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-messages">
                    <h4 class="mb-3">Inbox</h4>
                    <div class="card shadow-sm">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Message</th><th>Action</th></tr></thead>
                                <tbody>
                                    <?php 
                                    $msgs = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
                                    if ($msgs->num_rows > 0):
                                    while($m = $msgs->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($m['name']); ?></td>
                                        <td><a href="mailto:<?php echo $m['email']; ?>"><?php echo $m['email']; ?></a></td>
                                        <td><?php echo htmlspecialchars(substr($m['message'], 0, 50)) . '...'; ?></td>
                                        <td><a href="dashboard.php?delete_msg=<?php echo $m['id']; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a></td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr><td colspan="4" class="text-center p-4">No messages yet.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-newsletter">
                    <h4 class="mb-3">Send Newsletter</h4>
                    <?php if($newsletter_msg) echo "<div class='alert alert-success'>$newsletter_msg</div>"; ?>
                    <div class="card shadow-sm p-4">
                        <form method="POST">
                            <div class="mb-3"><label>Subject</label><input type="text" name="subject" class="form-control" required></div>
                            <div class="mb-3"><label>Message</label><textarea name="message" class="form-control" rows="8" required></textarea></div>
                            <button type="submit" name="send_newsletter" class="btn btn-primary"><i class="fas fa-paper-plane me-2"></i> Send to <?php echo $count_subs; ?> Subscribers</button>
                        </form>
                    </div>
                </div>

            </div> 
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileLinks = document.querySelectorAll('.mobile-trigger');
    const offcanvasElement = document.getElementById('mobileSidebar');
    const desktopTabs = document.getElementById('v-pills-tab');
    mobileLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); 
            const canvasInstance = bootstrap.Offcanvas.getInstance(offcanvasElement);
            if(canvasInstance) canvasInstance.hide();
            const targetId = this.getAttribute('href'); 
            const correspondingDesktopBtn = desktopTabs.querySelector(`a[href="${targetId}"]`);
            if (correspondingDesktopBtn) {
                bootstrap.Tab.getOrCreateInstance(correspondingDesktopBtn).show();
            }
        });
    });
});
</script>
</body>
</html>