**create_حجز-مواعيد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
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
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $notes = trim($_POST['notes']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($time)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO حجز_مواعيد (name, email, phone, date, time, notes) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $phone, $date, $time, $notes);
        if ($stmt->execute()) {
            // Redirect back to list page
            header('Location: list_حجز-مواعيد.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header and footer
require_once '../includes/header.php';
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-4">Create New حجز_مواعيد</h1>
    <form action="" method="post" id="create-form">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
            <input type="date" id="date" name="date" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="mb-4">
            <label for="time" class="block text-sm font-medium text-gray-700">Time:</label>
            <input type="time" id="time" name="time" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="mb-4">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes:</label>
            <textarea id="notes" name="notes" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500"><?= $error ?></p>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/حجز-مواعيد.php',
                data: formData,
                success: function(data) {
                    window.location.href = 'list_حجز-مواعيد.php';
                }
            });
        });
    });
</script>

<?php
require_once '../includes/footer.php';
?>


**backend/حجز-مواعيد.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $notes = trim($_POST['notes']);

    // Insert data into database
    $sql = "INSERT INTO حجز_مواعيد (name, email, phone, date, time, notes) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $phone, $date, $time, $notes);
    if ($stmt->execute()) {
        // Return success message
        echo 'true';
    } else {
        // Return error message
        echo 'false';
    }
}
?>