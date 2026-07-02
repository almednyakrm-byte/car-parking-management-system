**create_تتبع.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'nav.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New تتبع</h2>
        <form id="create-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 font-bold">Name:</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
                </div>
                <div>
                    <label for="description" class="text-slate-900 font-bold">Description:</label>
                    <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Create تتبع</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/تتبع.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_تتبع.php';
                    } else {
                        alert('Error creating تتبع');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**تتبع.php (backend)**

<?php
// Database connection
require_once 'db.php';

// Check if form data is sent
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Insert data into database
    $name = $_POST['name'];
    $description = $_POST['description'];
    $query = "INSERT INTO تتبع (name, description) VALUES ('$name', '$description')";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo 'success';
    } else {
        echo 'Error creating تتبع';
    }
}
?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <!-- Navigation bar -->
    <?php require_once 'nav.php'; ?>
    <!-- Main content -->
    <?php require_once 'content.php'; ?>
    <!-- Footer -->
    <?php require_once 'footer.php'; ?>
</body>
</html>


**footer.php**

<footer class="bg-white text-slate-900 text-sm p-4">
    &copy; 2023 All rights reserved.
</footer>


**nav.php**

<nav class="bg-white shadow-md p-4">
    <ul class="flex justify-between items-center">
        <li><a href="list_تتبع.php" class="text-slate-900 font-bold">List تتبع</a></li>
        <li><a href="create_تتبع.php" class="text-slate-900 font-bold">Create تتبع</a></li>
    </ul>
</nav>


**content.php**

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <!-- Main content here -->
</div>