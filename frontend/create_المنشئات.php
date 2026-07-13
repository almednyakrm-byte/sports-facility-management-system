**create_المنشئات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check if all fields are filled
    if (!empty($name) && !empty($email) && !empty($phone) && !empty($address)) {
        // Insert data into database
        $sql = "INSERT INTO المنشئات (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_المنشئات.php');
            exit;
        } else {
            echo 'Error inserting data';
        }
    } else {
        echo 'Please fill all fields';
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create المنشئات form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create المنشئات</h2>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-300 border rounded-md focus:outline-none focus:ring focus:border-emerald-600" placeholder="Enter name">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-bold text-gray-700">Email:</label>
            <input type="email" id="email" name="email" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-300 border rounded-md focus:outline-none focus:ring focus:border-emerald-600" placeholder="Enter email">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-bold text-gray-700">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-300 border rounded-md focus:outline-none focus:ring focus:border-emerald-600" placeholder="Enter phone">
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-bold text-gray-700">Address:</label>
            <textarea id="address" name="address" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-300 border rounded-md focus:outline-none focus:ring focus:border-emerald-600" placeholder="Enter address"></textarea>
        </div>
        <button type="submit" id="submit-btn" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX request to submit form data
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/المنشئات.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_المنشئات.php';
            } else {
                alert('Error creating المنشئات');
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>

**Note:** This code assumes that you have a `backend/المنشئات.php` file that handles the form submission and inserts the data into the database. You will need to create this file and implement the necessary logic to handle the form submission.