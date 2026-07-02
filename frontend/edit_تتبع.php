**edit_تتبع.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/تتبع.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set form data
$form_data = [
    'id' => $id,
    'field1' => $data['field1'],
    'field2' => $data['field2'],
    'field3' => $data['field3'],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit تتبع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">Edit تتبع</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="field1" class="block text-sm font-medium text-slate-900">Field 1</label>
                <input type="text" id="field1" name="field1" value="<?= $form_data['field1'] ?>" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="field2" class="block text-sm font-medium text-slate-900">Field 2</label>
                <input type="text" id="field2" name="field2" value="<?= $form_data['field2'] ?>" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="field3" class="block text-sm font-medium text-slate-900">Field 3</label>
                <input type="text" id="field3" name="field3" value="<?= $form_data['field3'] ?>" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-md hover:bg-indigo-700 focus:ring-indigo-500 focus:border-indigo-500">Update تتبع</button>
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
                    url: '../backend/تتبع.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating تتبع.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr, status, error);
                    }
                });
            });
        });
    </script>
</body>
</html>

Note: This code assumes that you have a `backend/تتبع.php` file that handles the PUT request and returns a JSON response with a `success` property indicating whether the update was successful. You will need to implement this file separately.