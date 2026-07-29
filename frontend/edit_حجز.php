**edit_حجز.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/حجز.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit حجز';
$mod_slug = 'حجز';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<!-- Edit حجز form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4"><?= $page_title ?></h2>
    <form id="edit-form" class="space-y-6">
        <div>
            <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="<?= $data['title'] ?>">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" rows="4"><?= $data['description'] ?></textarea>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save Changes</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<script>
    // Fetch existing record details via GET
    fetch('../backend/حجز.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('title').value = data.title;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('../backend/حجز.php', {
            method: 'PUT',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>


**backend/حجز.php**

<?php
// Check if id exists
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Invalid id']);
    exit;
}

// Get id
$id = $_GET['id'];

// Check if record exists
$record = get_record($id);
if (empty($record)) {
    echo json_encode(['error' => 'Record not found']);
    exit;
}

// Update record
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    update_record($id, $title, $description);
    echo json_encode(['success' => true]);
} else {
    // Return existing record details
    echo json_encode($record);
}

// Helper functions
function get_record($id) {
    // Database query to get record by id
    // ...
}

function update_record($id, $title, $description) {
    // Database query to update record
    // ...
}
?>