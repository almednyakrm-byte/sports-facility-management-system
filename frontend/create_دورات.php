**create_دورات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/config.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $duration = trim($_POST['duration']);

    if (!empty($name) && !empty($description) && !empty($price) && !empty($duration)) {
        // Insert data into database
        $query = "INSERT INTO دورات (name, description, price, duration) VALUES ('$name', '$description', '$price', '$duration')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_دورات.php');
            exit;
        } else {
            echo 'Error inserting data';
        }
    } else {
        echo 'Please fill in all fields';
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة دورة جديدة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">إضافة دورة جديدة</h1>
        <form id="create-form" class="bg-white p-4 rounded shadow-md" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الدورة</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="اسم الدورة">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف الدورة</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="وصف الدورة"></textarea>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">سعر الدورة</label>
                <input type="number" id="price" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="سعر الدورة">
            </div>
            <div class="mb-4">
                <label for="duration" class="block text-gray-700 text-sm font-bold mb-2">مدة الدورة</label>
                <input type="text" id="duration" name="duration" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="مدة الدورة">
            </div>
            <button type="submit" id="submit-btn" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" name="submit">إضافة</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/دورات.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_دورات.php';
                        } else {
                            alert('Error adding data');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**backend/دورات.php**

<?php
// Include database connection
require_once '../config.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['duration'])) {
    // Insert data into database
    $query = "INSERT INTO دورات (name, description, price, duration) VALUES ('".$_POST['name']."', '".$_POST['description']."', '".$_POST['price']."', '".$_POST['duration']."')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error adding data';
    }
}

// Close database connection
mysqli_close($conn);
?>