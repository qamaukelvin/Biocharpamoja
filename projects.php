<?php 
$page_title = "Our Projects & Technologies"; 
include 'includes/header.php'; 
?>

<div class="container mt-5 pt-5">
    <div class="row text-center mb-5">
        <div class="section-title">
            <h2>Our Projects</h2>
            <p>Every project is designed to combine technology, community training, and measurable outcomes. These initiatives not only strengthen farming systems in Kenya but also deliver verified climate solutions that inspire replication worldwide.</p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12 d-flex justify-content-center">
            <div class="btn-group" role="group" aria-label="Project Filters">
                <button type="button" class="btn btn-outline-success active filter-btn" data-filter="all">All</button>
                <button type="button" class="btn btn-outline-success filter-btn" data-filter="project">Projects</button>
                <button type="button" class="btn btn-outline-success filter-btn" data-filter="tech">Technologies</button>
            </div>
        </div>
    </div>

    <div id="portfolio-grid">
        
        <div class="card mb-5 border-0 shadow portfolio-item project overflow-hidden">
            <div class="row g-0 align-items-center">
                <div class="col-lg-6">
                    <div id="carouselBungoma" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="assets/images/farm.jpeg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Bungoma Farm">
                            </div>
                             <div class="carousel-item">
                                <img src="assets/images/biocharfarm.jpeg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Training">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselBungoma" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselBungoma" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="card-title fw-bold mb-0">Biochar Production - Bungoma</h3>
                            <span class="badge bg-success">Project</span>
                        </div>
                        <p class="text-muted mb-3">2022–Present</p>
                        <p class="card-text lead">Deployed RoCC and Panel Kilns to farmers fields to turn agricultural waste into biochar.</p>
                        
                        <div class="d-grid gap-2 d-md-block">
                             <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#flush-bungoma" >
                                Read Full Details <i class="fas fa-chevron-down ms-1"></i>
                            </button>
                            <a href="assets/documents/Biochar-Corn-Full-Report-2022-10-14.pdf" class="btn btn-success text-white"><i class="fas fa-download"></i> Report</a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="accordion accordion-flush" id="accordionBungoma">
                        <div id="flush-bungoma" class="accordion-collapse collapse bg-light" data-bs-parent="#accordionBungoma">
                            <div class="accordion-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-success">Activities</h5>
                                        <ul>
                                            <li>Deployed RoCC and Panel Kilns to farmers fields to turn agricultural waste into biochar.</li>
                                            <li>Conducted the Bungoma Maize Experiment (2022) comparing biochar-treated vs untreated plots.</li>
                                            <li>Trained 1,000+ farmers on site, farmers groups and exhibitions on biochar production operations and application for soil remediation and management.</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-success">Results</h5>
                                        <ul>
                                            <li>Farmers saw 20–30% higher maize yields in biochar plots.</li>
                                            <li>Biochar improved water retention, helping crops withstand drought.</li>
                                            <li>Fertilizer use decreased, lowering farming costs.</li>
                                            <li>Improved soil health.</li>
                                        </ul>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <h5 class="fw-bold text-success">Impact</h5>
                                        <ul>
                                            <li>435+ tonnes of crop residues converted into biochar.</li>
                                            <li>87 tonnes of biochar documented between 2021–2025.</li>
                                            <li>Onboarding 170+ tCO₂e on CharTrac dMRV.</li>
                                            <li>Demonstrated that smallholder farming can unlock carbon credit revenue streams.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-5 border-0 shadow portfolio-item tech overflow-hidden">
            <div class="row g-0 align-items-center">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="card-body p-4 p-lg-5">
                         <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="card-title fw-bold mb-0">The Panel Kiln</h3>
                            <span class="badge bg-warning text-dark">Technology</span>
                        </div>
                        <p class="card-text lead">The innovation is a set of essentially flat (but could be corrugated or shaped for strength) wall segments called panels that can be transported flat and then relatively easily positioned to create a six-sided structure to be a pyrolysis kiln.</p>
                        
                        <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#flush-panel" >
                            Read Technical Specs <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2">
                    <div id="carouselPanel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="assets/images/panelkiln3.jpeg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Panel Kiln">
                            </div>
                            <div class="carousel-item">
                                <img src="assets/images/panelkiln2.jpeg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Panel Kiln 2">
                            </div>
                        </div>
                         <button class="carousel-control-prev" type="button" data-bs-target="#carouselPanel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselPanel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
                <div class="col-12 order-3">
                    <div class="accordion accordion-flush" id="accordionPanel">
                        <div id="flush-panel" class="accordion-collapse collapse bg-light" data-bs-parent="#accordionPanel">
                            <div class="accordion-body p-4">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                         <p>Panel kilns can operate either with Flame Cap (FC) pyrolysis (with no intentional air entering the lower half of the created cavity) or with some adjustments as Top-Lit UpDraft (TLUD) pyrolysis (with intentional air entering at the bottom of the cavity).</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-success">How It Works</h5>
                                        <p>Entry of the biomass feedstock is from the top, as delivered by any method for handling biomass.</p>
                                        <p>Feedstock shape will impact the delivery: long straight stalks, reeds, bamboo, etc. can be dropped along the full length of the kiln.</p>
                                        <p>Short chunky can be dispersed by “sprinkling” or spreading. Small biomass such as pits of fruit and probably corn cobs might be delivered by gravity from elevated hoppers; etc.</p>
                                        <p>Residues (maize stalks, husks, sugarcane trash, woody shrubs) are layered and burned under controlled low-oxygen conditions. Incorporates sequential and moderated addition of dry biomass in layers with pyrolytic radiant heat from above and accumulation of created biochar in the lower cavity where minimal air is to be entering. Smoke is consumed by the flame curtain, producing biochar instead of ash.</p>
                                    </div>
                                    <div class="col-md-6">
                                         <h5 class="fw-bold text-success">Benefits</h5>
                                        <ol>
                                            <li>Operation in batch or cumulative batch mode (addition of biomass input during operations) as Flame Cap or TLUD pyrolysis.</li>
                                            <li>Produce larger quantities of biochar with larger devices.</li>
                                            <li>Have faster operations for loading the biomass and unloading the biochar.</li>
                                            <li>Accept biomass types and shapes/sizes not currently served.</li>
                                            <li>Offer mobility and/or portability of the kilns.</li>
                                            <li>Maintain or improve the quality of emissions and efficiencies.</li>
                                            <li>Have significantly lower capital and operational costs per unit of biochar production.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-5 border-0 shadow portfolio-item project overflow-hidden">
            <div class="row g-0 align-items-center">
                 <div class="col-lg-6">
                    <div id="carouselRoccPilot" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="assets/images/kilnsrocc.jpeg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="RoCC Pilot">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="card-title fw-bold mb-0">Kisii & Nyakach RoCC Kiln Pilots</h3>
                            <span class="badge bg-success">Project</span>
                        </div>
                        <p class="text-muted mb-3">2021</p>
                        <p class="card-text lead">Fabricated the first two RoCC kilns at Kisii University workshops and tested with multiple feedstocks.</p>
                        
                        <div class="d-grid gap-2 d-md-block">
                             <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#flush-kisii" >
                                Read Full Details <i class="fas fa-chevron-down ms-1"></i>
                            </button>
                            <a href="assets/documents/RoCC-Kilns-and-Biochar-in-Kenya-2022-02-24.pdf" class="btn btn-success text-white"><i class="fas fa-download"></i> Report</a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="accordion accordion-flush" id="accordionKisii">
                        <div id="flush-kisii" class="accordion-collapse collapse bg-light" data-bs-parent="#accordionKisii">
                            <div class="accordion-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-success">Activities</h5>
                                        <ul>
                                            <li>Fabricated the first two RoCC kilns at Kisii University workshops.</li>
                                            <li>Tested with multiple feedstocks: soybean residues, rice straw, Acacia shrubs.</li>
                                            <li>Collaborated with Kisii University, Kenya Marine and Fisheries Research Institute (KEMFRI), and Ahero Irrigation Scheme.</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-success">Results & Impact</h5>
                                        <ul>
                                            <li>Daily outputs ranged from 8–40 kg of biochar depending on feedstock.</li>
                                            <li>Efficient carbonization with reduced smoke and emissions.</li>
                                            <li>Proved potential for community-scale kilns.</li>
                                            <li>Lessons learned informed the design of the Panel Kiln, which is easier to transport and operate for farmer groups.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-5 border-0 shadow portfolio-item tech overflow-hidden">
            <div class="row g-0 align-items-center">
                 <div class="col-lg-6 order-2 order-lg-1">
                    <div class="card-body p-4 p-lg-5">
                         <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="card-title fw-bold mb-0">RoCC Kiln</h3>
                            <span class="badge bg-warning text-dark">Technology</span>
                        </div>
                        <p class="card-text lead">A RoCC kiln, short for Rotatable Covered Cavity kiln, is a type of pyrolysis kiln used for biochar production and thermal energy generation. It features a covered cavity with an open top design where pyrolysis of biomass occurs under a cap of flames.</p>
                        
                        <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#flush-rocc" >
                            Read Technical Specs <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                    </div>
                </div>
                <div class="col-lg-6 order-1 order-lg-2">
                    <div id="carouselRoccTech" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="assets/images/rocckiln.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="RoCC Tech">
                            </div>
                             <div class="carousel-item">
                                <img src="assets/images/char.jpeg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Char">
                            </div>
                        </div>
                         <button class="carousel-control-prev" type="button" data-bs-target="#carouselRoccTech" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselRoccTech" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
                <div class="col-12 order-3">
                    <div class="accordion accordion-flush" id="accordionRocc">
                        <div id="flush-rocc" class="accordion-collapse collapse bg-light" data-bs-parent="#accordionRocc">
                            <div class="accordion-body p-4">
                                <div class="mb-4">
                                    <h5 class="fw-bold text-success">Description</h5>
                                    <p>The kiln is rotatable on demand, allowing mixing of biomass inside to ensure complete pyrolysis and to facilitate easy emptying of char. Its covered design protects the flame from wind and rain, retains heat longer, and allows directed use of created heat via chimneys. The rotatable feature also helps automate and scale up production by mechanically mixing the biomass and char inside the kiln. RoCC kilns are available in various sizes and are patented technology, with applications in biochar production, carbon sequestration, and providing thermal energy while using biomass efficiently.</p>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-success">How It Works</h5>
                                        <p>The RoCC kiln works by using a rotatable, covered cavity design to facilitate pyrolysis of biomass into charcoal or biochar. The kiln is a fire-resistant container that is mostly enclosed except for a portal through which air, fuel, and emissions flow. It is supported on wheels or an axle that allows it to be rotated along its longitudinal axis when desired.</p>
                                        <p>Rotation serves several key purposes: it mixes and tumbles the biomass and partially pyrolyzed material inside, exposing it evenly to heat and breaking apart charcoal clumps. This improves the completeness and efficiency of the pyrolysis process.</p>
                                        <p>The process is initiated by loading biomass and a small amount of charcoal to ensure ignition. Once the fire is stable, the kiln is slowly rotated to the operating position to maintain an even fire. As pyrolysis progresses, the kiln is rotated back and forth to mix contents and enhance pyrolysis coverage. Char can be removed through the portal without fully stopping the process.</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-success">Benefits</h5>
                                        <ul>
                                            <li><strong>Protected flame:</strong> The covered cavity design mostly shields the flame from wind and rain, which improves flame stability and combustion efficiency.</li>
                                            <li><strong>Longer heat retention:</strong> Enhancing pyrolysis effectiveness.</li>
                                            <li><strong>Directed heat use:</strong> The created heat can be channeled for other uses via chimneys.</li>
                                            <li><strong>Rotatable for mixing:</strong> Ensures complete pyrolysis.</li>
                                            <li><strong>Easier char removal:</strong> Rotation facilitates emptying the biochar.</li>
                                            <li><strong>Worker safety:</strong> Partial shielding from radiant heat.</li>
                                            <li><strong>Good biochar quality:</strong> Produces biochar typically above 550°C.</li>
                                        </ul>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <h5 class="fw-bold text-success">Climate Role</h5>
                                        <ul>
                                            <li><strong>Carbon Sequestration:</strong> One 1-barrel RoCC kiln produces about 50 kg of biochar in 10 hours (~125 kg CO2-equivalent).</li>
                                            <li><strong>Reduced Emissions:</strong> Covered design improves combustion efficiency, reducing methane.</li>
                                            <li><strong>Renewable Energy:</strong> Replaces fossil fuels for heating/hot water.</li>
                                            <li><strong>Climate Smart Agriculture:</strong> Biochar enhances soil health and water retention.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-5 border-0 shadow portfolio-item project overflow-hidden">
            <div class="row g-0 align-items-center">
                 <div class="col-lg-6">
                    <div id="carouselStoves" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 400px;">
                                    <video src="assets/images/VID-20250915-WA0015.mp4" controls style="max-height: 100%; max-width: 100%;" autoplay muted loop></video>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="card-title fw-bold mb-0">Clean Cookstoves</h3>
                            <span class="badge bg-success">Project</span>
                        </div>
                        <p class="card-text lead">Millions of rural households in Kenya still rely on smoky open fires or charcoal, leading to deforestation and health risks. To reduce deforestation, improve household air quality and promote climate action on the community level we introduced clean cookstoves in 2023.</p>
                        <p class="small text-muted">We began with PowerPellet TLUD-ND stoves which run on pellets. Pelletjiko stoves came in later.</p>
                        
                        <div class="d-grid gap-2 d-md-block">
                             <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#flush-stoves" >
                                Read Full Details <i class="fas fa-chevron-down ms-1"></i>
                            </button>
                            <a href="assets/documents/PowerPellet TLUD ETHOS 2024-01-26 (1).pdf" class="btn btn-success text-white"><i class="fas fa-download"></i> Report</a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="accordion accordion-flush" id="accordionStoves">
                        <div id="flush-stoves" class="accordion-collapse collapse bg-light" data-bs-parent="#accordionStoves">
                            <div class="accordion-body p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-success">Activities</h5>
                                        <ul>
                                            <li>Deployed 300 cookstoves to date in partnership with the Rotary Club of Bungoma Magharibi and the Sunset Rotary Club of Bloomington-Normal.</li>
                                            <li>Worked with 10 Women Self-help groups (WSGs) to own, operate and manage cookstoves and distribute pellets.</li>
                                            <li>Provided training on cookstove use, management, biochar collection and safety.</li>
                                        </ul>
                                        <h5 class="fw-bold text-success mt-3">Project Target</h5>
                                        <ul>
                                            <li>800 stoves by 2025.</li>
                                            <li>Unlocking ~$7,500/year from carbon credits for sustainability.</li>
                                            <li>Circular economy solution for clean cooking, energy access and biochar production.</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="fw-bold text-success">Results</h5>
                                        <ul>
                                            <li>They are more fuel-efficient, leading to substantial savings in fuel costs, time reduction on firewood collection.</li>
                                            <li>The significant reduction in particulate emissions lowers the risk of respiratory diseases.</li>
                                            <li>The use of pellets efficiently reduces firewood consumption, helping conserve forests.</li>
                                            <li>Adoption of clean cookstoves empowers women by reducing their labor burden.</li>
                                            <li>Women’s groups earn 10% margin on pellet distribution.</li>
                                            <li>On average each stove produces ~100 kg biochar/year = 220 kg CO₂e removed annually.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> 
</div>

<script>
    // FILTER LOGIC
    const filterBtns = document.querySelectorAll('.filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filterValue = btn.getAttribute('data-filter');

            portfolioItems.forEach(item => {
                if (filterValue === 'all' || item.classList.contains(filterValue)) {
                    item.style.display = 'block'; 
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>

<?php 
include 'includes/footer.php'; 
?>