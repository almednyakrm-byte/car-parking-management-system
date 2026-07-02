**list_الدفعات.php**

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
    <title>الدفعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            margin-right: 10px;
        }
        .header .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .nav-links li {
            margin-right: 20px;
        }
        .header .nav-links a {
            color: #fff;
            text-decoration: none;
        }
        .table-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container table th, .table-container table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table-container table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar input[type="search"] {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .search-bar button[type="submit"] {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .search-bar button[type="submit"]:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">الدفعات</div>
        <ul class="nav-links">
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="profile.php"><?= $_SESSION['username'] ?></a></li>
            <li><a href="logout.php">تسجيل الخروج</a></li>
        </ul>
    </header>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-3xl font-bold">الدفعات</h1>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_الدفعات.php'">إضافة جديد</button>
        </div>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button type="submit" id="search-button">بحث</button>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>اسم الدفعة</th>
                        <th>تاريخ الدفعة</th>
                        <th>حالة الدفعة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <?php
                    // Fetch data from backend
                    $response = json_decode(file_get_contents('../backend/الدفعات.php'), true);
                    foreach ($response as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['name'] . '</td>';
                        echo '<td>' . $row['date'] . '</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '<td>';
                        echo '<a href="edit_الدفعات.php?id=' . $row['id'] . '" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>';
                        echo '<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(' . $row['id'] . ')">حذف</button>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const tableBody = document.getElementById('table-body');

        searchButton.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                fetch('../backend/الدفعات.php?search=' + searchTerm)
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${row.name}</td>
                                <td>${row.date}</td>
                                <td>${row.status}</td>
                                <td>
                                    <a href="edit_الدفعات.php?id=${row.id}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${row.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(tr);
                        });
                    });
            } else {
                fetch('../backend/الدفعات.php')
                    .then(response => response.json())
                    .then(data => {
                        tableBody.innerHTML = '';
                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${row.name}</td>
                                <td>${row.date}</td>
                                <td>${row.status}</td>
                                <td>
                                    <a href="edit_الدفعات.php?id=${row.id}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${row.id})">حذف</button>
                                </td>
                            `;
                            tableBody.appendChild(tr);
                        });
                    });
            }
        });

        // Delete functionality
        function deleteItem(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/الدفعات.php', {
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
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend script (`../backend/الدفعات.php`) that returns a JSON array of data. You will need to modify this script to match your specific database schema and query.