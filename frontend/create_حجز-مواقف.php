**create_حجز-مواقف.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $parking_type = trim($_POST['parking_type']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($parking_type)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO حجز_مواقف (name, email, phone, address, parking_type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $phone, $address, $parking_type);
        $stmt->execute();
        $stmt->close();

        // Redirect back to list page
        header('Location: list_حجز-مواقف.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-4">Create حجز مواقف</h1>

    <!-- Form -->
    <form action="" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
            <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address</label>
            <textarea id="address" name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>

        <div class="mb-4">
            <label for="parking_type" class="block text-gray-700 text-sm font-bold mb-2">Parking Type</label>
            <select id="parking_type" name="parking_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Select Parking Type</option>
                <option value="Outdoor">Outdoor</option>
                <option value="Indoor">Indoor</option>
            </select>
        </div>

        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</main>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_حجز-مواقف.js**
javascript
$(document).ready(function() {
    // Submit form via AJAX
    $('form').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: '../backend/حجز-مواقف.php',
            data: formData,
            success: function(response) {
                if (response == 'success') {
                    window.location.href = 'list_حجز-مواقف.php';
                } else {
                    alert('Error creating record');
                }
            }
        });
    });
});


**Note:** Make sure to replace `../backend/حجز-مواقف.php` with the actual PHP file that handles the form submission and database insertion. Also, update the `list_حجز-مواقف.php` file to handle the redirect after a successful form submission.