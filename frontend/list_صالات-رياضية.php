**list_صالات-رياضية.php**

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
    <title>صالات رياضية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">تسجيل خروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">صالات رياضية</h1>
        <div class="flex justify-between items-center mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_صالات-رياضية.php'">إضافة جديد</button>
            <input type="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="بحث" id="search">
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">الاسم</th>
                    <th class="border border-gray-400 p-2">العنوان</th>
                    <th class="border border-gray-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $url = '../backend/صالات-رياضية.php';
                $response = file_get_contents($url);
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="border border-gray-400 p-2"><?= $record['name'] ?></td>
                        <td class="border border-gray-400 p-2"><?= $record['address'] ?></td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_صالات-رياضية.php?id=<?= $record['id'] ?>" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const records = document.getElementById('records').getElementsByTagName('tr');
            for (let i = 0; i < records.length; i++) {
                const record = records[i];
                const name = record.cells[0].textContent.toLowerCase();
                const address = record.cells[1].textContent.toLowerCase();
                if (name.includes(searchValue) || address.includes(searchValue)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            }
        });

        // Delete record
        function deleteRecord(id) {
            fetch(`../backend/صالات-رياضية.php?action=delete&id=${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم حذف السجل بنجاح');
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء حذف السجل');
                }
            })
            .catch(error => console.error(error));
        }
    </script>
</body>
</html>

**backend/صالات-رياضية.php**

<?php
// Fetch records from database
$records = array();
// Replace with your database connection and query
$records = array(
    array('id' => 1, 'name' => 'صالة رياضية 1', 'address' => 'العنوان 1'),
    array('id' => 2, 'name' => 'صالة رياضية 2', 'address' => 'العنوان 2'),
    array('id' => 3, 'name' => 'صالة رياضية 3', 'address' => 'العنوان 3')
);
echo json_encode($records);
?>

Note: Replace the backend code with your actual database connection and query to fetch records.