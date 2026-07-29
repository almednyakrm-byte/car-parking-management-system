**create_تتبع.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include Tailwind CSS
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php
// Include navigation
include 'navigation.php';
?>

<div class="container mx-auto p-4 mt-4">
    <h1 class="text-3xl font-bold text-slate-900">Create New تتبع</h1>
    <form id="create-form" class="bg-white p-4 mt-4 shadow-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter name">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter description"></textarea>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-slate-900">Status:</label>
                <select id="status" name="status" class="block w-full p-2 mt-1 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500">
                    <option value="">Select status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label for="created_at" class="block text-sm font-medium text-slate-900">Created At:</label>
                <input type="datetime-local" id="created_at" name="created_at" class="block w-full p-2 mt-1 text-sm text-slate-900 placeholder:text-slate-400 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500" placeholder="Enter created at">
            </div>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Create تتبع</button>
    </form>
</div>

<?php
// Include footer
include 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/تتبع.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_تتبع.php';
                    } else {
                        alert('Error creating تتبع');
                    }
                }
            });
        });
    });
</script>


**تتبع.php (backend)**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $created_at = $_POST['created_at'];

    // Insert data into database
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO تتبع (name, description, status, created_at) VALUES ('$name', '$description', '$status', '$created_at')";
    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Error creating تتبع';
    }

    $conn->close();
}
?>

Note: This is a basic example and you should adjust the code to fit your specific needs. Also, make sure to replace the placeholders with your actual database credentials and table name.