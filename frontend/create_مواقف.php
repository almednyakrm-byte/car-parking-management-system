<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Get module slug
$mod_slug = 'مواقف';

// Get current user
$current_user = $_SESSION['username'];

// Page title
$page_title = 'Create مواقف';

// Include header
include 'header.php';
?>

<main class="h-screen flex flex-col items-center justify-center">
    <div class="max-w-md w-full mx-auto p-4 bg-slate-900 rounded-lg shadow-lg">
        <h2 class="text-2xl text-indigo-500 font-bold mb-4">Create مواقف</h2>
        <form id="create-moagaf" method="post">
            <div class="mb-4">
                <label for="title" class="block text-indigo-500 font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 bg-slate-100 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-indigo-500 font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 bg-slate-100 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-indigo-500 font-bold mb-2">Category</label>
                <select id="category" name="category" class="block w-full p-2 bg-slate-100 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select Category</option>
                    <?php
                    // Fetch categories from database
                    $categories = mysqli_query($conn, "SELECT * FROM categories");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-indigo-500 font-bold mb-2">Status</label>
                <select id="status" name="status" class="block w-full p-2 bg-slate-100 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="w-full p-2 bg-indigo-500 text-slate-100 font-bold rounded-lg hover:bg-indigo-700">Create مواقف</button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-moagaf').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/مواقف.php',
                data: $(this).serialize(),
                success: function(response) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>