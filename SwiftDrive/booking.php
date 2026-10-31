<?php
require_once "actions/booking_action.php";
include_once "header.php";

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css">

<main class="py-5">
  <div class="container">

    <nav aria-label="breadcrumb" class="breadcrumb-custom mb-4">
      <span><a href="index.php">Home</a></span>
      <span class="mx-2 text-muted">/</span>
      <span><a href="vehicles.php">Our Fleet</a></span>
      <span class="mx-2 text-muted">/</span>
      <span class="text-dark fw-bold"><?php echo htmlspecialchars($vehicle['name']); ?></span>
    </nav>

    <div class="mb-4">
      <h1 class="h2 fw-bold text-dark">Complete Your Booking</h1>
      <p class="text-muted">Review your selection and fill in the rental details.</p>
    </div>

    <div class="row g-5">

      <!-- Left: Booking Form -->
      <div class="col-lg-8">
        <div class="booking-card">
          <h4 class="fw-bold mb-4"><i class="fa-solid fa-map-location-dot text-primary me-2"></i> Rental Information</h4>

          <!-- Availability Alert Box -->
          <div id="availabilityAlert" class="alert d-none mb-4 fw-semibold small"></div>

          <form action="actions/book_action.php" method="POST" id="bookingForm">
            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['id']; ?>">
            <input type="hidden" name="daily_rate" id="dailyRate" value="<?php echo $vehicle['daily_rate']; ?>">
            <input type="hidden" name="total_price" id="totalPriceInput" value="0">
            <input type="hidden" name="total_days" id="totalDaysInput" value="0">

            <div class="row g-4">

              <div class="col-md-6">
                <label class="form-label small fw-bold text-dark">Pickup Location</label>
                <select name="pickup_location" class="form-select form-select-custom" required>
                  <option value="Negombo Main Hub">Negombo Main Hub</option>
                  <option value="Bandaranaike Intl Airport">Bandaranaike Intl Airport (CMB)</option>
                  <option value="Colombo City Center">Colombo City Center</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-bold text-dark">Return Location</label>
                <select name="return_location" class="form-select form-select-custom" required>
                  <option value="Negombo Main Hub">Negombo Main Hub</option>
                  <option value="Bandaranaike Intl Airport">Bandaranaike Intl Airport (CMB)</option>
                  <option value="Colombo City Center">Colombo City Center</option>
                </select>
              </div>

              <!-- Input fields for booking pickup and return dates -->
              <div class="col-md-6">
                <label class="form-label small fw-bold text-dark">Pickup Date</label>
                <input type="text" name="pickup_date" id="pickupDate" class="form-control form-control-custom bg-white" placeholder="Select Pickup Date" required readonly>
              </div>

              <div class="col-md-6">
                <label class="form-label small fw-bold text-dark">Return Date</label>
                <input type="text" name="return_date" id="returnDate" class="form-control form-control-custom bg-white" placeholder="Select Return Date" required readonly>
              </div>

              <div class="col-12">
                <label class="form-label small fw-bold text-dark">Special Requests (Optional)</label>
                <textarea name="special_requests" class="form-control form-control-custom" rows="3" placeholder="e.g. Need a baby seat, specific time for pickup..."></textarea>
              </div>

              <div class="col-12 mt-4">
                <button type="submit" name="submit_booking" id="confirmBookingBtn" class="btn btn-swift-primary w-100 text-uppercase">
                  Confirm Booking <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Right: Order Summary -->
      <div class="col-lg-4">
        <div class="summary-card">
          <h5 class="fw-bold mb-3">Order Summary</h5>

          <img src="<?php echo htmlspecialchars($vehicle['image']); ?>" class="summary-img" alt="Car">

          <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($vehicle['name']); ?></h4>
          <p class="text-white-50 small mb-4"><?php echo htmlspecialchars($vehicle['sub_category']); ?> | <?php echo htmlspecialchars($vehicle['transmission']); ?></p>

          <div class="price-row">
            <span class="text-white-50">Daily Rate</span>
            <span class="fw-bold">Rs. <?php echo number_format($vehicle['daily_rate'], 0); ?></span>
          </div>
          <div class="price-row">
            <span class="text-white-50">Total Days</span>
            <span class="fw-bold" id="displayDays">0 Days</span>
          </div>
          <div class="price-row">
            <span class="text-white-50">Refundable Deposit</span>
            <span class="fw-bold">Rs. <?php echo number_format($vehicle['deposit'], 0); ?></span>
          </div>

          <div class="price-row mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.2);">
            <span class="fw-bold fs-5 text-white">Total Cost</span>
            <span class="fw-bold fs-4 text-primary" id="displayTotal">Rs. 0</span>
          </div>
          <p class="text-white-50 mt-2" style="font-size: 0.75rem;">* Deposit will be collected at pickup.</p>
        </div>
      </div>

    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const pDateInput = document.getElementById("pickupDate");
    const rDateInput = document.getElementById("returnDate");
    const rate = parseFloat(document.getElementById("dailyRate").value);

    const displayDays = document.getElementById("displayDays");
    const displayTotal = document.getElementById("displayTotal");
    const totalDaysInput = document.getElementById("totalDaysInput");
    const totalPriceInput = document.getElementById("totalPriceInput");

    const alertBox = document.getElementById("availabilityAlert");
    const submitBtn = document.getElementById("confirmBookingBtn");
    const vehicleId = <?php echo $vehicle['id']; ?>;

    // Fetch already booked dates list from PHP backend
    const bookedDates = <?php echo $disabled_dates_json; ?>;

    // Function to calculate total rental price and check vehicle availability
    function calculatePriceAndCheckAvailability() {
      const pDate = new Date(pDateInput.value);
      const rDate = new Date(rDateInput.value);

      if (pDateInput.value && rDateInput.value && rDate > pDate) {
        const timeDiff = rDate.getTime() - pDate.getTime();
        const days = Math.ceil(timeDiff / (1000 * 3600 * 24));
        const total = days * rate;

        displayDays.innerText = days + " Days";
        displayTotal.innerText = "Rs. " + total.toLocaleString();

        totalDaysInput.value = days;
        totalPriceInput.value = total;

        alertBox.classList.remove("d-none", "alert-success", "alert-danger");
        alertBox.innerHTML = "<i class='fa-solid fa-spinner fa-spin me-2'></i> Checking availability...";
        submitBtn.disabled = true;

        const formData = new FormData();
        formData.append('vehicle_id', vehicleId);
        formData.append('pickup_date', pDateInput.value);
        formData.append('return_date', rDateInput.value);

        fetch('actions/check_availability.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.available) {
              alertBox.className = "alert alert-success mb-4 fw-semibold small";
              alertBox.innerHTML = "<i class='fa-solid fa-circle-check me-2'></i> " + data.message;
              submitBtn.disabled = false;
            } else {
              alertBox.className = "alert alert-danger mb-4 fw-semibold small";
              alertBox.innerHTML = "<i class='fa-solid fa-circle-exclamation me-2'></i> " + data.message;
              submitBtn.disabled = true;
            }
          });

      } else if (pDateInput.value && rDateInput.value) {
        displayDays.innerText = "0 Days";
        displayTotal.innerText = "Rs. 0";
        alertBox.className = "alert alert-danger mb-4 fw-semibold small";
        alertBox.innerHTML = "<i class='fa-solid fa-circle-exclamation me-2'></i> Return date must be after pickup date.";
        submitBtn.disabled = true;
      }
    }

    // Initialize Flatpickr calendar for return date
    const returnPicker = flatpickr("#returnDate", {
      minDate: "today",
      disable: bookedDates,
      dateFormat: "Y-m-d",
      onChange: function(selectedDates, dateStr, instance) {
        calculatePriceAndCheckAvailability();
      }
    });

    // Initialize Flatpickr calendar for pickup date
    const pickupPicker = flatpickr("#pickupDate", {
      minDate: "today",
      disable: bookedDates,
      dateFormat: "Y-m-d",
      onChange: function(selectedDates, dateStr, instance) {
        // Restrict return date to be on or after selected pickup date
        returnPicker.set('minDate', dateStr);
        calculatePriceAndCheckAvailability();
      }
    });

    // Calculate price and check availability on initial page load if dates exist
    if (pDateInput.value && rDateInput.value) {
      calculatePriceAndCheckAvailability();
    }
  });
</script>

<?php include_once "footer.php"; ?>