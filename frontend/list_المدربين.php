Here's the PHP file `list_المدربين.php` for managing the 'المدربين' module:


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
    <title>المدربين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #1a1d23;
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
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="header">
        <a href="index.php" class="text-lg font-bold text-emerald-600">المدربين</a>
        <div class="ml-auto">
            <a href="profile.php" class="text-lg font-bold text-teal-500">حسناً, <?= $_SESSION['username'] ?></a>
            <a href="logout.php" class="text-lg font-bold text-teal-500">تسجيل خروج</a>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-3xl font-bold text-emerald-600">قائمة المدربين</h1>
            <a href="create_المدربين.php" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" class="search-bar" placeholder="بحث...">
            <button id="search-btn" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المدرب</th>
                    <th>عنوان المدرب</th>
                    <th>تاريخ الميلاد</th>
                    <th>حالة المدرب</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/المدربين.php';
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                foreach ($data as $record) {
                    ?>
                    <tr>
                        <td><?= $record['اسم_المدرب'] ?></td>
                        <td><?= $record['عنوان_المدرب'] ?></td>
                        <td><?= $record['تاريخ_الولادة'] ?></td>
                        <td><?= $record['حالة_المدرب'] ?></td>
                        <td>
                            <a href="edit_المدربين.php?id=<?= $record['id'] ?>" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search functionality
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        const records = document.getElementById('records');

        searchBtn.addEventListener('click', () => {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/المدربين.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record['اسم_المدرب']}</td>
                                <td>${record['عنوان_المدرب']}</td>
                                <td>${record['تاريخ_الولادة']}</td>
                                <td>${record['حالة_المدرب']}</td>
                                <td>
                                    <a href="edit_المدربين.php?id=${record['id']}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record['id']})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/المدربين.php')
                    .then(response => response.json())
                    .then(data => {
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record['اسم_المدرب']}</td>
                                <td>${record['عنوان_المدرب']}</td>
                                <td>${record['تاريخ_الولادة']}</td>
                                <td>${record['حالة_المدرب']}</td>
                                <td>
                                    <a href="edit_المدربين.php?id=${record['id']}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record['id']})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            }
        });

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/المدربين.php', {
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


This code includes a premium Tailwind UI, session validation, and a search bar that filters elements in real-time. It also includes a delete record functionality using AJAX.