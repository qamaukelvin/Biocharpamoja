<?php
$page_title = "News & Updates";
include 'assets/php/db_connect.php';

/* ── CSRF token ── */
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

/* ── Newsletter subscription ── */
$sub_msg    = "";
$sub_status = "";

if (isset($_POST['subscribe'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $sub_msg = "Invalid request."; $sub_status = "danger";
    } else {
        $email = trim($_POST['email']);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("SELECT id FROM subscribers WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $sub_msg = "You're already subscribed!";
                $sub_status = "info";
            } else {
                $ins = $conn->prepare("INSERT INTO subscribers (email, subscribed_at) VALUES (?, NOW())");
                $ins->bind_param("s", $email);
                $ins->execute();
                $sub_msg = "Welcome aboard! You've been subscribed.";
                $sub_status = "success";
            }
        } else {
            $sub_msg = "Please enter a valid email address.";
            $sub_status = "danger";
        }
    }
}

/* ── Comment submission ── */
if (isset($_POST['submit_comment'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        header("Location: blog.php"); exit();
    }
    $post_id = (int)$_POST['post_id'];
    $name    = trim($_POST['name']);
    $comment = trim($_POST['comment']);
    if ($post_id > 0 && !empty($name) && !empty($comment) && mb_strlen($name) <= 100 && mb_strlen($comment) <= 1000) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, name, comment, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
        $stmt->bind_param("iss", $post_id, $name, $comment);
        $stmt->execute();
        header("Location: blog.php?commented=" . $post_id . "#post-" . $post_id);
        exit();
    }
}

include 'includes/header.php';
?>

<style>
/* ── Blog Hero ── */
.blog-hero {
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--logo-teal) 100%);
    padding: 72px 0 52px;
    color: white;
    text-align: center;
}
.blog-hero h1 { font-weight: 900; }

/* ── Category filter pills ── */
.filter-bar {
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
    padding: 14px 0;
    position: sticky;
    top: 80px;
    z-index: 100;
}
.cat-pill {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 50px;
    padding: 6px 18px;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.22s;
    white-space: nowrap;
}
.cat-pill:hover, .cat-pill.active {
    background: var(--primary-green);
    border-color: var(--primary-green);
    color: white;
}

/* ── Post card grid ── */
.post-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: var(--shadow);
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
}
.post-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 45px rgba(0,0,0,0.12);
}
.post-card-img {
    position: relative;
    height: 220px;
    overflow: hidden;
    flex-shrink: 0;
}
.post-card-img img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
    display: block;
}
.post-card:hover .post-card-img img { transform: scale(1.05); }

.post-card-img .carousel { height: 220px; }
.post-card-img .carousel-item img { height: 220px; object-fit: cover; }

.cat-badge {
    position: absolute;
    top: 12px; left: 12px;
    background: var(--primary-green);
    color: white;
    border-radius: 50px;
    padding: 3px 12px;
    font-size: 0.73rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.post-card-body {
    padding: 22px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.post-card-meta { font-size: 0.8rem; color: var(--text-muted); margin-bottom: 8px; }
.post-card-title { font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin-bottom: 10px; line-height: 1.4; }
.post-card-excerpt { font-size: 0.88rem; color: var(--text-muted); line-height: 1.7; flex: 1; margin-bottom: 16px; }
.post-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 14px;
    border-top: 1px solid var(--border-color);
}
.read-btn {
    background: var(--primary-green);
    color: white;
    border: none;
    border-radius: 50px;
    padding: 6px 18px;
    font-size: 0.82rem;
    font-weight: 700;
    transition: background 0.22s;
}
.read-btn:hover { background: var(--logo-teal); color: white; }
.comment-count { font-size: 0.8rem; color: var(--text-muted); }

/* ── Post Modal ── */
#postModal .modal-dialog { max-width: 860px; }
#postModal .modal-content {
    background: var(--bg-card);
    color: var(--text-main);
    border: none;
    border-radius: 18px;
    overflow: hidden;
}
.modal-post-img {
    width: 100%; height: 340px;
    object-fit: cover;
    display: block;
}
.modal-post-img-carousel .carousel-item img {
    height: 340px;
    object-fit: cover;
}
.modal-post-body { padding: 32px; }
.modal-post-title { font-size: 1.6rem; font-weight: 900; margin-bottom: 8px; }
.modal-post-meta { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px; }
.modal-post-content { line-height: 1.9; color: var(--text-main); }

