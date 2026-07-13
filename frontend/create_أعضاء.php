**create_أعضاء.php**

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

// Create form data array
$data = array(
    'name' => '',
    'email' => '',
    'phone' => '',
    'address' => '',
);

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO أعضاء (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect back to list_{mod_slug}.php
        header('Location: list_أعضاء.php');
        exit;
    } else {
        // Display error message
        echo '<div class="bg-red-500 text-white p-4 mb-4 rounded-lg text-center">Error: ' . mysqli_error($conn) . '</div>';
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
    <title>إضافة أعضاء</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input, .form-group select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-2xl text-emerald-600 mb-4">إضافة أعضاء</h2>
        <form id="create-member-form" method="post">
            <div class="form-group">
                <label for="name">اسم العضو:</label>
                <input type="text" id="name" name="name" value="<?php echo $data['name']; ?>">
            </div>
            <div class="form-group">
                <label for="email">بريد إلكتروني:</label>
                <input type="email" id="email" name="email" value="<?php echo $data['email']; ?>">
            </div>
            <div class="form-group">
                <label for="phone">رقم الهاتف:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $data['phone']; ?>">
            </div>
            <div class="form-group">
                <label for="address">عنوان العضو:</label>
                <textarea id="address" name="address"><?php echo $data['address']; ?></textarea>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" value="إضافة">
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-member-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/أعضاء.php',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            window.location.href = 'list_أعضاء.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**backend/أعضاء.php**

<?php
// Include database connection
require_once '../db.php';

// Check if form data has been submitted
if (isset($_POST['submit'])) {
    // Insert data into database
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $query = "INSERT INTO أعضاء (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>