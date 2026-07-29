**create_دفعات-مواقف.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';

// Form data
$mod_slug = 'دفعات-مواقف';
$form_data = array(
    'name' => '',
    'description' => '',
    'price' => '',
    'duration' => '',
    'status' => '',
);

// Form validation
if (isset($_POST['submit'])) {
    $form_data['name'] = $_POST['name'];
    $form_data['description'] = $_POST['description'];
    $form_data['price'] = $_POST['price'];
    $form_data['duration'] = $_POST['duration'];
    $form_data['status'] = $_POST['status'];

    // Validate form data
    if (empty($form_data['name']) || empty($form_data['description']) || empty($form_data['price']) || empty($form_data['duration']) || empty($form_data['status'])) {
        $error = 'Please fill all fields';
    } else {
        // Insert data into database
        $insert_query = "INSERT INTO دفعات_مواقف (name, description, price, duration, status) VALUES ('$form_data[name]', '$form_data[description]', '$form_data[price]', '$form_data[duration]', '$form_data[status]')";
        $insert_result = mysqli_query($conn, $insert_query);

        if ($insert_result) {
            $success = 'Record added successfully';
            header('Location: list_' . $mod_slug . '.php');
            exit;
        } else {
            $error = 'Error adding record';
        }
    }
}

// Include form
include 'form.php';
?>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**form.php**

<!-- Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Add دفعات مواقف</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-white rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter name">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-white rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter description"></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-slate-900">Price</label>
            <input type="number" id="price" name="price" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-white rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter price">
        </div>
        <div class="mb-4">
            <label for="duration" class="block text-sm font-medium text-slate-900">Duration</label>
            <input type="number" id="duration" name="duration" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-white rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter duration">
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-slate-900">Status</label>
            <select id="status" name="status" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-white rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Select status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" name="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-700 focus:ring-indigo-500 focus:border-indigo-500">Add Record</button>
    </form>
</div>

<!-- AJAX script -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/دفعات-مواقف.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        alert(response.success);
                        window.location.href = 'list_' + '<?php echo $mod_slug; ?>.php';
                    } else {
                        alert(response.error);
                    }
                }
            });
        });
    });
</script>


**backend/دفعات-مواقف.php**

<?php
// Include database connection
include 'db.php';

// Process form data
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $status = $_POST['status'];

    // Insert data into database
    $insert_query = "INSERT INTO دفعات_مواقف (name, description, price, duration, status) VALUES ('$name', '$description', '$price', '$duration', '$status')";
    $insert_result = mysqli_query($conn, $insert_query);

    if ($insert_result) {
        $success = 'Record added successfully';
    } else {
        $error = 'Error adding record';
    }

    // Output response
    $response = array('success' => $success, 'error' => $error);
    echo json_encode($response);
}
?>