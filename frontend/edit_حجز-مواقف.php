**edit_حجز-مواقف.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/حجز-مواقف.php?id=' . $id;
$existingRecord = json_decode(file_get_contents($url), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found.';
    exit;
}

// Set page title and content
$pageTitle = 'Edit حجز مواقف';
$pageContent = '
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <h1 class="text-3xl font-bold leading-tight text-slate-900">' . $pageTitle . '</h1>
        <form id="edit-form" class="mt-6 space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="' . $existingRecord['name'] . '">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="4">' . $existingRecord['description'] . '</textarea>
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save Changes</button>
        </form>
    </div>
';

// Include layout
include 'layout.php';
?>

<script>
    // Fetch existing record details via GET
    fetch("../backend/حجز-مواقف.php?id=<?php echo $id; ?>")
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById("name").value = data.name;
            document.getElementById("description").value = data.description;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById("edit-form").addEventListener("submit", function(event) {
        event.preventDefault();

        // Send AJAX PUT request
        fetch("../backend/حجز-مواقف.php", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: <?php echo $id; ?>,
                name: document.getElementById("name").value,
                description: document.getElementById("description").value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to list page
                window.location.href = "list_حجز-مواقف.php";
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/حجز-مواقف.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Check if ID is set
if (!isset($id)) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$stmt = $conn->prepare("SELECT * FROM حجز_مواقف WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch existing record details
$existingRecord = $result->fetch_assoc();

// Close connection
$conn->close();

// Output existing record details
echo json_encode($existingRecord);
?>


**backend/حجز-مواقف.php (PUT request handler)**

<?php
// Get ID, name, and description from request body
$id = $_POST['id'];
$name = $_POST['name'];
$description = $_POST['description'];

// Check if ID is set
if (!isset($id)) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update existing record
$stmt = $conn->prepare("UPDATE حجز_مواقف SET name = ?, description = ? WHERE id = ?");
$stmt->bind_param("ssi", $name, $description, $id);
$stmt->execute();

// Check if update was successful
if ($stmt->affected_rows == 1) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('error' => 'Update failed'));
}

// Close connection
$conn->close();
?>