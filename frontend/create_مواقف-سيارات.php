**create_مواقف-سيارات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $capacity = trim($_POST['capacity']);
    $price = trim($_POST['price']);

    // Check for empty fields
    if (empty($name) || empty($address) || empty($capacity) || empty($price)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO مواقف_سيارات (name, address, capacity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $name, $address, $capacity, $price);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_مواقف-سيارات.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create parking lot form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create Parking Lot</h2>
    <form id="create-parking-lot-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter parking lot name">
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-slate-900">Address:</label>
            <input type="text" id="address" name="address" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter parking lot address">
        </div>
        <div class="mb-4">
            <label for="capacity" class="block text-sm font-medium text-slate-900">Capacity:</label>
            <input type="number" id="capacity" name="capacity" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter parking lot capacity">
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-slate-900">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter parking lot price">
        </div>
        <button type="submit" name="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-lg hover:bg-indigo-600 focus:ring-indigo-500 focus:border-indigo-500">Create Parking Lot</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-parking-lot-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: '../backend/مواقف-سيارات.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_مواقف-سيارات.php';
                } else {
                    alert('Error creating parking lot');
                }
            }
        });
    });
</script>


**backend/مواقف-سيارات.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['name']) && isset($_POST['address']) && isset($_POST['capacity']) && isset($_POST['price'])) {
    // Insert data into database
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $capacity = trim($_POST['capacity']);
    $price = trim($_POST['price']);

    $sql = "INSERT INTO مواقف_سيارات (name, address, capacity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $name, $address, $capacity, $price);
    $stmt->execute();

    echo 'success';
} else {
    echo 'Error creating parking lot';
}
?>