/* ── Comments ── */
.comment-box {
    background: var(--bg-secondary);
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 10px;
}
.comment-box:last-child { margin-bottom: 0; }
.comment-scroll { max-height: 260px; overflow-y: auto; }

.modal-form-control {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-main);
    padding: 10px 14px;
    width: 100%;
    font-size: 0.9rem;
    transition: border-color 0.2s;
}
.modal-form-control:focus {
    outline: none;
    border-color: var(--primary-green);
    box-shadow: 0 0 0 0.2rem rgba(25,135,84,0.18);
    background: var(--bg-secondary);
    color: var(--text-main);
}
body.dark-mode .modal-form-control {
    background: #2a2a2a;
    border-color: #404040;
    color: #e0e0e0;
}
.btn-comment-submit {
    background: var(--primary-green);
    color: white;
    border: none;
    border-radius: 50px;
    padding: 10px 24px;
    font-weight: 700;
    width: 100%;
    transition: background 0.22s;
}
.btn-comment-submit:hover { background: var(--logo-teal); }

/* ── Newsletter ── */
.newsletter-section {
    background: linear-gradient(135deg, var(--primary-green), var(--logo-teal));
    color: white;
    padding: 72px 0;
}
.nl-input-wrap {
    background: white;
    border-radius: 50px;
    padding: 6px 6px 6px 22px;
    display: flex;
    align-items: center;
    max-width: 500px;
    margin: 0 auto;
    box-shadow: 0 8px 28px rgba(0,0,0,0.16);
}
.nl-input-wrap input {
    flex: 1; border: none; background: transparent;
    outline: none; font-size: 0.95rem; color: #333; padding: 4px 0;
}
.nl-input-wrap button {
    background: var(--primary-green); color: white;
    border: none; border-radius: 50px;
    padding: 10px 22px; font-weight: 700;
    white-space: nowrap; transition: background 0.22s;
}
.nl-input-wrap button:hover { background: var(--logo-teal); }

/* ── No results ── */
.no-posts { text-align: center; padding: 60px 0; }
</style>

<!-- HERO -->
<section class="blog-hero">
    <div class="container">
        <span class="badge bg-white text-success fw-bold px-3 py-2 mb-3" style="font-size:0.8rem;letter-spacing:1px;">NEWS &amp; UPDATES</span>
        <h1 class="display-5 mb-2">Field Stories &amp; Updates</h1>
        <p class="lead" style="opacity:0.85;max-width:500px;margin:0 auto;">Reports from the ground, research findings, and community impact from across Kenya.</p>
    </div>
</section>

<!-- COMMENT SUCCESS TOAST -->
<?php if (isset($_GET['commented'])): ?>
<div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index:2000;max-width:380px;" role="alert">
    <i class="fas fa-check-circle me-2"></i><strong>Comment submitted!</strong> It's awaiting moderation.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php
/* ── Fetch all posts ── */
$posts_result = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
$all_posts    = [];
$categories   = ['All'];
while ($p = $posts_result->fetch_assoc()) {
    $all_posts[] = $p;
    if (!empty($p['category']) && !in_array($p['category'], $categories)) {
        $categories[] = $p['category'];
    }
}
?>

<!-- CATEGORY FILTER -->
<?php if (count($all_posts) > 0): ?>
<div class="filter-bar">
    <div class="container">
        <div class="d-flex gap-2 overflow-auto" style="scrollbar-width:none;">
            <?php foreach ($categories as $cat): ?>
            <button class="cat-pill <?php echo $cat === 'All' ? 'active' : ''; ?>"
                    onclick="filterPosts('<?php echo htmlspecialchars($cat); ?>', this)">
                <?php echo htmlspecialchars($cat); ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- POST GRID -->
