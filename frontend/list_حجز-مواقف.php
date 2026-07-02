**list_حجز-مواقف.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حجز مواقف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            padding: 1rem;
            text-align: center;
        }
        .header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 1rem;
        }
        .header nav a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1f2937;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button[type="submit"] {
            background-color: #1f2937;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <nav>
            <a href="index.php">الرئيسية</a>
            <span>مرحباً <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php">تسجيل خروج</a>
        </nav>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">حجز مواقف</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_حجز-مواقف.php'">إضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" placeholder="بحث...">
            <button type="submit" class="search-bar">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الموقف</th>
                    <th>وصف الموقف</th>
                    <th>حالة الحجز</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be fetched from AJAX call -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.querySelector('.search-bar input[type="search"]');
        const searchButton = document.querySelector('.search-bar button[type="submit"]');
        const recordsTable = document.querySelector('#records');

        searchButton.addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                fetch('../backend/حجز-مواقف.php', {
                    method: 'GET',
                    params: { search: searchTerm }
                })
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    const tableHtml = records.map(record => `
                        <tr>
                            <td>${record.اسم_الموقف}</td>
                            <td>${record.وصف_الموقف}</td>
                            <td>${record.حالة_الحجز}</td>
                            <td>
                                <a href="edit_حجز-مواقف.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        </tr>
                    `).join('');
                    recordsTable.innerHTML = tableHtml;
                })
                .catch(error => console.error(error));
            } else {
                fetch('../backend/حجز-مواقف.php')
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    const tableHtml = records.map(record => `
                        <tr>
                            <td>${record.اسم_الموقف}</td>
                            <td>${record.وصف_الموقف}</td>
                            <td>${record.حالة_الحجز}</td>
                            <td>
                                <a href="edit_حجز-مواقف.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        </tr>
                    `).join('');
                    recordsTable.innerHTML = tableHtml;
                })
                .catch(error => console.error(error));
            }
        });

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا الموقف؟')) {
                fetch('../backend/حجز-مواقف.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الموقف بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        fetch('../backend/حجز-مواقف.php')
        .then(response => response.json())
        .then(data => {
            const records = data.records;
            const tableHtml = records.map(record => `
                <tr>
                    <td>${record.اسم_الموقف}</td>
                    <td>${record.وصف_الموقف}</td>
                    <td>${record.حالة_الحجز}</td>
                    <td>
                        <a href="edit_حجز-مواقف.php?id=${record.id}" class="text-blue-500 hover:text-blue-700">تعديل</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                </tr>
            `).join('');
            recordsTable.innerHTML = tableHtml;
        })
        .catch(error => console.error(error));
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP script (`../backend/حجز-مواقف.php`) that handles the GET and DELETE requests. You'll need to implement this script to fetch and delete records from your database.