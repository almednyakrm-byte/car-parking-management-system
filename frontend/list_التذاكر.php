**list_التذاكر.php**

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
    <title>التذاكر</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2c3e50;
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
            background-color: #2c3e50;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            margin: 1rem auto;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-teal-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-emerald-600">التذاكر</h1>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_التذاكر.php'">إضافة تذكرة جديدة</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم التذكرة</th>
                    <th>اسم المريض</th>
                    <th>تاريخ الميلاد</th>
                    <th>تاريخ الإصابة</th>
                    <th>حالة المريض</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const recordsTable = document.getElementById('records-table');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/التذاكر.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.patient_name}</td>
                                <td>${record.birth_date}</td>
                                <td>${record.infection_date}</td>
                                <td>${record.patient_status}</td>
                                <td>
                                    <a href="edit_التذاكر.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/التذاكر.php')
                    .then(response => response.json())
                    .then(data => {
                        recordsTable.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.id}</td>
                                <td>${record.patient_name}</td>
                                <td>${record.birth_date}</td>
                                <td>${record.infection_date}</td>
                                <td>${record.patient_status}</td>
                                <td>
                                    <a href="edit_التذاكر.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف التذكرة؟')) {
                fetch('../backend/التذاكر.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف التذكرة بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف التذكرة');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        searchRecords();
    </script>
</body>
</html>

**backend/التذاكر.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM التذاكر WHERE patient_name LIKE '%$searchQuery%' OR birth_date LIKE '%$searchQuery%' OR infection_date LIKE '%$searchQuery%' OR patient_status LIKE '%$searchQuery%'";
} else {
    $query = "SELECT * FROM التذاكر";
}

// Execute query
$result = $conn->query($query);

// Fetch records
$records = array();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Output records
echo json_encode($records);

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM التذاكر WHERE id = '$id'";
    $conn->query($query);
    echo json_encode(array('success' => true));
}

// Close connection
$conn->close();
?>