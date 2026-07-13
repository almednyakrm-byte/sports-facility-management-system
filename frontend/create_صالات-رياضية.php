**create_صالات-رياضية.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $errors = [];
    $data = [
        'name' => $_POST['name'],
        'address' => $_POST['address'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
    ];

    // Validate name
    if (empty($data['name'])) {
        $errors[] = 'Name is required';
    }

    // Validate address
    if (empty($data['address'])) {
        $errors[] = 'Address is required';
    }

    // Validate phone
    if (empty($data['phone'])) {
        $errors[] = 'Phone is required';
    }

    // Validate email
    if (empty($data['email'])) {
        $errors[] = 'Email is required';
    }

    // Check if there are any errors
    if (!empty($errors)) {
        // Display errors
        echo '<div class="bg-red-500 text-white p-4 mb-4">';
        echo '<p>There are some errors in your form:</p>';
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    } else {
        // Insert data into database
        $sql = "INSERT INTO صالات_رياضية (name, address, phone, email) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$data['name'], $data['address'], $data['phone'], $data['email']]);

        // Redirect to list page
        header('Location: list_صالات-رياضية.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة صالة رياضية جديدة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded shadow-md">
        <h1 class="text-2xl font-bold mb-4">إضافة صالة رياضية جديدة</h1>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الصالة</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="اسم الصالة">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">عنوان الصالة</label>
                <input type="text" id="address" name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="عنوان الصالة">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="رقم الهاتف">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="البريد الإلكتروني">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إضافة الصالة</button>
        </form>
    </div>

    <script>
        document.getElementById('create-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: '../backend/صالات-رياضية.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    window.location.href = 'list_صالات-رياضية.php';
                }
            });
        });
    </script>
</body>
</html>

This code creates a premium Tailwind UI form with all necessary fields based on common attributes for the `صالات_رياضية` module. It includes session validation and uses AJAX to POST the form data to `../backend/صالات-رياضية.php` on success, redirecting back to `list_صالات-رياضية.php`.