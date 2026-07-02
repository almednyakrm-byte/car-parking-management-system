<?php
session_start();
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
    <title>نظام إدارة مواقف سيارات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-slate-900 text-white h-screen">
    <div class="flex justify-between items-center p-4">
        <h1 class="text-3xl font-bold">نظام إدارة مواقف سيارات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
    </div>
    <div class="flex justify-center items-center h-screen">
        <div class="glassmorphism w-1/2 p-4">
            <h2 class="text-2xl font-bold mb-4">مرحباً <?= $_SESSION['username'] ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-900 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold mb-2">إحصائيات</h3>
                    <div id="stats"></div>
                </div>
                <div class="bg-slate-900 text-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-bold mb-2">روابط سريعة</h3>
                    <ul>
                        <li><a href="#" class="text-white hover:text-indigo-500">مواعف سيارات</a></li>
                        <li><a href="#" class="text-white hover:text-indigo-500">حجز مواقف</a></li>
                        <li><a href="#" class="text-white hover:text-indigo-500">دفعات مواقف</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                const statsElement = document.getElementById('stats');
                statsElement.innerHTML = `
                    <p>عدد المواعف: ${data.parkingLots}</p>
                    <p>عدد الحجوزات: ${data.bookings}</p>
                    <p>عدد دفعات الحجوزات: ${data.payments}</p>
                `;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched dynamically via a JavaScript API call from the backend files.