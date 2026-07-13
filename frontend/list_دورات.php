**list_دورات.php**

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
    <title>دورات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
        }
        .emerald-600 {
            color: #008E73;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2 text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">دورات</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_دورات.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 px-4 py-2">العنوان</th>
                    <th class="border border-gray-400 px-4 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        const searchInput = document.querySelector('input[type="search"]');
        const searchButton = document.querySelector('button:last-child');
        const recordsTable = document.getElementById('records');

        searchButton.addEventListener('click', () => {
            const searchTerm = searchInput.value.trim();
            if (searchTerm) {
                fetch('../backend/دورات.php', {
                    method: 'GET',
                    params: { search: searchTerm }
                })
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-400 px-4 py-2">${record.title}</td>
                            <td class="border border-gray-400 px-4 py-2">
                                <a href="edit_دورات.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                                <button class="ml-2 text-red-500 hover:text-red-900" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
            } else {
                fetch('../backend/دورات.php')
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-400 px-4 py-2">${record.title}</td>
                            <td class="border border-gray-400 px-4 py-2">
                                <a href="edit_دورات.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                                <button class="ml-2 text-red-500 hover:text-red-900" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
            }
        });

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/دورات.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP script (`../backend/دورات.php`) that handles GET and DELETE requests for retrieving and deleting records, respectively. You will need to implement this script separately.