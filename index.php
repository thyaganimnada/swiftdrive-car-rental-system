<?php include_once "header.php"; ?>

<section id="home" class="hero-section" id="heroSection">
    <video autoplay loop muted playsinline class="hero-video-bg">
        <source src="assets/hero.mp4" type="video/mp4">
    </video>

    <div class="hero-overlay"></div>
    <div class="container hero-content">

        <!-- Hero Text -->
        <div class="text-center mb-5">
            <h1 class="hero-title display-4">Drive Your Journey with <span class="text-primary">SwiftDrive</span></h1>
            <p class="hero-subtitle">Premium vehicles for short and long-term rentals. Book instantly.</p>
        </div>

        <!-- Search Card -->
        <div class="hero-search-card mx-auto" id="searchBarContainer" style="max-width: 1100px;">
            <form action="vehicles.php" method="GET" id="heroSearchForm">
                <div class="row g-2 align-items-stretch">

                    <!-- Location -->
                    <div class="col-lg-4 col-md-12 search-divider">
                        <div class="search-field-group">
                            <i class="fa-solid fa-location-dot"></i>
                            <div class="w-100 text-start">
                                <label class="search-field-label" for="heroLocation">Pickup Location</label>
                                <select class="search-field-input" id="heroLocation" name="location">
                                    <option value="Negombo" selected>Negombo (Main Branch)</option>
                                    <option value="Bandaranaike Intl Airport">Bandaranaike Intl Airport (CMB)</option>
                                    <option value="Colombo City Hub">Colombo City Hub</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Pickup Date -->
                    <div class="col-lg-3 col-md-6 search-divider">
                        <div class="search-field-group">
                            <i class="fa-regular fa-calendar-days"></i>
                            <div class="w-100 text-start">
                                <label class="search-field-label" for="heroPickupDate">Pickup Date</label>
                                <input type="date" class="search-field-input" id="heroPickupDate" name="pickup_date" required>
                            </div>
                        </div>
                    </div>

                    <!-- Return Date -->
                    <div class="col-lg-3 col-md-6">
                        <div class="search-field-group">
                            <i class="fa-regular fa-calendar-check"></i>
                            <div class="w-100 text-start">
                                <label class="search-field-label" for="heroReturnDate">Return Date</label>
                                <input type="date" class="search-field-input" id="heroReturnDate" name="return_date" required>
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="col-lg-2 col-md-12 mt-3 mt-lg-0">
                        <button type="submit" class="btn btn-hero-search w-100 text-uppercase" id="btnHeroSearch">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<section id="vehicle" class="py-5 fleet-section" id="fleetCategoriesSection">
    <div class="container py-5">

        <!-- Header -->
        <div class="text-center mb-5">
            <h2 class="section-title">OUR VEHICLE FLEET</h2>
            <p class="section-desc">Explore our diverse selection of well-maintained vehicles, carefully curated to provide the perfect ride for every journey.</p>
        </div>

        <div class="row g-4">

            <!-- Economy Category -->
            <div class="col-md-6 col-lg-3">
                <div class="category-card">
                    <div class="category-img-wrapper">
                        <img src="assets/toyota-aqua.jpg" alt="Economy Hatchback" class="category-img">
                    </div>
                    <div class="category-content">
                        <h3 class="category-title"><i class="fa-solid fa-car-side"></i> Economy</h3>
                        <p class="category-desc">Compact, fuel-efficient hatchbacks ideal for city travel, daily errands, and couple trips.</p>
                        <a href="vehicles.php?type=Economy" class="btn-swift-outline w-100 mt-auto">View Cars</a>
                    </div>
                </div>
            </div>

            <!-- SUV Category -->
            <div class="col-md-6 col-lg-3">
                <div class="category-card">
                    <div class="category-img-wrapper">
                        <img src="assets/toyota-rav4.jpg" alt="Comfortable SUV" class="category-img">
                    </div>
                    <div class="category-content">
                        <h3 class="category-title"><i class="fa-solid fa-car-rear"></i> Comfortable</h3>
                        <p class="category-desc">Rugged yet refined SUVs offering great ground clearance and scenic comfort for long trips.</p>
                        <a href="vehicles.php?type=SUV" class="btn-swift-outline w-100 mt-auto">View Cars</a>
                    </div>
                </div>
            </div>

            <!-- Van Category -->
            <div class="col-md-6 col-lg-3">
                <div class="category-card">
                    <div class="category-img-wrapper">
                        <img src="assets/toyota-hiace.jpg" alt="Family Van" class="category-img">
                    </div>
                    <div class="category-content">
                        <h3 class="category-title"><i class="fa-solid fa-van-shuttle"></i> Family</h3>
                        <p class="category-desc">Spacious high-roof vans providing maximum comfort with ample room for groups and luggage.</p>
                        <a href="vehicles.php?type=Van" class="btn-swift-outline w-100 mt-auto">View Cars</a>
                    </div>
                </div>
            </div>

            <!-- Luxury Category -->
            <div class="col-md-6 col-lg-3">
                <div class="category-card">
                    <div class="category-img-wrapper">
                        <img src="assets/mercedes-s-class.jpg" alt="Executive Luxury" class="category-img">
                    </div>
                    <div class="category-content">
                        <h3 class="category-title"><i class="fa-solid fa-gem"></i> Executive</h3>
                        <p class="category-desc">Premium European sedans engineered for luxury events, weddings, and VIP corporate transit.</p>
                        <a href="vehicles.php?type=Luxury" class="btn-swift-outline w-100 mt-auto">View Cars</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Action Button -->
        <div class="text-center mt-5 pt-3">
            <a href="vehicles.php" class="btn-swift-primary">
                <i class="fa-solid fa-list-check me-2"></i> Browse Complete Fleet
            </a>
        </div>
    </div>
