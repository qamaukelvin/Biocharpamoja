<?php 
$page_title = "News & Updates";
include 'assets/php/db_connect.php'; 

// --- HANDLE NEWSLETTER SUBSCRIPTION ---
$sub_msg = "";
$sub_status = "";

if (isset($_POST['subscribe'])) {
    $email = $conn->real_escape_string($_POST['email']);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $check = $conn->query("SELECT id FROM subscribers WHERE email='$email'");
        if ($check->num_rows > 0) {
            $sub_msg = "You are already subscribed!";
            $sub_status = "warning";
        } else {
            $conn->query("INSERT INTO subscribers (email) VALUES ('$email')");
            $sub_msg = "Welcome to the community! You've been subscribed.";
            $sub_status = "success";
        }
    } else {
        $sub_msg = "Please enter a valid email address.";
        $sub_status = "danger";
    }
}

// --- HANDLE NEW COMMENT SUBMISSION ---
if (isset($_POST['submit_comment'])) {
    $post_id = $_POST['post_id'];
    $name = $conn->real_escape_string($_POST['name']);
    $comment = $conn->real_escape_string($_POST['comment']);

    if (!empty($name) && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, name, comment, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("iss", $post_id, $name, $comment);
        $stmt->execute();
        header("Location: blog.php?msg=pending#post-" . $post_id);
        exit();
    }
}

include 'includes/header.php'; 
?>

