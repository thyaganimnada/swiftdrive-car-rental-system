<?php
include_once "header.php";
require_once "actions/vehicles_action.php";

?>

<style>
</style>

<main class="py-5">
    <div class="container">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <div>
                <h1 class="h2 fw-bold text-dark mb-1">Search Available Vehicles</h1>
                <p class="text-muted small mb-0">Browse our fleet and filter by your exact travel preferences.</p>
            </div>
            <div>
                <span class="badge bg-dark text-white px-3 py-2 rounded-pill fw-bold">
                    <?php echo count($vehicles); ?> Vehicles Found
                </span>
            </div>
        </div>

        <div class="row g-4">

            <!-- LEFT: Filters Panel -->
            <div class="col-lg-4 col-xl-3">
                <form method="GET" action="vehicles.php" id="filterForm">
                    <input type="hidden" name="location" value="<?php echo htmlspecialchars($pickupLocation); ?>">

                    <aside class="filter-card">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h2 class="filter-heading mb-0">FILTERS</h2>
                            <a href="vehicles.php" class="btn btn-sm btn-link text-decoration-none text-muted p-0 fw-semibold">
                                <i class="fa-solid fa-rotate-left me-1"></i>Reset
                            </a>
                        </div>

                        <!-- Filter vehicles by rental pickup and return dates -->
                        <div class="filter-group">
                            <div class="filter-group-title text-primary"><i class="fa-regular fa-calendar-check me-2"></i>Rental Dates</div>
                            <div class="mb-2">
                                <label class="small fw-bold text-muted mb-1">Pickup Date</label>
                                <input type="date" class="form-control form-control-sm" name="pickup_date" value="<?php echo htmlspecialchars($pickupDate); ?>" min="<?php echo date('Y-m-d'); ?>" style="border-radius: 8px;">
                            </div>
                            <div class="mb-2">
                                <label class="small fw-bold text-muted mb-1">Return Date</label>
                                <input type="date" class="form-control form-control-sm" name="return_date" value="<?php echo htmlspecialchars($returnDate); ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" style="border-radius: 8px;">
                            </div>
                            <button type="submit" class="btn btn-swift-primary btn-sm w-100 py-2 fw-bold mt-2" style="border-radius: 8px;">Check Availability</button>
                        </div>

                        <!-- Keyword Search -->
                        <div class="filter-group">
                            <label class="filter-group-title" for="keywordSearch">Keyword Search</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="keywordSearch" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" placeholder="e.g. Aqua..." style="border-radius: 8px 0 0 8px; padding: 10px;">
                                <button class="btn btn-dark px-3" type="submit" style="border-radius: 0 8px 8px 0;"><i class="fa-solid fa-search"></i></button>
                            </div>
                        </div>

                        <!-- Vehicle Type Filter -->
                        <div class="filter-group">
                            <div class="filter-group-title">Vehicle Type</div>
                            <?php
                            $types = ['Economy', 'SUV', 'Van', 'Luxury'];
                            foreach ($types as $t):
                                $checked = in_array($t, $selectedTypes) ? 'checked' : '';
                            ?>
                                <label class="custom-checkbox-label">
                                    <input type="checkbox" name="type[]" value="<?php echo $t; ?>" <?php echo $checked; ?> onchange="this.form.submit()">
                                    <span><?php echo $t; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="filter-group">
                            <div class="filter-group-title">Price Range (Rs./day)</div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" name="min_price" value="<?php echo $minPrice > 0 ? $minPrice : ''; ?>" placeholder="Min" style="border-radius: 8px;">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" name="max_price" value="<?php echo $maxPrice < 999999 ? $maxPrice : ''; ?>" placeholder="Max" style="border-radius: 8px;">
                                </div>
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-swift-dark btn-sm w-100 py-2 fw-bold" style="border-radius: 8px;">Apply Price</button>
                                </div>
                            </div>
                        </div>

                    </aside>
                </form>
            </div>

            <!-- RIGHT: Available Vehicles List -->
            <div class="col-lg-8 col-xl-9">

                <!-- Sort Control Bar -->
                <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 border shadow-sm">
                    <span class="fw-bold text-dark">
                        <?php if (!empty($pickupDate) && !empty($returnDate)): ?>
                            <span class="text-success"><i class="fa-solid fa-circle-check me-1"></i> Available from <?php echo $pickupDate; ?> to <?php echo $returnDate; ?></span>
                        <?php else: ?>
                            Available Fleet Catalog
                        <?php endif; ?>
                    </span>
                    <div class="d-flex align-items-center gap-2">
                        <label for="sortSelect" class="small text-muted mb-0 fw-semibold">Sort by:</label>
                        <select class="form-select form-select-sm fw-semibold" id="sortSelect" onchange="location = this.value;" style="width: auto; border-radius: 8px;">
                            <option value="<?php echo 'vehicles.php?' . http_build_query(array_merge($_GET, ['sort' => 'default'])); ?>" <?php echo $sortBy === 'default' ? 'selected' : ''; ?>>Featured</option>
                            <option value="<?php echo 'vehicles.php?' . http_build_query(array_merge($_GET, ['sort' => 'price_asc'])); ?>" <?php echo $sortBy === 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="<?php echo 'vehicles.php?' . http_build_query(array_merge($_GET, ['sort' => 'price_desc'])); ?>" <?php echo $sortBy === 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Vehicle Grid -->
                <?php if (empty($vehicles)): ?>
                    <div class="p-5 bg-white rounded-3 border text-center shadow-sm">
                        <i class="fa-solid fa-calendar-xmark fa-3x text-danger mb-3 opacity-75"></i>
                        <h4 class="fw-bold mb-2">No Vehicles Available</h4>
                        <p class="text-muted mb-4">Sorry, all vehicles matching your criteria are booked for the selected dates. Try different dates or remove filters.</p>
                        <a href="vehicles.php" class="btn btn-swift-dark px-4 py-2">
                            <i class="fa-solid fa-rotate-left me-2"></i>Clear Filters & Dates
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row g-4">
                        <?php foreach ($vehicles as $veh):
                            $status = $veh['status'] ?? 'Available';
                            $statusClass = strtolower($status);

                            // Book Button URL Creation
                            $bookingUrl = "booking.php?vehicle_id={$veh['id']}";
                            if (!empty($pickupDate)) $bookingUrl .= "&pickup=" . urlencode($pickupDate);
                            if (!empty($returnDate)) $bookingUrl .= "&return=" . urlencode($returnDate);
                            if (!empty($pickupLocation)) $bookingUrl .= "&location=" . urlencode($pickupLocation);
                        ?>
                            <div class="col-md-6">
                                <div class="vehicle-card shadow-sm">

                                    <!-- Image Wrapper -->
                                    <div class="vehicle-image-wrapper">
                                        <img src="<?php echo htmlspecialchars($veh['image']); ?>" alt="<?php echo htmlspecialchars($veh['name']); ?>" loading="lazy" onerror="this.src='assets/images/default-car.jpg';">
                                        <span class="status-badge available position-absolute top-0 start-0 m-3 shadow-sm">
                                            <span class="dot"></span> Available
                                        </span>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="vehicle-card-body">

                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div>
                                                <h3 class="vehicle-name mb-0"><?php echo htmlspecialchars($veh['name']); ?></h3>
                                                <span class="vehicle-type-tag"><?php echo htmlspecialchars($veh['sub_category'] ?? $veh['category']); ?></span>
                                            </div>
                                            <span class="badge bg-light text-dark border px-2 py-1 fw-semibold rounded-pill">
                                                <i class="fa-solid fa-gas-pump text-primary me-1"></i> <?php echo htmlspecialchars($veh['fuel']); ?>
                                            </span>
                                        </div>

                                        <!-- Specs Row -->
                                        <div class="vehicle-specs-row">
                                            <div class="spec-item">
                                                <i class="fa-solid fa-gear"></i> <?php echo htmlspecialchars($veh['transmission']); ?>
                                            </div>
                                            <div class="spec-item">
                                                <i class="fa-solid fa-users"></i> <?php echo htmlspecialchars($veh['seats']); ?> Seats
                                            </div>
                                            <div class="spec-item">
                                                <i class="fa-solid fa-suitcase-rolling"></i> <?php echo htmlspecialchars($veh['luggage_capacity'] ?? 2); ?> Bags
                                            </div>
                                        </div>

                                        <!-- Price Row -->
                                        <div class="vehicle-price-row">
                                            <div>
                                                <div class="price-val">Rs. <?php echo number_format($veh['daily_rate'], 0); ?></div>
                                                <div class="price-unit">per day</div>
                                            </div>
                                            <div class="small text-muted fw-semibold">
                                                Deposit: Rs. <?php echo number_format($veh['deposit'], 0); ?>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="d-grid gap-2 mt-auto">
                                            <a href="vehicledetails.php?id=<?php echo (int)$veh['id']; ?>" class="btn btn-swift-outline text-uppercase py-2 fw-semibold" style="font-size: 0.85rem;">
                                                <i class="fa-solid fa-eye me-1"></i> View Details
                                            </a>

                                            <a href="<?php echo $bookingUrl; ?>" class="btn btn-swift-primary text-uppercase py-2 fw-bold">
                                                <i class="fa-solid fa-bolt me-2"></i> Book Now
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>

    </div>
</main>

<?php include_once "footer.php"; ?>