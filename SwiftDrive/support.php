<?php 
// Include header navigation component
include_once "header.php"; 

// Determine active information tab from URL query parameters
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'about';
?>

<div class="support-hero text-center">
    <div class="container">
        <h1 class="fw-bold mb-2">Help Center & Information</h1>
        <p class="text-white-50 lead mx-auto">Everything you need to know about renting with SwiftDrive.</p>
    </div>
</div>

<main class="pb-5 min-vh-100">
    <div class="container">
        <div class="row g-4">
            
            <!-- Left Sidebar Tabs -->
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills support-tabs" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    
                    <button class="nav-link <?php echo $active_tab == 'about' ? 'active' : ''; ?>" id="tab-about" data-bs-toggle="pill" data-bs-target="#content-about" type="button" role="tab">
                        <i class="fa-solid fa-building"></i> About Us
                    </button>
                    
                    <button class="nav-link <?php echo $active_tab == 'how-it-works' ? 'active' : ''; ?>" id="tab-how-it-works" data-bs-toggle="pill" data-bs-target="#content-how-it-works" type="button" role="tab">
                        <i class="fa-solid fa-car-side"></i> How to Book
                    </button>
                    
                    <button class="nav-link <?php echo $active_tab == 'faq' ? 'active' : ''; ?>" id="tab-faq" data-bs-toggle="pill" data-bs-target="#content-faq" type="button" role="tab">
                        <i class="fa-solid fa-circle-question"></i> FAQ & Support
                    </button>
                    
                    <button class="nav-link <?php echo $active_tab == 'terms' ? 'active' : ''; ?>" id="tab-terms" data-bs-toggle="pill" data-bs-target="#content-terms" type="button" role="tab">
                        <i class="fa-solid fa-file-contract"></i> Terms of Service
                    </button>
                    
                    <button class="nav-link <?php echo $active_tab == 'privacy' ? 'active' : ''; ?>" id="tab-privacy" data-bs-toggle="pill" data-bs-target="#content-privacy" type="button" role="tab">
                        <i class="fa-solid fa-shield-halved"></i> Privacy Policy
                    </button>

                </div>
            </div>

            <!-- Right Content Area -->
            <div class="col-lg-9">
                <div class="tab-content tab-content-box" id="v-pills-tabContent">
                    
                    <!-- 1. ABOUT US -->
                    <div class="tab-pane fade <?php echo $active_tab == 'about' ? 'show active' : ''; ?>" id="content-about" role="tabpanel">
                        <h3 class="fw-bold mb-4 text-dark border-bottom pb-3"><i class="fa-solid fa-building text-primary me-2"></i>About Us</h3>
                        <p class="text-muted" style="font-size: 1.1rem; line-height: 1.8;">
                            Welcome to <strong>SwiftDrive</strong>, Sri Lanka's premier vehicle rental service. Founded with the vision of making travel seamless and enjoyable, we offer a wide range of well-maintained vehicles to suit every journey—from quick city rides to adventurous island tours.
                        </p>
                        <p class="text-muted" style="font-size: 1.1rem; line-height: 1.8;">
                            Our dedicated team works around the clock to ensure you receive top-notch customer service, transparent pricing, and vehicles that meet the highest safety standards. 
                        </p>
                    </div>

                    <!-- 2. HOW IT WORKS -->
                    <div class="tab-pane fade <?php echo $active_tab == 'how-it-works' ? 'show active' : ''; ?>" id="content-how-it-works" role="tabpanel">
                        <h3 class="fw-bold mb-4 text-dark border-bottom pb-3"><i class="fa-solid fa-car-side text-primary me-2"></i>How to Book a Vehicle</h3>
                        
                        <div class="row g-4 mt-2">
                            <div class="col-md-6">
                                <div class="step-card">
                                    <div class="step-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                                    <h5 class="fw-bold">1. Find Your Ride</h5>
                                    <p class="text-muted small">Browse our fleet and select a vehicle that fits your needs.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="step-card">
                                    <div class="step-icon"><i class="fa-regular fa-calendar-check"></i></div>
                                    <h5 class="fw-bold">2. Select Dates</h5>
                                    <p class="text-muted small">Choose your pickup and return dates for instant price calculation.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="step-card">
                                    <div class="step-icon"><i class="fa-solid fa-file-signature"></i></div>
                                    <h5 class="fw-bold">3. Confirm Booking</h5>
                                    <p class="text-muted small">Submit your request. Our admins will approve it quickly.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="step-card">
                                    <div class="step-icon"><i class="fa-solid fa-key"></i></div>
                                    <h5 class="fw-bold">4. Drive Away</h5>
                                    <p class="text-muted small">Pick up the keys and enjoy a safe and comfortable journey.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. FAQ -->
                    <div class="tab-pane fade <?php echo $active_tab == 'faq' ? 'show active' : ''; ?>" id="content-faq" role="tabpanel">
                        <h3 class="fw-bold mb-4 text-dark border-bottom pb-3"><i class="fa-solid fa-circle-question text-primary me-2"></i>Frequently Asked Questions</h3>
                        
                        <div class="accordion accordion-flush" id="faqAccordion">
                            <div class="accordion-item bg-light border-0 rounded-3 mb-3 p-1">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        What documents do I need?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">You need a valid National Driving License, an NIC or Passport, and proof of billing address.</div>
                                </div>
                            </div>
                            
                            <div class="accordion-item bg-light border-0 rounded-3 mb-3 p-1">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        Is fuel included in the price?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">No, we provide the car with a full tank, and it must be returned full (Full-to-Full policy).</div>
                                </div>
                            </div>
                            
                            <div class="accordion-item bg-light border-0 rounded-3 mb-3 p-1">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-transparent fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        Can I cancel my booking?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">Yes, free cancellation is available up to 48 hours before your scheduled pickup time.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. TERMS OF SERVICE -->
                    <div class="tab-pane fade <?php echo $active_tab == 'terms' ? 'show active' : ''; ?>" id="content-terms" role="tabpanel">
                        <h3 class="fw-bold mb-4 text-dark border-bottom pb-3"><i class="fa-solid fa-file-contract text-primary me-2"></i>Terms of Service</h3>
                        
                        <h6 class="fw-bold mt-4">1. General Conditions</h6>
                        <p class="text-muted small mb-4">By booking a vehicle through SwiftDrive, you agree to comply with all terms. The primary driver must be at least 21 years old with a valid license.</p>

                        <h6 class="fw-bold">2. Vehicle Usage</h6>
                        <p class="text-muted small mb-4">The rented vehicle must only be driven by the authorized person. Off-road driving and illegal activities are strictly prohibited.</p>

                        <h6 class="fw-bold">3. Late Returns</h6>
                        <p class="text-muted small mb-0">Vehicles returned later than the agreed time without prior notice will be subject to additional hourly penalties.</p>
                    </div>

                    <!-- 5. PRIVACY POLICY -->
                    <div class="tab-pane fade <?php echo $active_tab == 'privacy' ? 'show active' : ''; ?>" id="content-privacy" role="tabpanel">
                        <h3 class="fw-bold mb-4 text-dark border-bottom pb-3"><i class="fa-solid fa-shield-halved text-primary me-2"></i>Privacy Policy</h3>
                        
                        <p class="text-muted mb-4">At SwiftDrive, your privacy is our priority. We collect your personal information (name, email, phone, license details) solely to process your vehicle reservations.</p>

                        <h6 class="fw-bold"><i class="fa-solid fa-lock text-primary me-2"></i> Data Security</h6>
                        <p class="text-muted small mb-4">Your passwords are encrypted, and we use secure connections to protect your data. We do not store credit card details locally.</p>

                        <h6 class="fw-bold"><i class="fa-solid fa-share-nodes text-primary me-2"></i> Third-Party Sharing</h6>
                        <p class="text-muted small mb-0">We do not sell or trade your information. Data is only shared with trusted legal or insurance partners when strictly necessary.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once "footer.php"; ?>