<div class="container mt-5 pt-4">
    
    <div class="row text-center mb-5">
        <div class="section-title">
            <h2>Latest News & Stories</h2>
            <p class="text-muted">Stay updated with our field activities, research, and community impact.</p>
        </div>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'pending'): ?>
    <div class="alert alert-success alert-dismissible fade show fixed-top m-3" style="z-index: 1050;" role="alert">
        <strong>Thanks!</strong> Your comment has been submitted and is awaiting admin approval.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <?php 
            $sql = "SELECT * FROM blog_posts ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0):
                $counter = 0;
                while($row = $result->fetch_assoc()):
                    $id = $row['id'];
                    $gallery = json_decode($row['gallery_images'], true);
                    $has_gallery = !empty($gallery) && count($gallery) > 0;
                    
                    // Alternating Layout Logic (Matches your Projects style)
                    // Even: Image Left (Order 1), Text Right (Order 2)
                    // Odd: Text Left (Order 1), Image Right (Order 2)
                    $img_order = ($counter % 2 == 0) ? 'order-1 order-lg-1' : 'order-1 order-lg-2';
                    $txt_order = ($counter % 2 == 0) ? 'order-2 order-lg-2' : 'order-2 order-lg-1';
            ?>

            <div class="card mb-5 border-0 shadow portfolio-item project overflow-hidden" id="post-<?php echo $id; ?>" style="background-color:var(--bg-card);">
                <div class="row g-0 align-items-center">
                    
                    <div class="col-lg-6 <?php echo $img_order; ?>">
                        <?php if ($has_gallery): ?>
                            <div id="carouselPost<?php echo $id; ?>" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php if(!empty($row['image'])): ?>
                                        <div class="carousel-item active">
                                            <img src="<?php echo $row['image']; ?>" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Main">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    $g_count = 0;
                                    foreach($gallery as $img): 
                                        $active = (empty($row['image']) && $g_count == 0) ? 'active' : '';
                                    ?>
                                        <div class="carousel-item <?php echo $active; ?>">
                                            <img src="<?php echo $img; ?>" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Gallery">
                                        </div>
                                    <?php $g_count++; endforeach; ?>
                                </div>
                                
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselPost<?php echo $id; ?>" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselPost<?php echo $id; ?>" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            </div>
                        <?php else: ?>
                            <img src="<?php echo !empty($row['image']) ? $row['image'] : 'assets/images/default.jpg'; ?>" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Post Image">
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-6 <?php echo $txt_order; ?>">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="card-title fw-bold mb-0"><?php echo htmlspecialchars($row['title']); ?></h3>
                                <span class="badge bg-success"><?php echo htmlspecialchars($row['category']); ?></span>
                            </div>
                            
                            <p class="text-muted mb-3"><?php echo date('F d, Y', strtotime($row['created_at'])); ?></p>
                            
                            <p class="card-text lead">
                                <?php echo substr(strip_tags($row['content']), 0, 150) . '...'; ?>
                            </p>
                            
                            <div class="d-grid gap-2 d-md-block">
                                <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#flush-post<?php echo $id; ?>">
                                    Read Full Details <i class="fas fa-chevron-down ms-1"></i>
                                </button>
                                <a href="#comments-<?php echo $id; ?>" class="btn btn-success text-white" data-bs-toggle="collapse" data-bs-target="#flush-post<?php echo $id; ?>">
                                    <i class="fas fa-comment"></i> Comment
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 order-3">
                        <div class="accordion accordion-flush" id="accordionPost<?php echo $id; ?>">
                            <div id="flush-post<?php echo $id; ?>" class="accordion-collapse collapse bg-light" data-bs-parent="#accordionPost<?php echo $id; ?>">
                                <div class="accordion-body p-4" style="background-color:var(--bg-card);">
                                    
                                    <div class="row mb-5">
                                        <div class="col-12">
                                            <h5 class="fw-bold text-success mb-3"><?php echo htmlspecialchars($row['title']); ?></h5>
                                            <div style="line-height: 1.8;">
                                                <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                                            </div>
                                            <?php if (stripos($row['content'], 'kiln') !== false || stripos($row['content'], 'pyrolysis') !== false): ?>
                                                 <div class="my-4 text-center">
                                                    
                                                 </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <hr class="text-muted">

                                    <div class="row mt-4" id="comments-<?php echo $id; ?>" style="background-color:var(--bg-card);">
                                        <div class="col-md-6 mb-4" style="background-color:var(--bg-card);">
                                            <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-comments"></i> Discussion</h5>
                                            <div class="bg-white p-3 rounded shadow-sm" style="max-height: 300px; overflow-y: auto;">
                                                <?php 
                                                $c_sql = "SELECT * FROM comments WHERE post_id = $id AND status = 'approved' ORDER BY created_at DESC";
                                                $c_res = $conn->query($c_sql);
                                                if ($c_res->num_rows > 0):
                                                    while($com = $c_res->fetch_assoc()):
                                                ?>
                                                    <div class="mb-3 border-bottom pb-2" style="background-color:var(--bg-card);">
                                                        <strong class="text-dark"><?php echo htmlspecialchars($com['name']); ?></strong>
                                                        <small class="text-muted float-end"><?php echo date('M d', strtotime($com['created_at'])); ?></small>
                                                        <p class="mb-0 small mt-1 text-secondary"><?php echo htmlspecialchars($com['comment']); ?></p>
                                                    </div>
                                                <?php endwhile; else: ?>
                                                    <p class="text-muted text-center small my-4">No comments yet.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="col-md-6" style="background-color:var(--bg-card);" >
                                            <h5 class="fw-bold mb-3 text-dark">Leave a Reply</h5>
                                            <div class="bg-white p-3 rounded shadow-sm project-card" style="background-color:var(--bg-card);">
                                                <form method="POST" action="blog.php">
                                                    <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                                                    <div class="mb-3">
                                                        <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <textarea name="comment" class="form-control" rows="3" placeholder="Write your thought here..." required></textarea>
                                                    </div>
                                                    <button type="submit" name="submit_comment" class="btn btn-success w-100">Post Comment (Moderated)</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>
            <?php 
                $counter++; 
                endwhile; 
            else: 
            ?>
                <div class="text-center py-5">
                    <h3>No posts found.</h3>
                    <p class="text-muted">Check back later for updates!</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<section class="py-5 mt-5" style="background:var(--bg-card);">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-3">Don't Miss an Update</h2>
                <p class="lead mb-4">Join our community to get the latest field reports, research findings, and project news delivered to your inbox.</p>
                
                <?php if ($sub_msg): ?>
                    <div class="alert alert-<?php echo $sub_status; ?> alert-dismissible fade show text-start text-dark" role="alert">
                        <?php echo $sub_msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="blog.php#newsletter" id="newsletter">
                    <div class="input-group input-group-lg shadow p-1 rounded-pill">
                        <input type="email" name="email" class="form-control border-0 rounded-pill ps-4" placeholder="Enter your email address..." required style="outline: none; box-shadow: none;">
                        <button class="btn btn-success rounded-pill px-4 m-1 fw-bold" type="submit" name="subscribe">
                            Subscribe <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                    <small class="d-block mt-3 text-white-50">No spam. Unsubscribe anytime.</small>
                </form>

            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>