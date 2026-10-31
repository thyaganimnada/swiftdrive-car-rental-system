<?php
// Include payment processing pre-check action logic
require_once "actions/payment_action.php";
// Include header navigation component
include_once "header.php";
?>

<main class="py-5">
  <div class="container">
    
    <div class="text-center mb-5">
      <h1 class="h2 fw-bold text-dark mb-1">Secure Payment</h1>
      <p class="text-muted">Complete your reservation by paying the security deposit.</p>
    </div>

    <div class="row g-5 justify-content-center">
      
      <!-- Order Summary -->
      <div class="col-lg-5">
        <div class="summary-box">
          <h5 class="fw-bold mb-4">Booking Summary</h5>
          <img src="<?php echo htmlspecialchars($booking['image']); ?>" alt="Vehicle">
          
          <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($booking['vehicle_name']); ?></h5>
          <p class="text-white-50 small mb-4">Booking ID: #CRB-2026-<?php echo str_pad($booking['id'], 4, '0', STR_PAD_LEFT); ?></p>

          <div class="price-breakdown">
            <span class="text-white-50">Rental Amount (<?php echo $booking['total_days']; ?> Days)</span>
            <span class="fw-bold">Rs. <?php echo number_format($rental_amount, 0); ?></span>
          </div>
          <div class="price-breakdown">
            <span class="text-white-50">Security Deposit (Refundable)</span>
            <span class="fw-bold">Rs. <?php echo number_format($security_deposit, 0); ?></span>
          </div>
          
          <div class="price-breakdown total">
            <span class="text-white">Total Payable Now</span>
            <span>Rs. <?php echo number_format($security_deposit, 0); ?></span>
          </div>
          <p class="small text-white-50 mt-2">* You are only paying the Security Deposit today. The rental amount of Rs. <?php echo number_format($rental_amount, 0); ?> is payable at pickup.</p>
        </div>
      </div>

      <!-- Payment Form -->
      <div class="col-lg-6">
        <div class="checkout-card">
          <h4 class="fw-bold mb-4">Payment Details</h4>
          
          <form action="actions/process_payment.php" method="POST">
            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
            <input type="hidden" name="amount" value="<?php echo $security_deposit; ?>">

            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">Cardholder Name</label>
                <!-- Added name attribute -->
                <input type="text" class="form-control custom-input" name="card_name" placeholder="e.g. Kasun Fernando" required>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">Card Number</label>
                <!-- Added name, id, pattern, and title -->
                <input type="text" class="form-control custom-input" name="card_number" id="cardNumber" placeholder="0000 0000 0000 0000" maxlength="19" pattern="\d{4} \d{4} \d{4} \d{4}" title="Please enter a valid 16-digit card number" required>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6">
                    <label class="form-label small fw-bold text-dark">Expiry Date</label>
                    <!-- Added name, id, pattern, and title -->
                    <input type="text" class="form-control custom-input" name="expiry_date" id="expiryDate" placeholder="MM/YY" maxlength="5" pattern="(0[1-9]|1[0-2])\/[0-9]{2}" title="Format must be MM/YY (e.g., 12/26)" required>
                </div>
                <div class="col-6">
                    <label class="form-label small fw-bold text-dark">CVV</label>
                    <!-- Added name, id, pattern, and title -->
                    <input type="password" class="form-control custom-input" name="cvv" id="cvv" placeholder="123" maxlength="3" pattern="\d{3}" title="Please enter a 3-digit CVV" required>
                </div>
            </div>

            <div class="alert alert-primary bg-opacity-10 border-0 small fw-semibold text-primary mb-4">
                <i class="fa-solid fa-lock me-2"></i> Your payment information is securely processed.
            </div>

            <button type="submit" name="pay_deposit" class="btn btn-primary w-100 py-3 fw-bold text-uppercase" style="border-radius: 12px; font-size: 1.1rem;">
              Pay Deposit (Rs. <?php echo number_format($security_deposit, 0); ?>)
            </button>
          </form>

        </div>
      </div>

    </div>
  </div>
</main>
<script>
  

document.addEventListener('DOMContentLoaded', function () {

    // 1. Card Number Validation
    const cardNumberInput = document.getElementById('cardNumber');
    cardNumberInput.addEventListener('input', function (e) {
        // Remove all non-digit characters (prevents typing letters)
        let value = e.target.value.replace(/\D/g, '');

        // Add a space after every 4 digits
        value = value.replace(/(.{4})/g, '$1 ').trim();

        // Update the input field with the formatted value
        e.target.value = value;
    });

    // 2. Expiry Date Validation (MM/YY format)
    const expiryInput = document.getElementById('expiryDate');
    expiryInput.addEventListener('input', function (e) {
        // Remove all non-digit characters
        let value = e.target.value.replace(/\D/g, '');

        // Automatically insert the slash '/' after the month (first 2 digits)
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }

        e.target.value = value;
    });

    // 3. CVV Validation
    const cvvInput = document.getElementById('cvv');
    cvvInput.addEventListener('input', function (e) {
        // Remove all non-digit characters (only 3 digits allowed due to maxlength="3" in HTML)
        e.target.value = e.target.value.replace(/\D/g, '');
    });

});
</script>
<?php include_once "footer.php"; ?>