**create_دفع.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $amount = trim($_POST['amount']);
    $date = trim($_POST['date']);

    if (empty($name) || empty($amount) || empty($date)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert new record into database
        $query = "INSERT INTO دفع (name, amount, date) VALUES ('$name', '$amount', '$date')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_دفع.php');
            exit;
        } else {
            $error = 'Error inserting record';
        }
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create new دفع form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New دفع</h2>

    <?php if (isset($error)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm mb-4" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form id="create-daf" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="amount" class="block text-sm font-medium text-slate-900">Amount:</label>
            <input type="number" id="amount" name="amount" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-gray-300 rounded-md" required>
        </div>

        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-slate-900">Date:</label>
            <input type="date" id="date" name="date" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-gray-300 rounded-md" required>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create دفع</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-daf').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('../backend/دفع.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_دفع.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**Note:** This code assumes that you have a `db.php` file that establishes a connection to your database, and a `header.php` and `footer.php` file that includes the necessary HTML for the page header and footer. You will need to modify the code to match your specific database schema and file structure.