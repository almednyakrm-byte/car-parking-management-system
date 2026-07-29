**list_دفعات-مواقف.php**

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
    <title>دفعات مواقف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .actions .btn {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
        }
        .actions .btn:hover {
            background-color: #1a1d23;
        }
        .search-bar {
            width: 50%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="logo">دفعات مواقف</h1>
        <ul class="nav-links">
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="#"><?php echo $_SESSION['username']; ?></a></li>
            <li><a href="logout.php">تسجيل خروج</a></li>
        </ul>
    </div>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">قائمة دفعات مواقف</h2>
        <button class="btn" onclick="location.href='create_دفعات-مواقف.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="btn" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>تاريخ</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/دفعات-مواقف.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['name'] . '</td>';
                    echo '<td>' . $record['date'] . '</td>';
                    echo '<td>' . $record['status'] . '</td>';
                    echo '<td class="actions">';
                    echo '<button class="btn" onclick="editRecord(' . $record['id'] . ')">تعديل</button>';
                    echo '<button class="btn" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/دفعات-مواقف.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.date}</td>
                            <td>${record.status}</td>
                            <td class="actions">
                                <button class="btn" onclick="editRecord(${record.id})">تعديل</button>
                                <button class="btn" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function editRecord(id) {
            location.href = 'edit_دفعات-مواقف.php?id=' + id;
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
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
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/دفعات-مواقف.php**

<?php
// Fetch records from database
$records = array();
// Replace with your database connection and query
$records = array(
    array('id' => 1, 'name' => 'اسم السجل 1', 'date' => '2022-01-01', 'status' => 'مفعل'),
    array('id' => 2, 'name' => 'اسم السجل 2', 'date' => '2022-01-02', 'status' => 'غير مفعل'),
    // Add more records here
);
header('Content-Type: application/json');
echo json_encode($records);
?>

Note: This code assumes you have a backend script (`backend/دفعات-مواقف.php`) that fetches records from a database and returns them as JSON. You'll need to replace the placeholder code in `backend/دفعات-مواقف.php` with your actual database connection and query.