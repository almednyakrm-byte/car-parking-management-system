**edit_دفعات-مواقف.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Validate id
if (empty($id)) {
    header('Location: list_دفعات-مواقف.php');
    exit;
}

// Fetch existing record details
$url = '../backend/دفعات-مواقف.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if (empty($data)) {
    header('Location: list_دفعات-مواقف.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit دفعات مواقف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit دفعات مواقف</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-md hover:bg-indigo-600 focus:ring-indigo-500 focus:border-indigo-500">Update</button>
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
                    url: '../backend/دفعات-مواقف.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_دفعات-مواقف.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating record: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/دفعات-مواقف.php**

<?php
// Get id from URL
$id = $_GET['id'];

// Validate id
if (empty($id)) {
    echo json_encode(array('success' => false));
    exit;
}

// Fetch existing record details
$data = array(
    'name' => 'Existing Name',
    'description' => 'Existing Description'
);

// Simulate database query
echo json_encode($data);