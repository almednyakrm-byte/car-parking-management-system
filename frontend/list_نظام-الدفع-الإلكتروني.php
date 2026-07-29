<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام الدفع الإلكتروني</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white">
    <header class="bg-indigo-500 py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-4"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="bg-indigo-700 hover:bg-indigo-800 py-2 px-4 rounded">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">نظام الدفع الإلكتروني</h1>
        <div class="flex justify-between mb-4">
            <a href="create_نظام-الدفع-الإلكتروني.php" class="bg-indigo-500 hover:bg-indigo-700 py-2 px-4 rounded">إضافة جديد</a>
            <input type="search" id="search" class="py-2 pl-10 text-sm text-gray-200 bg-slate-800 rounded" placeholder="بحث...">
        </div>
        <table id="records" class="w-full text-center border border-slate-700">
            <thead class="bg-slate-800">
                <tr>
                    <th class="py-2">الاسم</th>
                    <th class="py-2">الوصف</th>
                    <th class="py-2">العمليات</th>
                </tr>
            </thead>
            <tbody id="records-body">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>

    <script>
        const searchInput = document.getElementById('search');
        const recordsBody = document.getElementById('records-body');

        // Load records
        fetch('../backend/نظام-الدفع-الإلكتروني.php')
            .then(response => response.json())
            .then(data => {
                const recordsHtml = data.map(record => `
                    <tr>
                        <td>${record.name}</td>
                        <td>${record.description}</td>
                        <td>
                            <a href="edit_نظام-الدفع-الإلكتروني.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    </tr>
                `).join('');
                recordsBody.innerHTML = recordsHtml;
            });

        // Search functionality
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = recordsBody.getElementsByTagName('tr');

            for (const row of rows) {
                const nameCell = row.cells[0];
                const descriptionCell = row.cells[1];

                if (nameCell.textContent.toLowerCase().includes(searchValue) || descriptionCell.textContent.toLowerCase().includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/نظام-الدفع-الإلكتروني.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('خطأ في حذف السجل');
                        }
                    });
            }
        }
    </script>
</body>
</html>