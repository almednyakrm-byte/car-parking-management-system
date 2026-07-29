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

// Check if data is available
if ($data) {
    // Populate form fields
    $name = $data['name'];
    $description = $data['description'];
} else {
    // Handle error
    echo 'Error fetching data';
    exit;
}

// Include header
include 'header.php';

?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Edit تتبع</h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-900 rounded border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $name ?>">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-900 rounded border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $description ?></textarea>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/تتبع.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Send AJAX PUT request
        fetch('../backend/تتبع.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                description: document.getElementById('description').value
            })
        })
        .then(response => response.json())
        .then(data => {
            // Redirect to list page
            window.location.href = 'list_تتبع.php';
        })
        .catch(error => console.error(error));
    });
</script>


**backend/تتبع.php**

<?php
// Check if ID is provided
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = [
    'id' => $id,
    'name' => 'Example Name',
    'description' => 'Example Description'
];

// Send response
header('Content-Type: application/json');
echo json_encode($data);