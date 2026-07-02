**create_الدفعات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Create a new record
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    $query = "INSERT INTO الدفعات (name, description, amount, date) VALUES ('$name', '$description', '$amount', '$date')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo '<script>alert("Record created successfully!");</script>';
        header('Location: list_الدفعات.php');
        exit;
    } else {
        echo '<script>alert("Error creating record!");</script>';
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة دفعة جديدة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-emerald-600 mb-4">إضافة دفعة جديدة</h1>
        <form id="create-form" class="bg-white p-4 rounded shadow-md" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الدفعة:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="اسم الدفعة">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف الدفعة:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="وصف الدفعة"></textarea>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">مبلغ الدفعة:</label>
                <input type="number" id="amount" name="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="مبلغ الدفعة">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">تاريخ الدفعة:</label>
                <input type="date" id="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="تاريخ الدفعة">
            </div>
            <button type="submit" name="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة دفعة جديدة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/الدفعات.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response == 'Record created successfully!') {
                            alert(response);
                            window.location.href = 'list_الدفعات.php';
                        } else {
                            alert(response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `../backend/الدفعات.php` with the actual URL of your backend script that handles the form submission. Also, this code assumes that you have a database connection established and a table named `الدفعات` with the necessary columns.