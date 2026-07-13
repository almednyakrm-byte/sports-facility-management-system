**list_إدارة-الصالة.php**

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
    <title>إدارة الصالة</title>
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
            text-align: left;
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
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">إدارة الصالة</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_إدارة-الصالة.php'">إضافة جديد</button>
        <div class="search-bar mb-4">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الصالة</th>
                    <th>العنوان</th>
                    <th>حذف</th>
                    <th>تعديل</th>
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
            if (searchQuery) {
                fetch('../backend/إدارة-الصالة.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.address}</td>
                            <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                            <td><a href="edit_إدارة-الصالة.php?id=${record.id}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
            } else {
                fetch('../backend/إدارة-الصالة.php')
                .then(response => response.json())
                .then(data => {
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.address}</td>
                            <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                            <td><a href="edit_إدارة-الصالة.php?id=${record.id}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف الصالة؟')) {
                fetch('../backend/إدارة-الصالة.php', {
                    method: 'DELETE',
                    params: { id: id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الصالة بنجاح');
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء حذف الصالة');
                    }
                });
            }
        }

        searchRecords();
    </script>
</body>
</html>

**backend/إدارة-الصالة.php**

<?php
// Assuming you have a database connection established
// and a function to retrieve records from the database

function getRecords($search = null) {
    // Retrieve records from the database
    // and return them as an array
}

function deleteRecord($id) {
    // Delete a record from the database
    // and return a success message
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $search = $_GET['search'] ?? null;
    $records = getRecords($search);
    echo json_encode($records);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];
    $result = deleteRecord($id);
    echo json_encode($result);
}
?>