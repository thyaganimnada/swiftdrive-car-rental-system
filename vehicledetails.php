<?php
include_once "header.php";
require_once "actions/viehicalDetails_action.php";
?>
<main class="py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="breadcrumb-custom mb-4">
            <span><a href="index.php">Home</a></span>
            <span class="mx-2 text-muted">/</span>
            <span><a href="vehicles.php">Our Fleet</a></span>
            <span class="mx-2 text-muted">/</span>
            <span class="text-dark fw-bold"><?php echo htmlspecialchars($vehicle['name']); ?></span>
        </nav>

        <div class="row g-5">
            <!-- LEFT: Vehicle Image & Specs Grid -->
            <div class="col-lg-7">
                <!-- Main Image Card -->
                <div class="details-img-card">
                    <?php
                    $status = $vehicle['status'] ?? 'Available';
                    $statusClass = strtolower($status);
                    ?>
                    <span class="status-badge <?php echo $statusClass; ?> position-absolute top-0 start-0 m-4">
                        <span class="dot"></span> <?php echo htmlspecialchars($status); ?>
                    </span>
                    <img src="<?php echo htmlspecialchars($vehicle['image']); ?>" alt="<?php echo htmlspecialchars($vehicle['name']); ?>" class="details-main-img" onerror="this.src='assets/images/default-car.jpg';">
                </div>

                <!-- Quick Specs Grid -->
                <div class="quick-specs-grid">
                    <div class="spec-box">
                        <i class="fa-regular fa-calendar"></i>
                        <span>Model Year</span>
                        <strong><?php echo htmlspecialchars($vehicle['year'] ?? 'N/A'); ?></strong>
                    </div>
                    <div class="spec-box">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span>Efficiency</span>
                        <strong><?php echo htmlspecialchars($vehicle['mileage'] ?? 'N/A'); ?></strong>
                    </div>
                    <div class="spec-box">
                        <i class="fa-solid fa-suitcase-rolling"></i>
                        <span>Luggage</span>
                        <strong><?php echo htmlspecialchars($vehicle['luggage_capacity'] ?? '2'); ?> Bags</strong>
                    </div>
                    <div class="spec-box">
                        <i class="fa-solid fa-gear"></i>
                        <span>Transmission</span>
                        <strong><?php echo htmlspecialchars($vehicle['transmission'] ?? 'Auto'); ?></strong>
                    </div>
                    <div class="spec-box">
                        <i class="fa-solid fa-users"></i>
                        <span>Capacity</span>
                        <strong><?php echo htmlspecialchars($vehicle['seats'] ?? '5'); ?> Seats</strong>
                    </div>
                    <div class="spec-box">
                        <i class="fa-solid fa-gas-pump"></i>
                        <span>Fuel Type</span>
                        <strong><?php echo htmlspecialchars($vehicle['fuel'] ?? 'Petrol'); ?></strong>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Vehicle Details & Pricing Actions -->
            <div class="col-lg-5">

                <!-- Title & Category -->
                <div class="mb-4">
                    <div class="vehicle-cat-tag mb-1"><i class="fa-solid fa-car-side me-2"></i><?php echo htmlspecialchars($vehicle['sub_category'] ?? $vehicle['category']); ?></div>
                    <h1 class="vehicle-title"><?php echo htmlspecialchars($vehicle['name']); ?></h1>
                    <p class="vehicle-desc"><?php echo htmlspecialchars($vehicle['description'] ?? 'No description available for this vehicle.'); ?></p>
                </div>

                <!-- Features (Modern Pills) -->
                <div class="mb-4">
                    <h6 class="fw-bold text-dark text-uppercase small mb-3" style="letter-spacing: 0.5px;">Vehicle Features</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($featuresList as $feat): ?>
                            <div class="feature-pill-modern">
                                <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars(trim($feat)); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Pricing Summary Card -->
                <div class="pricing-summary-card">
                    <h6 class="fw-bold text-dark text-uppercase small mb-3" style="letter-spacing: 0.5px;">Rental Rates & Deposit</h6>

                    <div class="price-row-item">
                        <span class="price-label-text">Daily Rate</span>
                        <span class="price-value-text highlight">Rs. <?php echo number_format($vehicle['daily_rate'], 0); ?></span>
                    </div>

                    <div class="price-row-item">
                        <span class="price-label-text">Weekly Rate</span>
                        <span class="price-value-text">Rs. <?php echo number_format($vehicle['weekly_rate'] ?? 0, 0); ?></span>
                    </div>

                    <div class="price-row-item">
                        <span class="price-label-text">Refundable Deposit</span>
                        <span class="price-value-text">Rs. <?php echo number_format($vehicle['deposit'] ?? 0, 0); ?></span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-3 mt-4 pt-2">
                        <button class="btn btn-swift-dark py-3 text-uppercase" data-bs-toggle="modal" data-bs-target="#availabilityModal">
                            <i class="fa-regular fa-calendar-check me-2"></i> Check Availability
                        </button>

                        <?php if (strtolower($status) === 'available'): ?>
                            <a href="booking.php?vehicle_id=<?php echo (int)$vehicle['id']; ?>" class="btn btn-swift-primary py-3 text-uppercase">
                                <i class="fa-solid fa-bolt me-2"></i> Book This Vehicle Now
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary py-3 text-uppercase fw-bold" disabled style="border-radius: 12px;">
                                Currently Unavailable
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>

