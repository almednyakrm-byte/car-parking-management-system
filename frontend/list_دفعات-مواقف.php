**list_دفعات-مواقف.php**

<?php
session_start();

// Redirect to login.php if not authenticated
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
    <title>دفعات مواقف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #2d3748;
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
            background-color: #2d3748;
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #3b4453;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-white">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-white">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">دفعات مواقف</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_دفعات-مواقف.php'">إضافة جديد</button>
        <div class="search-bar mt-4">
            <input type="search" id="search" placeholder="بحث...">
            <button type="submit" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>رقم السجل</th>
                    <th>اسم المالك</th>
                    <th>رقم الهاتف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $response = file_get_contents('../backend/دفعات-مواقف.php');
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['id'] . '</td>';
                    echo '<td>' . $record['name'] . '</td>';
                    echo '<td>' . $record['phone'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_دفعات-مواقف.php?id=' . $record['id'] . '" class="text-indigo-500 hover:text-indigo-700">تعديل</a>';
                    echo '<button class="text-red-500 hover:text-red-700" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/دفعات-مواقف.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(records => {
                        const recordsElement = document.getElementById('records');
                        recordsElement.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.name}</td>
                                <td>${record.phone}</td>
                                <td>
                                    <a href="edit_دفعات-مواقف.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsElement.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/دفعات-مواقف.php')
                    .then(response => response.json())
                    .then(records => {
                        const recordsElement = document.getElementById('records');
                        recordsElement.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.name}</td>
                                <td>${record.phone}</td>
                                <td>
                                    <a href="edit_دفعات-مواقف.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsElement.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف السجل؟')) {
                fetch('../backend/دفعات-مواقف.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code uses the Fetch API to fetch records from the backend and display them in a table. It also includes a search bar that filters the records in real-time. The delete button sends a DELETE request to the backend to delete the record.