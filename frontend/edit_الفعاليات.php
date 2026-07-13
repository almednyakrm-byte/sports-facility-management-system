**edit_الفعاليات.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/الفعاليات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الفعاليات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
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
        <h2 class="text-center">تعديل الفعاليات</h2>
        <form id="edit-form">
            <div class="form-group">
                <label for="title">العنوان</label>
                <input type="text" id="title" name="title" value="<?= $data['title'] ?>">
            </div>
            <div class="form-group">
                <label for="description">الوصف</label>
                <textarea id="description" name="description"><?= $data['description'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="date">التاريخ</label>
                <input type="date" id="date" name="date" value="<?= $data['date'] ?>">
            </div>
            <div class="form-group">
                <label for="time">الوقت</label>
                <input type="time" id="time" name="time" value="<?= $data['time'] ?>">
            </div>
            <div class="form-group">
                <label for="location">الموقع</label>
                <input type="text" id="location" name="location" value="<?= $data['location'] ?>">
            </div>
            <div class="form-group">
                <input type="submit" value="حفظ">
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/الفعاليات.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            Swal.fire({
                                title: 'نجاح',
                                text: 'تم تعديل الفعالية بنجاح',
                                icon: 'success',
                                confirmButtonText: 'حسناً'
                            }).then(function() {
                                window.location.href = 'list_الفعاليات.php';
                            });
                        } else {
                            Swal.fire({
                                title: 'فشل',
                                text: 'حدث خطأ أثناء تعديل الفعالية',
                                icon: 'error',
                                confirmButtonText: 'حسناً'
                            });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**backend/الفعاليات.php**

<?php
// Validate session
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Update record via PUT
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];

    // Update record in database
    $query = "UPDATE الفعاليات SET title = '$title', description = '$description', date = '$date', time = '$time', location = '$location' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    // Get record details via GET
    $query = "SELECT * FROM الفعاليات WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    echo json_encode($data);
}
?>