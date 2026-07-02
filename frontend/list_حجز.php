**list_حجز.php**

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
    <title>حجز</title>
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
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مرحباً <?= $_SESSION['username'] ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">حجز</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_حجز.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الحجز</th>
                    <th>اسم المريض</th>
                    <th>تاريخ الحجز</th>
                    <th>حالة الحجز</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= $record['patient_name'] ?></td>
                        <td><?= $record['booking_date'] ?></td>
                        <td><?= $record['status'] ?></td>
                        <td>
                            <a href="edit_حجز.php?id=<?= $record['id'] ?>" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            fetch('../backend/حجز.php', {
                method: 'GET',
                params: {
                    search: searchValue
                }
            })
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.id}</td>
                        <td>${record.patient_name}</td>
                        <td>${record.booking_date}</td>
                        <td>${record.status}</td>
                        <td>
                            <a href="edit_حجز.php?id=${record.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/حجز.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
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

        function fetchRecords() {
            return fetch('../backend/حجز.php', {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => data.records);
        }
    </script>
</body>
</html>


**backend/حجز.php**

<?php
// Database connection
$dsn = 'mysql:host=localhost;dbname=database_name';
$username = 'username';
$password = 'password';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
    exit;
}

// Search records
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $stmt = $pdo->prepare('SELECT * FROM bookings WHERE patient_name LIKE :search OR booking_date LIKE :search');
    $stmt->bindParam(':search', '%' . $searchValue . '%');
    $stmt->execute();
    $records = $stmt->fetchAll();
} else {
    $records = $pdo->query('SELECT * FROM bookings')->fetchAll();
}

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = json_decode(file_get_contents('php://input'), true)['id'];
    $stmt = $pdo->prepare('DELETE FROM bookings WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

// Fetch records
echo json_encode(['records' => $records]);


Note: This code assumes you have a MySQL database with a table named `bookings` containing columns `id`, `patient_name`, `booking_date`, and `status`. You'll need to modify the database connection and query to match your actual database schema.