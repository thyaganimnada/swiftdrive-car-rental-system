<?php
// Include database connection
include_once "db/db.php";

// Retrieve filter criteria from GET request parameters
$selectedTypes = isset($_GET['type']) ? (array)$_GET['type'] : [];
$selectedTrans = isset($_GET['transmission']) ? (array)$_GET['transmission'] : [];
$selectedFuel = isset($_GET['fuel']) ? (array)$_GET['fuel'] : [];
$minPrice = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : 999999;
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Retrieve pickup and return search criteria
$pickupDate = isset($_GET['pickup_date']) ? trim($_GET['pickup_date']) : '';
$returnDate = isset($_GET['return_date']) ? trim($_GET['return_date']) : '';
$pickupLocation = isset($_GET['location']) ? trim($_GET['location']) : 'Negombo';

// Build dynamic SQL query for vehicle filtering
$sql = "SELECT * FROM vehicles WHERE 1=1";

// Exclude vehicles already reserved during the requested dates
if (!empty($pickupDate) && !empty($returnDate)) {
    $p_date_esc = mysqli_real_escape_string($conn, $pickupDate);
    $r_date_esc = mysqli_real_escape_string($conn, $returnDate);
    
    $sql .= " AND id NOT IN (
                SELECT vehicle_id FROM bookings 
                WHERE status NOT IN ('Cancelled', 'Completed', 'Returned') 
                AND (pickup_date <= '$r_date_esc' AND return_date >= '$p_date_esc')
              )";
}

// Filter by vehicle category types
if (!empty($selectedTypes)) {
    $typesEscaped = array_map(function($t) use ($conn) { return "'" . mysqli_real_escape_string($conn, $t) . "'"; }, $selectedTypes);
    $sql .= " AND category IN (" . implode(',', $typesEscaped) . ")";
}
// Filter by transmission type
if (!empty($selectedTrans)) {
    $transEscaped = array_map(function($tr) use ($conn) { return "'" . mysqli_real_escape_string($conn, $tr) . "'"; }, $selectedTrans);
    $sql .= " AND transmission IN (" . implode(',', $transEscaped) . ")";
}
// Filter by fuel type
if (!empty($selectedFuel)) {
    $fuelEscaped = array_map(function($f) use ($conn) { return "'" . mysqli_real_escape_string($conn, $f) . "'"; }, $selectedFuel);
    $sql .= " AND fuel IN (" . implode(',', $fuelEscaped) . ")";
}
// Filter by minimum daily rental rate
if ($minPrice > 0) {
    $sql .= " AND daily_rate >= " . (float)$minPrice;
}
// Filter by maximum daily rental rate
if ($maxPrice < 999999 && $maxPrice > 0) {
    $sql .= " AND daily_rate <= " . (float)$maxPrice;
}
// Filter by keyword match in name, description, or category
if (!empty($keyword)) {
    $kw = mysqli_real_escape_string($conn, $keyword);
    $sql .= " AND (name LIKE '%$kw%' OR description LIKE '%$kw%' OR category LIKE '%$kw%')";
}

// Apply selected sorting order
if ($sortBy === 'price_asc') {
    $sql .= " ORDER BY daily_rate ASC";
} elseif ($sortBy === 'price_desc') {
    $sql .= " ORDER BY daily_rate DESC";
} elseif ($sortBy === 'name_asc') {
    $sql .= " ORDER BY name ASC";
} else {
    $sql .= " ORDER BY id DESC";
}

// Execute query and fetch matching vehicles
$result = mysqli_query($conn, $sql);
$vehicles = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $vehicles[] = $row;
    }
}
