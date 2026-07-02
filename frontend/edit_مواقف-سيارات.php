<?php
// Session validation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$record_id = $_GET['id'];

// Include database connection
require_once '../backend/db.php';

// Fetch record details
$query = "SELECT * FROM مواقف_سيارات WHERE id = '$record_id'";
$result = mysqli_query($conn, $query);
$record = mysqli_fetch_assoc($result);

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل موقف سيارات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-slate-100 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-indigo-500 mb-4">تعديل موقف سيارات</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">اسم الموقف</label>
                <input type="text" id="name" name="name" value="<?php echo $record['name']; ?>" class="block w-full p-2 pl-10 text-sm text-slate-900 border border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-slate-900">الموقع</label>
                <input type="text" id="location" name="location" value="<?php echo $record['location']; ?>" class="block w-full p-2 pl-10 text-sm text-slate-900 border border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="capacity" class="block text-sm font-medium text-slate-900">السعة</label>
                <input type="number" id="capacity" name="capacity" value="<?php echo $record['capacity']; ?>" class="block w-full p-2 pl-10 text-sm text-slate-900 border border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="w-full p-2 text-sm text-white bg-indigo-500 rounded-lg hover:bg-indigo-700 focus:ring-indigo-500 focus:border-indigo-500">تعديل</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/مواقف-سيارات.php', {
                method: 'PUT',
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    window.location.href = 'list_مواقف-سيارات.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch((error) => console.error(error));
        });
    </script>
</body>
</html>