**edit_المدربين.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/المدربين.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found.';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit المدربين';
$modSlug = 'المدربين';

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-emerald-600"><?= $pageTitle ?></h1>
        <form id="edit-form" class="bg-white p-4 mt-4 shadow-md">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">الاسم</label>
                    <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border-gray-300 rounded-md" value="<?= $existingRecord['name'] ?>">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="block w-full p-2 mt-1 border-gray-300 rounded-md" value="<?= $existingRecord['email'] ?>">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                    <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 border-gray-300 rounded-md" value="<?= $existingRecord['phone'] ?>">
                </div>
                <div>
                    <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">حفظ التغييرات</button>
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/المدربين.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $modSlug ?>.php';
                        } else {
                            alert('Error: ' + response.error);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/المدربين.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if ID exists in database
// Replace this with your actual database query
$existingRecord = array(
    'id' => $id,
    'name' => 'Existing Name',
    'email' => 'existing@example.com',
    'phone' => '0123456789'
);

// Return existing record details as JSON
echo json_encode($existingRecord);