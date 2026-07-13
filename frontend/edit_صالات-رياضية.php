**edit_صالات-رياضية.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/صالات-رياضية.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if ($data) {
    $name = $data['name'];
    $address = $data['address'];
    $phone = $data['phone'];
    $email = $data['email'];
} else {
    echo 'Error fetching data';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit صالات رياضية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">Edit صالات رياضية</h1>

    <form id="edit-form" class="bg-white p-4 rounded shadow-md">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $name; ?>">
        </div>

        <div class="mb-4">
            <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
            <input type="text" id="address" name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $address; ?>">
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
            <input type="text" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $phone; ?>">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $email; ?>">
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
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
                url: '../backend/صالات-رياضية.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    console.log(data);
                    window.location.href = 'list_صالات-رياضية.php';
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


**backend/صالات-رياضية.php**

<?php
// Check if id is provided
if (!isset($_GET['id'])) {
    echo 'Error: ID not provided';
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Check if id is numeric
if (!is_numeric($id)) {
    echo 'Error: ID is not numeric';
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch existing record details
$sql = "SELECT * FROM صالات_رياضية WHERE id = '$id'";
$result = $conn->query($sql);

// Check if data is available
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo 'Error fetching data';
}

// Close connection
$conn->close();
?>