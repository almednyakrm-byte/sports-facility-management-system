**edit_الطلاب.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from the URL
$id = $_GET['id'];

// Fetch the existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/الطلاب.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit Student</h2>
        <form id="edit-student-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-700">Name:</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold text-gray-700">Email:</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-bold text-gray-700">Phone:</label>
                <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" value="<?= $existingRecord['phone'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update Student</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-student-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/الطلاب.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating student');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/الطلاب.php**

<?php
// Check if the ID is set
if (!isset($_GET['id'])) {
    http_response_code(404);
    exit;
}

// Get the ID from the URL
$id = $_GET['id'];

// Fetch the existing record details from the database
// Replace this with your actual database query
$existingRecord = array(
    'id' => $id,
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '1234567890'
);

// Output the existing record details as JSON
header('Content-Type: application/json');
echo json_encode($existingRecord);