</section>

<section id="about" class="py-5 about-section" id="aboutUsSection">
    <div class="container py-4">
        <div class="row gx-5 align-items-center justify-content-between">

            <!-- Text Content -->
            <div class="col-lg-5 order-2 order-lg-1 mt-5 mt-lg-0">
                <div class="pe-lg-4">
                    <span class="about-subtitle">Our Story</span>
                    <h2 class="display-5 about-title">Driving Your Journey with Excellence</h2>

                    <p class="about-desc-lead">
                        Based in the vibrant coastal city of Negombo, SwiftDrive Rentals was established with a clear vision: to redefine the car rental experience through seamless digital innovation.
                    </p>

                    <p class="about-desc">
                        Whether you need a fuel-efficient economy car for city errands, a rugged SUV for a cross-country adventure, a spacious family van, or a premium luxury sedan for a corporate event, our meticulously maintained fleet is tailored for your journey. We believe renting a vehicle should be as effortless as the drive itself. With our modern digital platform, transparent terms, and dedicated local support, we ensure your next road trip is safe, comfortable, and unforgettable.
                    </p>

                    <div class="mt-4 pt-2">
                        <a href="vehicles.php" class="btn btn-swift-primary px-4 py-2 fw-bold shadow-sm rounded-pill" style="background-color: #0d6efd; color: white; border: none;">
                            Explore Our Fleet <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Image Grid -->
            <div class="col-lg-6 offset-lg-1 order-1 order-lg-2">
                <div class="row gx-3">

                    <!-- Left Column of Images -->
                    <div class="col-6">
                        <div class="about-img-wrapper">
                            <img class="about-img" src="assets/ab1.jpg" alt="SUV on a road trip">
                        </div>
                        <div class="about-img-wrapper">
                            <img class="about-img" src="assets/ab2.jpg" alt="Handing over car keys">
                        </div>
                    </div>

                    <!-- Right Column of Images (Staggered up for modern look) -->
                    <div class="col-6 stagger-up">
                        <div class="about-img-wrapper">
                            <img class="about-img" src="assets/ab3.jpg" alt="Luxury car driving">
                        </div>
                        <div class="about-img-wrapper">
                            <img class="about-img" src="assets/ab4.jpg" alt="Scenic driving route">
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<section class="py-5 why-choose-section" id="whyChooseUsSection">
    <div class="container py-5">

        <!-- Header -->
        <div class="text-center section-header">
            <h2 class="section-title">Why Choose SwiftDrive?</h2>
            <p class="section-desc">Experience effortless car rentals with transparent terms, premium vehicles, and dedicated local support.</p>
        </div>

        <!-- Feature Cards Row -->
        <div class="row g-4 text-center">

            <!-- 1. Easy Booking -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h4 class="feature-title">Instant & Easy Booking</h4>
                    <p class="feature-desc">Seamless online reservations in under 2 minutes with instant availability checks and flexible pickup locations across the country.</p>
                </div>
            </div>

            <!-- 2. Reliable Vehicles -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h4 class="feature-title">Reliable & Safe Vehicles</h4>
                    <p class="feature-desc">Every vehicle in our fleet undergoes rigorous 50-point mechanical safety checks and deep sanitization before every single handover.</p>
                </div>
            </div>

            <!-- 3. Flexible Rentals -->
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fa-regular fa-calendar-days"></i>
                    </div>
                    <h4 class="feature-title">Flexible Rental Plans</h4>
                    <p class="feature-desc">Whether you need short-term daily hires, discounted weekly packages, or customized long-term corporate leases, we have you covered.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<?php