<section class="py-5" style="background:var(--bg-body);">
    <div class="container">
        <?php if (count($all_posts) > 0): ?>
        <div class="row g-4" id="post-grid">
            <?php foreach ($all_posts as $row):
                $id          = $row['id'];
                $gallery     = json_decode($row['gallery_images'], true) ?? [];
                $has_gallery = !empty($gallery);
                $approved_count_q = $conn->prepare("SELECT COUNT(*) as c FROM comments WHERE post_id=? AND status='approved'");
                $approved_count_q->bind_param("i", $id);
                $approved_count_q->execute();
                $approved_count = $approved_count_q->get_result()->fetch_assoc()['c'];
                $main_image = !empty($row['image']) ? $row['image'] : 'assets/images/hero1.jpg';
            ?>
            <div class="col-lg-4 col-md-6 post-col"
                 data-category="<?php echo htmlspecialchars($row['category']); ?>"
                 id="post-<?php echo $id; ?>">
                <div class="post-card h-100"
                     onclick="openPost(<?php echo $id; ?>)"
                     role="button"
                     tabindex="0">
                    <div class="post-card-img">
                        <?php if ($has_gallery): ?>
                        <div id="thumb-carousel-<?php echo $id; ?>" class="carousel slide h-100" data-bs-ride="false">
                            <div class="carousel-inner h-100">
                                <div class="carousel-item active">
                                    <img src="<?php echo htmlspecialchars($main_image); ?>" alt="Post">
                                </div>
                                <?php foreach (array_slice($gallery, 0, 3) as $gi => $gimg): ?>
                                <div class="carousel-item">
                                    <img src="<?php echo htmlspecialchars($gimg); ?>" alt="Gallery">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <img src="<?php echo htmlspecialchars($main_image); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <?php endif; ?>
                        <span class="cat-badge"><?php echo htmlspecialchars($row['category']); ?></span>
                    </div>
                    <div class="post-card-body">
                        <div class="post-card-meta">
                            <i class="fas fa-calendar-alt text-success me-1"></i>
                            <?php echo date('F j, Y', strtotime($row['created_at'])); ?>
                        </div>
                        <h3 class="post-card-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="post-card-excerpt">
                            <?php echo htmlspecialchars(substr(strip_tags($row['content']), 0, 130)) . '…'; ?>
                        </p>
                        <div class="post-card-footer">
                            <button class="read-btn">Read More <i class="fas fa-arrow-right ms-1"></i></button>
                            <span class="comment-count">
                                <i class="fas fa-comment me-1"></i><?php echo $approved_count; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div id="no-results" class="no-posts d-none">
            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
            <p class="text-muted">No posts in this category yet.</p>
        </div>

        <?php else: ?>
        <div class="no-posts">
            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
            <h4>No posts yet</h4>
            <p class="text-muted">Check back soon for field reports and project updates.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- POST MODAL -->
<div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0 px-4 pt-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="modalBody">
                <!-- Injected by JS -->
                <div class="text-center py-5">
                    <div class="spinner-border text-success"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Post data for JS -->
<script>
const postsData = <?php
$out = [];
foreach ($all_posts as $p) {
    $g = json_decode($p['gallery_images'], true) ?? [];
    $out[] = [
        'id'       => (int)$p['id'],
        'title'    => htmlspecialchars($p['title'], ENT_QUOTES),
        'category' => htmlspecialchars($p['category'], ENT_QUOTES),
        'date'     => date('F j, Y', strtotime($p['created_at'])),
        'content'  => nl2br(htmlspecialchars($p['content'], ENT_QUOTES)),
        'image'    => htmlspecialchars(!empty($p['image']) ? $p['image'] : 'assets/images/hero1.jpg', ENT_QUOTES),
        'gallery'  => array_map('htmlspecialchars', $g),
    ];
}
echo json_encode($out);
?>;
</script>

