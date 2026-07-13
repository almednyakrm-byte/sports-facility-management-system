**edit_اشتراكات-الاعضاء.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/اشتراكات-الاعضاء.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل اشتراك</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تعديل اشتراك</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم العضو</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الالكتروني</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['phone'] ?>">
            </div>
            <div class="mb-4">
                <label for="subscription" class="block text-gray-700 text-sm font-bold mb-2">اشتراك</label>
                <input type="text" id="subscription" name="subscription" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['subscription'] ?>">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('edit-form');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(form);
                fetch('../backend/اشتراكات-الاعضاء.php', {
                    method: 'PUT',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    } else {
                        console.error(data.error);
                    }
                })
                .catch(error => console.error(error));
            });
        });
    </script>
</body>
</html>

**backend/اشتراكات-الاعضاء.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = get_record($id);

// Output JSON response
echo json_encode($existingRecord);

function get_record($id) {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare query
    $stmt = $conn->prepare('SELECT * FROM اشتراكات_الاعضاء WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch result
    $result = $stmt->fetch();

    // Close connection
    $conn = null;

    return $result;
}

Note: Replace `localhost`, `database`, `username`, and `password` with your actual database credentials. Also, make sure to update the `get_record` function to match your database schema.