require_once "db/db.php";
$blog_sql = "SELECT * FROM blogs WHERE status = 'Approved' ORDER BY created_at DESC LIMIT 3";
$blog_result = mysqli_query($conn, $blog_sql);
?>

<section class="py-5 blog-section" id="blog" style="background-color: #f8fafc;">
    <div class="container py-5">

        <!-- Section Header -->
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: #0f172a;">Travel Guides & Tips</h2>
            <p class="text-muted" style="max-width: 600px; margin: 0 auto;">Discover the best road trip routes, essential driving tips, and top destinations to make your Sri Lankan journey unforgettable.</p>
        </div>

        <div class="row g-4">
            
            <?php if(mysqli_num_rows($blog_result) > 0): ?>
                <?php while($blog = mysqli_fetch_assoc($blog_result)): ?>
                <div class="col-md-6 col-lg-4">
                    <article class="card h-100 border-0 shadow-sm" style="border-radius: 20px; overflow: hidden; transition: 0.3s;">
                        <div class="position-relative">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                <?php echo htmlspecialchars($blog['category']); ?>
                            </span>
                            
                            <!-- Render blog featured image with fallback handler -->
                            <img src="<?php echo htmlspecialchars($blog['image_url']); ?>" alt="Blog Image" class="w-100" style="height: 220px; object-fit: cover;" onerror="this.src='assets/images/default.jpg';">
                            
                        </div>
                        <div class="card-body p-4">
                            <div class="text-muted small fw-semibold mb-3 d-flex gap-3">
                                <span><i class="fa-regular fa-calendar text-primary me-1"></i> <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                                <span><i class="fa-regular fa-clock text-primary me-1"></i> <?php echo $blog['read_time']; ?> Min Read</span>
                            </div>
                            <h5 class="fw-bold mb-3" style="color: #0f172a; line-height: 1.4;">
                                <?php echo htmlspecialchars($blog['title']); ?>
                            </h5>
                            <p class="text-muted small mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                <?php echo htmlspecialchars($blog['excerpt']); ?>
                            </p>
                            
                            <!-- Link to complete blog article view -->
                            <a href="blog_view.php?id=<?php echo $blog['id']; ?>" class="fw-bold text-decoration-none mt-auto d-inline-block" style="color: #0d6efd;">
                                Read Full Guide <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </article>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-4">
                    <p>No blog posts have been approved yet. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- View All Button -->
        <div class="text-center mt-5">
            <a href="blog.php" class="btn px-4 py-2 fw-bold" style="border: 2px solid #0d6efd; color: #0d6efd; border-radius: 12px; transition: 0.3s;" onmouseover="this.style.backgroundColor='#0d6efd'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#0d6efd';">
                View All Articles <i class="fa-solid fa-book-open ms-2"></i>
            </a>
        </div>

    </div>
