**edit_إدارة-الصالة.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/إدارة-الصالة.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set form data
$form_data = [
    'name' => $data['name'],
    'description' => $data['description'],
    'address' => $data['address'],
];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الصالة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل إدارة الصالة</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم الصالة</label>
                <input type="text" id="name" name="name" value="<?= $form_data['name'] ?>" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">وصف الصالة</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" rows="4"><?= $form_data['description'] ?></textarea>
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">عنوان الصالة</label>
                <input type="text" id="address" name="address" value="<?= $form_data['address'] ?>" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/إدارة-الصالة.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error updating record');
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/إدارة-الصالة.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = [
    'id' => $id,
    'name' => 'اسم الصالة',
    'description' => 'وصف الصالة',
    'address' => 'عنوان الصالة',
];

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);