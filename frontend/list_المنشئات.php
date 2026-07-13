**list_المنشئات.php**

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
    <title>المنشئات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2c3e50;
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
            text-align: center;
        }
        .table th {
            background-color: #2c3e50;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
        .btn {
            background-color: #2c3e50;
            color: #fff;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #1a1d23;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-lg font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-lg font-bold text-red-600">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">المنشئات</h1>
        <button class="btn" onclick="location.href='create_المنشئات.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="btn" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>اسم المنشئة</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to load records
        async function loadRecords() {
            const response = await fetch('../backend/المنشئات.php');
            const data = await response.json();
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach((record, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${record.اسم_المنشئة}</td>
                    <td><button class="btn btn-danger" onclick="deleteRecord(${record.id})">حذف</button></td>
                    <td><a href="edit_المنشئات.php?id=${record.id}" class="btn btn-primary">تعديل</a></td>
                `;
                records.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/المنشئات.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach((record, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${record.اسم_المنشئة}</td>
                                <td><button class="btn btn-danger" onclick="deleteRecord(${record.id})">حذف</button></td>
                                <td><a href="edit_المنشئات.php?id=${record.id}" class="btn btn-primary">تعديل</a></td>
                            `;
                            records.appendChild(row);
                        });
                    });
            } else {
                loadRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                const response = await fetch('../backend/المنشئات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    loadRecords();
                } else {
                    alert('حدث خطأ أثناء الحذف');
                }
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a search bar, and AJAX calls to fetch and delete records. The `loadRecords()` function is called on page load to load the initial records, and the `searchRecords()` function is called when the search bar is used. The `deleteRecord()` function is called when the delete button is clicked, and it sends a DELETE request to the backend to delete the record.