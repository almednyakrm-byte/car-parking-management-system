**create_حجز.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
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

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Create حجز</h1>

    <form id="create-form" class="bg-white rounded shadow-md p-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-slate-900">Date:</label>
            <input type="date" id="date" name="date" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div class="mb-4">
            <label for="time" class="block text-sm font-medium text-slate-900">Time:</label>
            <input type="time" id="time" name="time" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create حجز</button>
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
                url: '../backend/حجز.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_حجز.php';
                    } else {
                        alert('Error creating حجز');
                    }
                }
            });
        });
    });
</script>


**Note:** This code assumes you have jQuery and Tailwind CSS installed. You may need to adjust the CSS classes and JavaScript code to match your specific requirements. Additionally, you should replace `../backend/حجز.php` with the actual URL of your backend script that handles the form submission.