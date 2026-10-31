//Smart Date Script (Prevents selecting past dates & Return date before Pickup date
document.addEventListener("DOMContentLoaded", function () {
    const pickupInput = document.getElementById("heroPickupDate");
    const returnInput = document.getElementById("heroReturnDate");

    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split('T')[0];

    // Disable past dates on page load
    pickupInput.setAttribute('min', today);
    returnInput.setAttribute('min', today);

    // When pickup date changes, update the minimum return date
    pickupInput.addEventListener("change", function () {
        returnInput.setAttribute('min', this.value);
        // If return date is now invalid, clear it
        if (returnInput.value && returnInput.value < this.value) {
            returnInput.value = this.value;
        }
    });
});

