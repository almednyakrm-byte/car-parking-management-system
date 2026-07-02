**edit_التذاكر.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/التذاكر.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set form fields
$title = $data['title'];
$description = $data['description'];
$status = $data['status'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit التذاكر</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-emerald-600">Edit التذاكر</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="title" class="block text-sm font-bold text-gray-700">Title:</label>
                <input type="text" id="title" name="title" class="block w-full p-2 border border-gray-300 rounded" value="<?php echo $title; ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold text-gray-700">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-gray-300 rounded h-20"><?php echo $description; ?></textarea>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-bold text-gray-700">Status:</label>
                <select id="status" name="status" class="block w-full p-2 border border-gray-300 rounded">
                    <option value="active" <?php if ($status == 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if ($status == 'inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
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
                    url: '../backend/التذاكر.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_التذاكر.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/التذاكر.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'ID not set.'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM التذاكر WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if data exists
if (empty($data)) {
    echo json_encode(array('success' => false, 'message' => 'Record not found.'));
    exit;
}

// Update record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents('php://input'), $putData);
    $title = $putData['title'];
    $description = $putData['description'];
    $status = $putData['status'];

    $query = "UPDATE التذاكر SET title = '$title', description = '$description', status = '$status' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(array('success' => true, 'message' => 'Record updated successfully.'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error updating record.'));
    }
} else {
    echo json_encode($data);
}
?>

Note: This code assumes you have a MySQL database connection established in the `backend/التذاكر.php` file. Also, make sure to replace `list_التذاكر.php` with the actual URL of the page you want to redirect to after updating the record.