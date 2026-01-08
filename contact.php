<?php 
$page_title = "Contact Us"; 
include 'includes/header.php'; 
?>

<div class="container mt-5 pt-5">
    <div class="row text-center mb-5">
        <div class="section-title">
            <h2>Get in Touch</h2>
            <p>We would love to hear from you! Whether you have inquiries about our products, need support, or want to explore partnership opportunities, our team is here to assist you.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="contactcard h-100 d-flex flex-column justify-content-center">
                <h3 class="mb-4 fw-bold" style="color: var(--primary-green);">Contact Information</h3>
                <ul>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <span class="fw-bold d-block">Location</span>
                            <span>Bungoma, Kenya</span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <div>
                            <span class="fw-bold d-block">Email</span>
                            <a href="mailto:info@biocharpamoja.co.ke" style="color: inherit;">info@biocharpamoja.co.ke</a>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <div>
                            <span class="fw-bold d-block">Phone</span>
                            <a href="tel:+254723545858" style="color: inherit;">+254 723545858</a>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-box"></i>
                        <div>
                            <span class="fw-bold d-block">Postal Address</span>
                            <span>P.O Box 1157 - 50200</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-7 contactcard">
            <div class="card border-0 shadow-sm p-4 contactcard">
                
                <?php 
                if (isset($_GET['status'])) {
                    if ($_GET['status'] == 'success') {
                        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> Your message has been sent. We will get back to you soon.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
                    } elseif ($_GET['status'] == 'error') {
                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> Something went wrong sending your message. Please try again or email us directly.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
                    } elseif ($_GET['status'] == 'invalid') {
                        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Invalid Input!</strong> Please check your fields and try again.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
                    }
                }
                ?>
                <form action="assets/php/process-contact.php" method="POST">
                    <div class="row g-3 contactcard">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="Firstname" id="firstName" placeholder="First Name" required>
                                <label for="firstName">First Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="Email" id="emailAddr" placeholder="Email" required>
                                <label for="emailAddr">Email Address</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" name="Message" id="messageBox" placeholder="Message" style="height: 150px;" required></textarea>
                                <label for="messageBox">Your Message</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success btn-lg w-100" type="submit">Send Message <i class="fas fa-paper-plane ms-2"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>