**list_دفع.php**

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
    <title>دفع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a;
        }
        .text-indigo-500 {
            color: #6b6bcf;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="max-w-7xl mx-auto p-4">
        <nav class="bg-slate-900 py-2">
            <div class="container mx-auto flex justify-between items-center">
                <a href="index.php" class="text-indigo-500 hover:text-white">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-indigo-500 hover:text-white">مرحباً <?= $_SESSION['username'] ?></span>
                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">تسجيل خروج</button>
                </div>
            </div>
        </nav>
        <div class="container mx-auto p-4 mt-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-indigo-500 text-2xl font-bold">قائمة دفع</h2>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_دفع.php'">إضافة جديد</button>
            </div>
            <div class="flex justify-between items-center mb-4">
                <input type="search" id="search" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="بحث...">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="py-3 px-6">رقم السجل</th>
                        <th scope="col" class="py-3 px-6">المبلغ</th>
                        <th scope="col" class="py-3 px-6">تاريخ الدفع</th>
                        <th scope="col" class="py-3 px-6">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="records">
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/دفع.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    const html = records.map(record => `
                        <tr>
                            <td class="py-4 px-6">${record.id}</td>
                            <td class="py-4 px-6">${record.amount}</td>
                            <td class="py-4 px-6">${record.payment_date}</td>
                            <td class="py-4 px-6">
                                <a href="edit_دفع.php?id=${record.id}" class="text-indigo-500 hover:text-white">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        </tr>
                    `).join('');
                    recordsContainer.innerHTML = html;
                })
                .catch(error => console.error(error));
            } else {
                fetch('../backend/دفع.php')
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    const html = records.map(record => `
                        <tr>
                            <td class="py-4 px-6">${record.id}</td>
                            <td class="py-4 px-6">${record.amount}</td>
                            <td class="py-4 px-6">${record.payment_date}</td>
                            <td class="py-4 px-6">
                                <a href="edit_دفع.php?id=${record.id}" class="text-indigo-500 hover:text-white">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        </tr>
                    `).join('');
                    recordsContainer.innerHTML = html;
                })
                .catch(error => console.error(error));
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف السجل؟')) {
                fetch('../backend/دفع.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        searchRecords();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error(error));
            }
        }

        searchRecords();
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP script (`../backend/دفع.php`) that handles GET and DELETE requests for retrieving and deleting records, respectively. You will need to implement this script separately.