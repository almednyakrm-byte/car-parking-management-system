**edit_نظام-الدفع-الإلكتروني.php**

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

// Validate ID
if (empty($id)) {
    header('Location: list_نظام-الدفع-الإلكتروني.php');
    exit;
}

// Fetch existing record details
$url = '../backend/نظام-الدفع-الإلكتروني.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    header('Location: list_نظام-الدفع-الإلكتروني.php');
    exit;
}

// Set page title
$page_title = 'تعديل نظام الدفع الإلكتروني';

// Include header
include 'header.php';

?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم النظام</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف النظام</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $data['description'] ?></textarea>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">حالة النظام</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="active" <?= $data['status'] == 'active' ? 'selected' : '' ?>>نشط</option>
                <option value="inactive" <?= $data['status'] == 'inactive' ? 'selected' : '' ?>>غير نشط</option>
            </select>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">حفظ التعديلات</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/نظام-الدفع-الإلكتروني.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
            document.getElementById('status').value = data.status;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('../backend/نظام-الدفع-الإلكتروني.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_نظام-الدفع-الإلكتروني.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**backend/نظام-الدفع-الإلكتروني.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Validate ID
if (empty($id)) {
    header('Location: list_نظام-الدفع-الإلكتروني.php');
    exit;
}

// Fetch existing record details
$data = get_record($id);

// Check if data exists
if (empty($data)) {
    header('Location: list_نظام-الدفع-الإلكتروني.php');
    exit;
}

// Update record via PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    update_record($id, $name, $description, $status);

    echo json_encode(['success' => true]);
    exit;
}

// Get existing record details
function get_record($id) {
    // Database query to fetch existing record details
    // ...
}

// Update existing record details
function update_record($id, $name, $description, $status) {
    // Database query to update existing record details
    // ...
}
?>