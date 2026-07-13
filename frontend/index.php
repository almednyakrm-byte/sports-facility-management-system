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
    <title>نظام إدارة صالة رياضية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-emerald-600 text-white">
        <h1 class="text-3xl font-bold">نظام إدارة صالة رياضية</h1>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
    </div>
    <div class="flex justify-center items-center p-4">
        <div class="glassmorphism-card w-1/2 p-4">
            <h2 class="text-2xl font-bold mb-4">مرحباً <?= $_SESSION['username'] ?></h2>
            <div class="flex justify-between items-center mb-4">
                <button class="bg-emerald-600 hover:bg-emerald-800 text-white font-bold py-2 px-4 rounded" onclick="location.href='members.php'">أعضاء</button>
                <button class="bg-emerald-600 hover:bg-emerald-800 text-white font-bold py-2 px-4 rounded" onclick="location.href='events.php'">فعاليات</button>
                <button class="bg-emerald-600 hover:bg-emerald-800 text-white font-bold py-2 px-4 rounded" onclick="location.href='courses.php'">دورات</button>
                <button class="bg-emerald-600 hover:bg-emerald-800 text-white font-bold py-2 px-4 rounded" onclick="location.href='hall-management.php'">إدارة الصالة</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                <div class="glassmorphism-card p-4">
                    <h3 class="text-lg font-bold mb-2">إجمالي أعضاء</h3>
                    <p id="total-members" class="text-2xl font-bold"></p>
                </div>
                <div class="glassmorphism-card p-4">
                    <h3 class="text-lg font-bold mb-2">إجمالي فعاليات</h3>
                    <p id="total-events" class="text-2xl font-bold"></p>
                </div>
                <div class="glassmorphism-card p-4">
                    <h3 class="text-lg font-bold mb-2">إجمالي دورات</h3>
                    <p id="total-courses" class="text-2xl font-bold"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-members').innerText = data.total_members;
                document.getElementById('total-events').innerText = data.total_events;
                document.getElementById('total-courses').innerText = data.total_courses;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched dynamically via a JavaScript API call from the backend files. The color palette used is emerald-600 and teal-500.