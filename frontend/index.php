<?php
session_start();

// Check if user is authenticated
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-slate-900">
        <a href="#" class="text-lg font-bold text-indigo-500">نظام إدارة مواقف سيارات</a>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900">
        <div class="glassmorphism-card w-1/2 p-4">
            <h2 class="text-2xl font-bold text-indigo-500">مرحباً</h2>
            <p class="text-lg text-gray-300">مرحباً بكم في نظام إدارة مواقف سيارات</p>
        </div>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900">
        <div class="glassmorphism-card w-1/2 p-4">
            <h2 class="text-2xl font-bold text-indigo-500">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-indigo-500">مواعيد الحجز</h3>
                    <p id="booking-count" class="text-lg text-gray-300"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-indigo-500">مواعيد الدفع</h3>
                    <p id="payment-count" class="text-lg text-gray-300"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-indigo-500">مواعيد التتبع</h3>
                    <p id="tracking-count" class="text-lg text-gray-300"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900">
        <div class="glassmorphism-card w-1/2 p-4">
            <h2 class="text-2xl font-bold text-indigo-500">روابط سريعة</h2>
            <ul class="list-none p-0 m-0">
                <li class="py-2">
                    <a href="#" class="text-lg text-gray-300 hover:text-indigo-500">مواعيد</a>
                </li>
                <li class="py-2">
                    <a href="#" class="text-lg text-gray-300 hover:text-indigo-500">حجز</a>
                </li>
                <li class="py-2">
                    <a href="#" class="text-lg text-gray-300 hover:text-indigo-500">دفع</a>
                </li>
                <li class="py-2">
                    <a href="#" class="text-lg text-gray-300 hover:text-indigo-500">تتبع</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('booking-count').textContent = data.booking_count;
                document.getElementById('payment-count').textContent = data.payment_count;
                document.getElementById('tracking-count').textContent = data.tracking_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. It also includes a dynamic stats grid that fetches data from a backend API using JavaScript.

Please note that you need to replace `/api/stats` with the actual URL of your backend API that returns the stats data. Also, you need to create a `logout.php` file to handle the logout functionality.

This code assumes that you have a backend API that returns the stats data in JSON format. You can modify the JavaScript code to match the structure of your API response.

Also, this code uses a simple session check to authenticate the user. You should replace this with a more secure authentication mechanism, such as using a library like Laravel's Sanctum or a custom authentication system.

Please note that this is just a basic example and you should modify it to fit your specific requirements.