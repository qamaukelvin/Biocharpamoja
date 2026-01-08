<?php 
$page_title = "Biochar Pamoja - Home";
include 'includes/header.php'; 
?>
    
<main>
    <section id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 introduction order-2 order-lg-1">
                    <div class="catchphrase mb-4">
                        <h2 class="fw-bold display-4">WE FIX <span style="color: var(--primary-green);">CARBON</span></h2>
                    </div>
                    <p class="lead text-muted mb-4">We are revolutionizing biochar production and application with cutting-edge RoCC kiln, TLUD, and Panel kiln technologies. Our mission is to provide low-cost, sustainable solutions for biochar production, improving soil health, reducing carbon footprints, and creating economic opportunities for communities in Kenya and beyond.</p>
                    <div class="buttons d-flex gap-3">
                        <a href="projects.php" class="btn btn-success btn-lg rounded-pill px-4">Our Projects <i class="fas fa-arrow-right ms-2"></i></a>
                        <a href="contact.php" class="btn btn-outline-success btn-lg rounded-pill px-4">Get In Touch</a>
                    </div>
                </div>
                
                <div class="col-lg-7 heroimage order-1 order-lg-2 mb-5 mb-lg-0">
                    <div class="carousel slide carousel-fade shadow-lg rounded-4 overflow-hidden" id="heroCarousel" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="assets/images/hero2.jpg" alt="Kilns" class="hero-carousel-img">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/images/biocharfarm.jpeg" alt="Farm" class="hero-carousel-img">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/images/hero1.jpg" alt="College Farm" class="hero-carousel-img">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/images/hero3.jpg" alt="College Farm" class="hero-carousel-img">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/images/kilnsrocc.jpeg" alt="College Farm" class="hero-carousel-img">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="stats">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <div class="col-6 col-md-3 stat-item">
                    <i class="fas fa-calendar-alt"></i>
                    <h2>5+</h2>
                    <p>Years Experience</p>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <i class="fas fa-leaf"></i>
                    <h2>170+</h2>
                    <p>Tonnes CO₂e Removed</p>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <i class="fas fa-users"></i>
                    <h2>1000+</h2>
                    <p>Farmers Trained</p>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <i class="fas fa-handshake"></i>
                    <h2>5+</h2>
                    <p>Women’s Groups</p>
                </div>
            </div>
        </div>
    </section>

    <section id="projects" class="py-5">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2>Featured Projects</h2>
                <p>Explore some of our recent projects and see how we combine technology, community training, and measurable outcomes.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm project-card">
                        <img src="assets/images/news-kiln.PNG" class="project-card-img card-img-top" alt="RoCC Kiln">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Kisii & Nyakach Pilots</h5>
                            <p class="card-text text-muted small">In collaboration with Kisii University and KMFRI, testing the first RoCC Kilns fabricated in Kenya.</p>
                            <a href="projects.php" class="btn btn-outline-success btn-sm mt-3">Read More</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm project-card">
                        <img src="assets/images/tlud.jpg" class="project-card-img card-img-top" alt="TLUD">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">PowerPellet Cookstoves</h5>
                            <p class="card-text text-muted small">Clean cooking stoves that also produce biochar and electricity, empowering women’s groups in Bungoma.</p>
                            <a href="projects.php" class="btn btn-outline-success btn-sm mt-3">Read More</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm project-card">
                        <img src="assets/images/biocharfarm.jpeg" class="project-card-img card-img-top" alt="Bungoma Farm">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Bungoma Biochar Farming</h5>
                            <p class="card-text text-muted small">Training 1000+ farmers to turn crop residues into biochar, boosting maize yields by 20–30%.</p>
                            <a href="projects.php" class="btn btn-outline-success btn-sm mt-3">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5" >
                <div class="col text-center" style="background-color: var(--bg-body);">
                    <a href="projects.php" class="btn btn-success btn-lg rounded-pill px-5">View All Projects <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>