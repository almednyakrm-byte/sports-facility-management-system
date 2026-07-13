**edit_المنشئات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/المنشئات.php?id=' . $id;
$response = json_decode(file_get_contents($url), true);

// Check if record exists
if (!$response) {
    echo 'Error: Record not found.';
    exit;
}

// Set record details
$record = $response;

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المنشئات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
        }
        .emerald-600 {
            color: #008751;
        }
        .teal-500 {
            color: #0097a7;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4 mt-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">تعديل المنشئات</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم المنشئة:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $record['name']; ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف المنشئة:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo $record['description']; ?></textarea>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">حفظ التغييرات</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/المنشئات.php',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            title: 'تم الحفظ',
                            text: 'تم تعديل المنشئة بنجاح',
                            icon: 'success',
                            confirmButtonText: 'حسناً'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'list_المنشئات.php';
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `../backend/المنشئات.php` with the actual URL of your backend script. Also, this code assumes that the backend script returns the record details in JSON format. If it returns in a different format, you may need to adjust the JavaScript code accordingly.