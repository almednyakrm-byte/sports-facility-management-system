**list_اشتراكات-الاعضاء.php**

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
    <title>اشتراكات الاعضاء</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">اشتراكات الاعضاء</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_اشتراكات-الاعضاء.php'">اضافة جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" class="w-full p-2 border border-gray-400 rounded" placeholder="بحث" id="search">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">اسم العضو</th>
                    <th class="border border-gray-400 p-2">نوع الاشتراك</th>
                    <th class="border border-gray-400 p-2">تاريخ الاشتراك</th>
                    <th class="border border-gray-400 p-2">تاريخ النهاية</th>
                    <th class="border border-gray-400 p-2">حذف</th>
                    <th class="border border-gray-400 p-2">تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be fetched from backend using AJAX -->
            </tbody>
        </table>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/fetch@2.0.3/dist/fetch.min.js"></script>
    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/اشتراكات-الاعضاء.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-400 p-2">${record.اسم_العضو}</td>
                            <td class="border border-gray-400 p-2">${record.نوع_الاشتراك}</td>
                            <td class="border border-gray-400 p-2">${record.تاريخ_الاشتراك}</td>
                            <td class="border border-gray-400 p-2">${record.تاريخ_النهاية}</td>
                            <td class="border border-gray-400 p-2">
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td class="border border-gray-400 p-2">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_اشتراكات-الاعضاء.php?id=${record.id}'">تعديل</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/اشتراكات-الاعضاء.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        searchRecords();
                    } else {
                        alert('حدث خطأ أثناء الحذف');
                    }
                });
            }
        }

        searchRecords();
    </script>
</body>
</html>

**backend/اشتراكات-الاعضاء.php**

<?php
// Assuming you have a database connection established
// Fetch all records from database
$records = array();
$records = getRecordsFromDatabase();

// If search query is provided, filter records
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = filterRecords($records, $search);
}

// Output records in JSON format
header('Content-Type: application/json');
echo json_encode($records);

// Helper functions
function getRecordsFromDatabase() {
    // Assuming you have a function to fetch records from database
    // Replace this with your actual database query
    return array(
        array('id' => 1, 'اسم_العضو' => 'عضو 1', 'نوع_الاشتراك' => 'اشتراك 1', 'تاريخ_الاشتراك' => '2022-01-01', 'تاريخ_النهاية' => '2022-12-31'),
        array('id' => 2, 'اسم_العضو' => 'عضو 2', 'نوع_الاشتراك' => 'اشتراك 2', 'تاريخ_الاشتراك' => '2022-02-01', 'تاريخ_النهاية' => '2022-11-30')
    );
}

function filterRecords($records, $search) {
    $filteredRecords = array();
    foreach ($records as $record) {
        if (strpos($record['اسم_العضو'], $search) !== false || strpos($record['نوع_الاشتراك'], $search) !== false || strpos($record['تاريخ_الاشتراك'], $search) !== false || strpos($record['تاريخ_النهاية'], $search) !== false) {
            $filteredRecords[] = $record;
        }
    }
    return $filteredRecords;
}

function deleteRecord($id) {
    // Assuming you have a function to delete a record from database
    // Replace this with your actual database query
    return array('success' => true);
}
?>

Note: This is a basic example and you should replace the database queries and functions with your actual implementation. Additionally, you should ensure that the backend script is secure and follows best practices for handling user input and database queries.