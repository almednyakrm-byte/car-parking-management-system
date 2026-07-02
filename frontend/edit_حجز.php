<!-- edit_حجز.php -->

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/حجز.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit حجز</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-slate-900 {
            background-color: #1A1D23 !important;
        }
        .text-indigo-500 {
            color: #6B7280 !important;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-indigo-500">Edit حجز</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $data['name']; ?>" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $data['email']; ?>" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo $data['phone']; ?>" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/حجز.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_حجز.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>



// backend/حجز.php

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '1234567890'
];

// Output data as JSON
echo json_encode($data);