</section>

<?php
// Include database connection
include_once "db/db.php"; 

// Query top 3 approved customer reviews from database
$reviewsList = [];
$sql = "SELECT customer_name, rating, comment FROM reviews WHERE status = 'Approved' ORDER BY created_at DESC LIMIT 3;";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($resultData)) {
        $reviewsList[] = $row;
    }
    mysqli_stmt_close($stmt);
}
?>

<!-- Render customer reviews section if approved reviews exist -->
<?php if (!empty($reviewsList)): ?>
<section class="py-5 reviews-section" id="customerReviewsSection">
    <div class="container py-5">
        
        <!-- Header -->
        <div class="text-center section-header">
            <h2 class="section-title">What Our Customers Say</h2>
            <p class="section-desc">See what our valued local and international clients have to say about their SwiftDrive experience.</p>
        </div>

        <!-- Reviews Row -->
        <div class="row g-4 mt-2">
            <?php foreach ($reviewsList as $rev): ?>
                <div class="col-md-4">
                    <div class="review-card-modern">
                        
                        <i class="fa-solid fa-quote-right quote-watermark"></i>
                        
                        <!-- Stars -->
                        <div class="review-stars">
                            <?php for ($i = 0; $i < (int)($rev['rating']); $i++): ?>
                                <i class="fa-solid fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        
                        <!-- Review Comment -->
                        <p class="review-text-modern">"<?php echo htmlspecialchars($rev['comment']); ?>"</p>
                        
                        <!-- Customer Details (Avatar + Name) -->
                        <div class="d-flex align-items-center gap-3 mt-auto">
                            <!-- Initials Avatar -->
                            <div class="reviewer-avatar">
                                <?php
                                $words = explode(" ", $rev['customer_name']);
                                $initials = "";
                                foreach ($words as $w) {
                                    if (!empty($w)) {
                                        $initials .= $w[0];
                                    }
                                }
                                echo strtoupper(substr($initials, 0, 2));
                                ?>
                            </div>
                            
                            <div class="reviewer-info">
                                <h6><?php echo htmlspecialchars($rev['customer_name']); ?></h6>
                                <span class="verified-tag">
                                    <i class="fa-solid fa-circle-check"></i> Verified Customer
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Contact & Inquiry Section -->
<section class="py-5 contact-section" id="contact">
    <div class="container py-4">

        <div class="row g-5">
            <!-- Left: Inquiry Form -->
            <div class="col-lg-7">
                <div class="card p-4 p-md-5 inquiry-card h-100">
                    <div class="mb-4">
                        <h2 class="h3 fw-bold text-dark mb-2">Send an Inquiry</h2>
                        <p class="text-muted small">Need a specific vehicle or planning a long trip? Send us a message and our team will get back to you within 30 minutes.</p>
                    </div>
                    
                    <!-- Display inquiry submission status alerts -->
                    <?php if (isset($_GET['success']) && $_GET['success'] == "inquirysent"): ?>
                        <div class="alert alert-success fw-bold small">
                            <i class="fa-solid fa-circle-check me-2"></i> Thank you! Your inquiry has been sent successfully. We will contact you soon.
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['error']) && $_GET['error'] == "stmtfailed"): ?>
                        <div class="alert alert-danger fw-bold small">
                            <i class="fa-solid fa-circle-exclamation me-2"></i> Something went wrong. Please try again.
                        </div>
                    <?php endif; ?>

                    <form action="actions/inquiry_action.php" method="POST" id="contactInquiryForm">
                        <div class="row g-4">

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark mb-1">Full Name</label>
                                <div class="input-icon-wrapper">
                                    <input type="text" name="name" class="form-control form-control-custom" placeholder="e.g. Kasun Fernando" required>
                                    <i class="fa-regular fa-user input-icon"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark mb-1">Email Address</label>
                                <div class="input-icon-wrapper">
                                    <input type="email" name="email" class="form-control form-control-custom" placeholder="e.g. kasun@example.com" required>
                                    <i class="fa-regular fa-envelope input-icon"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark mb-1">Phone Number</label>
                                <div class="input-icon-wrapper">
                                    <input type="tel" name="phone" class="form-control form-control-custom" placeholder="+94 77 123 4567" required>
                                    <i class="fa-solid fa-phone input-icon"></i>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-dark mb-1">Subject / Request Type</label>
                                <div class="input-icon-wrapper">
                                    <select name="subject" class="form-select form-select-custom" required>
                                        <option value="" disabled selected>Select an option...</option>
                                        <option value="General Inquiry">General Fleet Inquiry</option>
                                        <option value="Airport Pickup Booking">Airport Pickup & Meet-and-Greet</option>
                                        <option value="Corporate Monthly Lease">Corporate Monthly Lease</option>
                                        <option value="Wedding / Luxury Car Hire">Wedding / Luxury Car Hire</option>
                                    </select>
                                    <i class="fa-solid fa-car-side input-icon"></i>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark mb-1">Your Message</label>
                                <div class="input-icon-wrapper textarea-wrapper">
                                    <textarea name="message" class="form-control form-control-custom" rows="4" placeholder="Let us know your travel dates, preferred vehicle category, or any special requests..." required></textarea>
                                    <i class="fa-regular fa-comment-dots input-icon"></i>
                                </div>
                            </div>

                            <div class="col-12 mt-4 pt-2">
                                <button type="submit" name="submit_inquiry" class="btn btn-swift-submit w-100 py-3 fw-bold text-uppercase">
                                    Submit Inquiry <i class="fa-solid fa-paper-plane ms-2"></i>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Right: Branch Desks -->
            <div class="col-lg-5">
                <div class="card p-4 p-md-5 branch-card h-100">
                    <h3 class="h4 fw-bold text-dark mb-4">Branch & Pickup Points</h3>

                    <!-- Branch 1: Main Hub -->
                    <div class="branch-item d-flex align-items-start gap-3 mb-2 pb-3 border-bottom">
                        <div class="branch-icon-box bg-main-hub">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Negombo Main Hub</h6>
                            <p class="small text-muted mb-2">No. 142, Main Street, Negombo, Sri Lanka (HQ)</p>
                            <a href="tel:+94312234567" class="text-decoration-none text-primary fw-semibold small">
                                <i class="fa-solid fa-phone me-1"></i> +94 31 223 4567
                            </a>
                        </div>
                    </div>

                    <!-- Branch 2: Airport -->
                    <div class="branch-item d-flex align-items-start gap-3 mb-2 pb-3 border-bottom">
                        <div class="branch-icon-box bg-airport">
                            <i class="fa-solid fa-plane-departure"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Airport Desk (CMB)</h6>
                            <p class="small text-muted mb-2">Arrivals Terminal, Bandaranaike Intl Airport, Katunayake</p>
                            <a href="tel:+94771234567" class="text-decoration-none text-dark fw-semibold small">
                                <i class="fa-solid fa-headset me-1"></i> +94 77 123 4567 (24/7 Support)
                            </a>
                        </div>
                    </div>

                    <!-- Branch 3: Colombo -->
                    <div class="branch-item d-flex align-items-start gap-3 mb-2">
                        <div class="branch-icon-box bg-city">
                            <i class="fa-solid fa-city"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Colombo City Center</h6>
                            <p class="small text-muted mb-2">R. A. De Mel Mawatha, Colombo 03</p>
                            <a href="tel:+94114456789" class="text-decoration-none fw-semibold small" style="color: #10b981;">
                                <i class="fa-solid fa-phone me-1"></i> +94 11 445 6789
                            </a>
                        </div>
                    </div>

                    <!-- Help box -->
                    <div class="mt-auto pt-4">
                        <div class="p-3 rounded-3" style="background: rgba(13, 110, 253, 0.05); border: 1px dashed rgba(13, 110, 253, 0.3);">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fa-brands fa-whatsapp text-success fs-2"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">WhatsApp Us</h6>
                                    <small class="text-muted">Instant replies during business hours</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>
<?php include_once "footer.php"; ?>