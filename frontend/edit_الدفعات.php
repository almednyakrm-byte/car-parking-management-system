**edit_الدفعات.php**

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
$url = '../backend/الدفعات.php?id=' . $id;
$record = json_decode(file_get_contents($url), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الدفعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل الدفعات</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم الدفعة</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $record['name'] ?>">
            </div>
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700">مبلغ الدفعة</label>
                <input type="number" id="amount" name="amount" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $record['amount'] ?>">
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">تاريخ الدفعة</label>
                <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $record['date'] ?>">
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">حفظ</button>
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
                    url: '../backend/الدفعات.php',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
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


**Note:** Make sure to replace `<?= $_SESSION['mod_slug'] ?>` with the actual value of `mod_slug` from your session. Also, ensure that the `../backend/الدفعات.php` file is properly configured to handle the PUT request and update the record accordingly.