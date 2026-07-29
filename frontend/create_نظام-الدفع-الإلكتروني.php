**create_نظام-الدفع-الإلكتروني.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';

// Include form script
include 'form_script.php';

// Form data
$data = array(
    'name' => '',
    'description' => '',
    'type' => '',
    'status' => '',
);

// Form validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['name'] = $_POST['name'];
    $data['description'] = $_POST['description'];
    $data['type'] = $_POST['type'];
    $data['status'] = $_POST['status'];

    // AJAX request to create new record
    $ajax_url = '../backend/نظام-الدفع-الإلكتروني.php';
    $ajax_data = array(
        'name' => $data['name'],
        'description' => $data['description'],
        'type' => $data['type'],
        'status' => $data['status'],
    );

    $ajax_response = ajax_request($ajax_url, $ajax_data);

    if ($ajax_response['success']) {
        header('Location: list_نظام-الدفع-الإلكتروني.php');
        exit;
    } else {
        echo '<div class="alert alert-danger">' . $ajax_response['message'] . '</div>';
    }
}

// Display form
?>

<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 text-lg font-bold mb-2">إضافة نظام الدفع الإلكتروني</h2>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="text-slate-900 text-sm font-bold">اسم النظام</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="text-slate-900 text-sm font-bold">وصف النظام</label>
                <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500"><?= $data['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="type" class="text-slate-900 text-sm font-bold">نوع النظام</label>
                <select id="type" name="type" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    <option value="">اختر نوع النظام</option>
                    <option value="1">نظام الدفع الإلكتروني</option>
                    <option value="2">نظام الدفع عبر البنك</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="status" class="text-slate-900 text-sm font-bold">حالة النظام</label>
                <select id="status" name="status" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
                    <option value="">اختر حالة النظام</option>
                    <option value="1">نشط</option>
                    <option value="2">مغلق</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
        </form>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>

<script>
    // AJAX request to create new record
    function ajax_request(url, data) {
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(response) {
                if (response.success) {
                    alert('تم إضافة النظام بنجاح');
                    window.location.href = 'list_نظام-الدفع-الإلكتروني.php';
                } else {
                    alert(response.message);
                }
            }
        });
    }

    // Initialize form
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            ajax_request('../backend/نظام-الدفع-الإلكتروني.php', formData);
        });
    });
</script>


**form_script.php**

<?php
// Include form script
?>


**header.php**

<?php
// Include header
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام الدفع الإلكتروني</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <header class="bg-white shadow-md p-4">
        <!-- Header content -->
    </header>
    <main>
        <!-- Main content -->
    </main>
    <footer class="bg-white shadow-md p-4">
        <!-- Footer content -->
    </footer>
</body>
</html>


**footer.php**

<?php
// Include footer
?>


**navigation.php**

<?php
// Include navigation
?>
<nav class="bg-white shadow-md p-4">
    <!-- Navigation content -->
</nav>


Note: This code assumes that you have already set up a basic HTML structure and have included the necessary CSS and JavaScript files. You will need to modify the code to fit your specific needs and requirements. Additionally, you will need to create the `ajax_request` function in your JavaScript code to handle the AJAX request to create a new record.