<!-- Check Availability Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal-content shadow-lg">

            <div class="modal-header custom-modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-calendar-days me-2"></i> Check Availability
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 p-md-5">
                <p class="text-muted mb-4 small">Select your planned rental dates for <strong><?php echo htmlspecialchars($vehicle['name']); ?></strong> to verify real-time availability.</p>

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <label class="form-label small fw-bold text-dark">Pickup Date</label>
                        <input type="date" class="form-control modal-input" id="modalCheckPickup" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-dark">Return Date</label>
                        <input type="date" class="form-control modal-input" id="modalCheckReturn" value="<?php echo date('Y-m-d', strtotime('+4 days')); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                </div>

                <div id="modalCheckResult" class="d-none rounded-3 p-3 text-center"></div>
            </div>

            <div class="modal-footer custom-modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-light fw-bold px-4" style="border-radius: 10px;" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-swift-dark px-4 py-2" id="btnRunCheckDates">Check Now</button>
                <a href="#" class="btn btn-swift-primary px-4 py-2 d-none" id="btnModalGoToBooking">Proceed to Book</a>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById("btnRunCheckDates")?.addEventListener("click", () => {
        const pDate = document.getElementById("modalCheckPickup").value;
        const rDate = document.getElementById("modalCheckReturn").value;
        const vehId = <?php echo (int)$vehicle['id']; ?>;

        const resultDiv = document.getElementById("modalCheckResult");
        const checkBtn = document.getElementById("btnRunCheckDates");
        const proceedBtn = document.getElementById("btnModalGoToBooking");

        // Check if date inputs are empty
        if (!pDate || !rDate) {
            alert("Please select both Pickup and Return dates.");
            return;
        }

        // Validate that return date is not prior to pickup date
        if (new Date(rDate) < new Date(pDate)) {
            resultDiv.classList.remove("d-none");
            resultDiv.className = "alert alert-danger mb-0 small fw-semibold";
            resultDiv.innerHTML = "<i class=\"fa-solid fa-circle-exclamation me-1\"></i> Return date cannot be earlier than pickup.";
            proceedBtn.classList.add("d-none");
            checkBtn.classList.remove("d-none");
            return;
        }

        // Display loading feedback to user
        resultDiv.classList.remove("d-none");
        resultDiv.className = "alert alert-info mb-0 small fw-semibold";
        resultDiv.innerHTML = "<i class='fa-solid fa-spinner fa-spin me-2'></i> Checking real-time availability...";
        checkBtn.disabled = true;

        // Send asynchronous availability check request via AJAX fetch API
        const formData = new FormData();
        formData.append('vehicle_id', vehId);
        formData.append('pickup_date', pDate);
        formData.append('return_date', rDate);

        fetch('actions/check_availability.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                checkBtn.disabled = false;

                if (data.available) {
                    // Show success status if dates are available
                    resultDiv.className = "alert alert-success mb-0 small fw-semibold";
                    resultDiv.innerHTML = "<i class=\"fa-solid fa-circle-check me-1\"></i> <strong>" + data.message + "</strong>";

                    // Display Proceed to Book button and hide check button
                    checkBtn.classList.add("d-none");
                    proceedBtn.classList.remove("d-none");

                    // Set target booking URL with selected dates
                    proceedBtn.href = "booking.php?vehicle_id=" + vehId + "&pickup=" + encodeURIComponent(pDate) + "&return=" + encodeURIComponent(rDate);
                } else {
                    // Show warning if dates are unavailable
                    resultDiv.className = "alert alert-danger mb-0 small fw-semibold";
                    resultDiv.innerHTML = "<i class=\"fa-solid fa-circle-exclamation me-1\"></i> <strong>" + data.message + "</strong>";

                    proceedBtn.classList.add("d-none");
                    checkBtn.classList.remove("d-none");
                }
            })
            .catch(error => {
                checkBtn.disabled = false;
                resultDiv.className = "alert alert-warning mb-0 small fw-semibold";
                resultDiv.innerHTML = "<i class=\"fa-solid fa-triangle-exclamation me-1\"></i> Error checking availability. Try again.";
            });

    });
</script>

<?php include_once "footer.php"; ?>