**create_حجز-مواقف.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $errors = [];

    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    $description = trim($_POST['description']);
    if (empty($description)) {
        $errors[] = 'Description is required';
    }

    $price = trim($_POST['price']);
    if (empty($price) || !is_numeric($price)) {
        $errors[] = 'Price is required and must be a number';
    }

    $capacity = trim($_POST['capacity']);
    if (empty($capacity) || !is_numeric($capacity)) {
        $errors[] = 'Capacity is required and must be a number';
    }

    if (empty($errors)) {
        // Insert new record
        $sql = "INSERT INTO حجز_مواقف (name, description, price, capacity) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $price, $capacity]);

        // Redirect back to list page
        header('Location: list_حجز-مواقف.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create new parking record form -->
<div class="max-w-md mx-auto p-4 bg-white rounded shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New Parking Record</h2>

    <form id="create-parking-record-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-slate-900 border border-slate-300 rounded" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 text-slate-900 border border-slate-300 rounded" required></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-slate-900 text-sm font-bold mb-2">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 text-slate-900 border border-slate-300 rounded" required>
        </div>

        <div class="mb-4">
            <label for="capacity" class="block text-slate-900 text-sm font-bold mb-2">Capacity:</label>
            <input type="number" id="capacity" name="capacity" class="block w-full p-2 text-slate-900 border border-slate-300 rounded" required>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Record</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // Submit form via AJAX
    document.getElementById('create-parking-record-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('../backend/حجز-مواقف.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_حجز-مواقف.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/حجز-مواقف.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $errors = [];

    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $capacity = trim($_POST['capacity']);

    if (empty($name)) {
        $errors[] = 'Name is required';
    }

    if (empty($description)) {
        $errors[] = 'Description is required';
    }

    if (empty($price) || !is_numeric($price)) {
        $errors[] = 'Price is required and must be a number';
    }

    if (empty($capacity) || !is_numeric($capacity)) {
        $errors[] = 'Capacity is required and must be a number';
    }

    if (empty($errors)) {
        // Insert new record
        $sql = "INSERT INTO حجز_مواقف (name, description, price, capacity) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $description, $price, $capacity]);

        // Return success response
        echo json_encode(['success' => true]);
        exit;
    } else {
        // Return error response
        echo json_encode(['error' => implode('<br>', $errors)]);
        exit;
    }
}