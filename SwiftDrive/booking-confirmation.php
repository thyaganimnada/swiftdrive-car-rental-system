<?php
// Include booking confirmation action handler
require_once "actions/bookingCom_action.php";
// Include header navigation component
include_once "header.php";
?>

<main class="py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        <div class="confirmation-card">
          <div class="success-icon">
            <i class="fa-solid fa-check"></i>
          </div>
          <h2 class="fw-bold text-dark mb-2">Booking Confirmed!</h2>
          <p class="text-muted">Thank you, <?php echo htmlspecialchars($_SESSION['fullname']); ?>. Your reservation has been successfully placed.</p>

          <div class="invoice-box">
            <h5 class="fw-bold mb-4 border-bottom pb-2">Reservation Details</h5>
            
            <div class="detail-row">
              <span class="detail-label">Booking Reference</span>
              <span class="detail-value text-primary"><?php echo $booking_ref; ?></span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Vehicle</span>
              <span class="detail-value"><?php echo htmlspecialchars($booking['vehicle_name']); ?></span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Pickup</span>
              <span class="detail-value"><?php echo $booking['pickup_date']; ?> <small class="text-muted">(<?php echo htmlspecialchars($booking['pickup_location']); ?>)</small></span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Return</span>
              <span class="detail-value"><?php echo $booking['return_date']; ?> <small class="text-muted">(<?php echo htmlspecialchars($booking['return_location']); ?>)</small></span>
            </div>
            
            <h5 class="fw-bold mt-4 pt-3 border-top mb-3">Payment Summary</h5>
            
            <div class="detail-row">
              <span class="detail-label">Rental Amount (To pay at pickup)</span>
              <span class="detail-value">Rs. <?php echo number_format($rental_amount, 0); ?></span>
            </div>
            <div class="detail-row">
              <span class="detail-label text-success">Security Deposit Paid ✓</span>
              <span class="detail-value text-success">Rs. <?php echo number_format($booking['deposit_paid'], 0); ?></span>
            </div>
            <div class="detail-row mt-2" style="background: rgba(13, 110, 253, 0.05); padding: 15px; border-radius: 8px;">
              <span class="detail-label text-dark">Balance Due at Pickup</span>
              <span class="detail-value text-primary fs-5">Rs. <?php echo number_format($balance_to_pay, 0); ?></span>
            </div>
          </div>
          <div class="d-flex justify-content-center gap-3 mt-5">
            <a href="index.php" class="btn btn-swift-outline px-4 py-2">Return Home</a>
            <a href="my_bookings.php" class="btn btn-swift-primary px-4 py-2">Go to My Bookings</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include_once "footer.php"; ?>