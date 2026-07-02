// edit_حجز-مواقف.php

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/حجز-مواقف.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل حجز مواقف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">تعديل حجز مواقف</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700">اسم الحجز:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-700">وصف الحجز:</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 border border-gray-300 rounded-md"><?= $data['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-slate-700">تاريخ الحجز:</label>
                <input type="date" id="date" name="date" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $data['date'] ?>">
            </div>
            <div class="mb-4">
                <label for="time" class="block text-sm font-medium text-slate-700">ساعة الحجز:</label>
                <input type="time" id="time" name="time" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $data['time'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/حجز-مواقف.php',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            window.location.href = 'list_حجز-مواقف.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>



// backend/حجز-مواقف.php

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Check if id is numeric
if (!is_numeric($id)) {
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$query = "SELECT * FROM حجز_مواقف WHERE id = '$id'";
$result = $conn->query($query);

// Fetch data
$data = $result->fetch_assoc();

// Close connection
$conn->close();

// Output data
echo json_encode($data);
?>



// backend/حجز-مواقف.php (update record)

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Check if id is numeric
if (!is_numeric($id)) {
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$description = $_POST['description'];
$date = $_POST['date'];
$time = $_POST['time'];

// Update record
$query = "UPDATE حجز_مواقف SET name = '$name', description = '$description', date = '$date', time = '$time' WHERE id = '$id'";
$conn->query($query);

// Close connection
$conn->close();

// Output success message
echo 'success';
?>