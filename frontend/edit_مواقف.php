**edit_مواقف.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مواقف.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if (isset($data['error'])) {
    echo 'Error: ' . $data['error'];
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
    <title>Edit مواقف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit مواقف</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="field1" class="block text-sm font-bold text-gray-700">Field 1</label>
                <input type="text" id="field1" name="field1" class="w-full p-2 mb-2 text-sm text-gray-700 border border-gray-300 rounded-lg" value="<?= $form_data['field1'] ?>">
            </div>
            <div class="mb-4">
                <label for="field2" class="block text-sm font-bold text-gray-700">Field 2</label>
                <input type="text" id="field2" name="field2" class="w-full p-2 mb-2 text-sm text-gray-700 border border-gray-300 rounded-lg" value="<?= $form_data['field2'] ?>">
            </div>
            <div class="mb-4">
                <label for="field3" class="block text-sm font-bold text-gray-700">Field 3</label>
                <input type="text" id="field3" name="field3" class="w-full p-2 mb-2 text-sm text-gray-700 border border-gray-300 rounded-lg" value="<?= $form_data['field3'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update</button>
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
                    url: '../backend/مواقف.php',
                    data: formData,
                    success: function(data) {
                        if (data.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + data.error);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مواقف.php**

<?php
// Check if ID is provided
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not provided']);
    exit;
}

// Get the ID
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM مواقف WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Check if record exists
if (empty($row)) {
    echo json_encode(['error' => 'Record not found']);
    exit;
}

// Prepare data for AJAX response
$data = [
    'field1' => $row['field1'],
    'field2' => $row['field2'],
    'field3' => $row['field3'],
];

// Check if request method is PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get form data
    $formData = $_POST;

    // Update record
    $query = "UPDATE مواقف SET field1 = '$formData[field1]', field2 = '$formData[field2]', field3 = '$formData[field3]' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    // Check if update was successful
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Update failed']);
    }
} else {
    echo json_encode($data);
}