**create_التذاكر.php**

<?php
// Session validation
if (!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<!-- Create تذاكر form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة تذاكر جديد</h2>
    <form id="create-tazker-form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">اسم التذاكر</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">وصف التذاكر</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"></textarea>
        </div>
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">سعر التذاكر</label>
            <input type="number" id="price" name="price" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
        </div>
        <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700">كمية التذاكر</label>
            <input type="number" id="quantity" name="quantity" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
        </div>
        <button type="submit" class="w-full px-4 py-2 text-white bg-emerald-600 rounded-md hover:bg-emerald-700">حفظ التذاكر</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once 'footer.php'; ?>

<!-- AJAX script -->
<script>
    $(document).ready(function() {
        $('#create-tazker-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/التذاكر.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_التذاكر.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**backend/التذاكر.php**

<?php
// Database connection
require_once 'db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Insert data into database
    $query = "INSERT INTO التذاكر (name, description, price, quantity) VALUES ('$name', '$description', '$price', '$quantity')";
    $result = mysqli_query($conn, $query);

    // Check if insertion is successful
    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>