<!-- NEWSLETTER -->
<section class="newsletter-section" id="newsletter">
    <div class="container text-center">
        <i class="fas fa-paper-plane fa-2x mb-3" style="opacity:0.7;"></i>
        <h2 class="fw-bold mb-2">Stay in the Loop</h2>
        <p class="lead mb-4" style="opacity:0.85;">Get field reports, project milestones, and community stories — straight to your inbox.</p>

        <?php if ($sub_msg): ?>
        <div class="alert alert-<?php echo $sub_status; ?> alert-dismissible fade show d-inline-block mb-4"
             style="min-width:320px;max-width:500px;" role="alert">
            <?php echo htmlspecialchars($sub_msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <form method="POST" action="blog.php#newsletter">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf; ?>">
            <div class="nl-input-wrap">
                <input type="email" name="email" placeholder="Your email address…" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <button type="submit" name="subscribe">Subscribe <i class="fas fa-paper-plane ms-1"></i></button>
            </div>
            <p class="small mt-3 mb-0" style="opacity:0.6;">No spam. Unsubscribe anytime.</p>
        </form>
    </div>
</section>

<script>
/* ── Category filter ── */
function filterPosts(cat, btn) {
    document.querySelectorAll('.cat-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    let visible = 0;
    document.querySelectorAll('.post-col').forEach(col => {
        const match = cat === 'All' || col.dataset.category === cat;
        col.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('no-results').classList.toggle('d-none', visible > 0);
}

/* ── Open post modal ── */
function openPost(id) {
    const post = postsData.find(p => p.id === id);
    if (!post) return;

    const modal = new bootstrap.Modal(document.getElementById('postModal'));

    /* Build gallery HTML */
    let imgHtml = '';
    const allImgs = [post.image, ...post.gallery].filter(Boolean);
    if (allImgs.length > 1) {
        const items = allImgs.map((src, i) =>
            `<div class="carousel-item ${i === 0 ? 'active' : ''}">
                <img src="${src}" class="d-block w-100 modal-post-img" alt="">
             </div>`
        ).join('');
        imgHtml = `
            <div id="modalCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">${items}</div>
                <button class="carousel-control-prev" type="button" data-bs-target="#modalCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#modalCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>`;
    } else {
        imgHtml = `<img src="${post.image}" class="modal-post-img" alt="${post.title}">`;
    }

    document.getElementById('modalBody').innerHTML = `
        ${imgHtml}
        <div class="modal-post-body">
            <span class="badge bg-success mb-2">${post.category}</span>
            <h2 class="modal-post-title">${post.title}</h2>
            <p class="modal-post-meta"><i class="fas fa-calendar-alt text-success me-1"></i>${post.date}</p>
            <div class="modal-post-content mb-4">${post.content}</div>
            <hr style="border-color:var(--border-color);">
            <div class="row g-4 mt-1">
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3"><i class="fas fa-comments text-success me-2"></i>Comments</h6>
                    <div id="commentsFor${id}" class="comment-scroll">
                        <p class="text-muted small text-center py-3">Loading comments…</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="fw-bold mb-3"><i class="fas fa-pen text-success me-2"></i>Leave a Reply</h6>
                    <form method="POST" action="blog.php#post-${id}">
                        <input type="hidden" name="post_id" value="${id}">
                        <input type="hidden" name="csrf_token" value="${document.querySelector('meta[name=csrf]')?.content || '<?php echo $csrf; ?>'}">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf; ?>">
                        <div class="mb-2">
                            <input type="text" name="name" class="modal-form-control" placeholder="Your name" required maxlength="100">
                        </div>
                        <div class="mb-3">
                            <textarea name="comment" class="modal-form-control" rows="4"
                                      placeholder="Share your thoughts…" required maxlength="1000"></textarea>
                        </div>
                        <button type="submit" name="submit_comment" class="btn-comment-submit">
                            Post Comment <small style="opacity:0.75;">(moderated)</small>
                        </button>
                    </form>
                </div>
            </div>
        </div>`;

    modal.show();
    loadComments(id);
}

/* ── Load comments via AJAX ── */
function loadComments(postId) {
    fetch(`assets/php/get_comments.php?post_id=${postId}`)
        .then(r => r.json())
        .then(comments => {
            const el = document.getElementById('commentsFor' + postId);
            if (!el) return;
            if (!comments.length) {
                el.innerHTML = '<p class="text-muted small text-center py-3">No comments yet — be the first!</p>';
                return;
            }
            el.innerHTML = comments.map(c => `
                <div class="comment-box">
                    <div class="d-flex justify-content-between mb-1">
                        <strong class="small">${c.name}</strong>
                        <small class="text-muted">${c.date}</small>
                    </div>
                    <p class="mb-0 small text-muted">${c.comment}</p>
                </div>`).join('');
        })
        .catch(() => {
            const el = document.getElementById('commentsFor' + postId);
            if (el) el.innerHTML = '<p class="text-muted small text-center py-2">Could not load comments.</p>';
        });
}
</script>

<?php include 'includes/footer.php'; ?>
