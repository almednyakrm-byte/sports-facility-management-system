**create_الفعاليات.php**

<?php
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
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $location = trim($_POST['location']);

    if (!empty($name) && !empty($description) && !empty($date) && !empty($time) && !empty($location)) {
        // Insert data into database
        $query = "INSERT INTO الفعاليات (name, description, date, time, location) VALUES ('$name', '$description', '$date', '$time', '$location')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_الفعاليات.php');
            exit;
        } else {
            echo 'Error inserting data';
        }
    } else {
        echo 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create event form -->
<div class="container mx-auto p-4 pt-6">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Create Event</h2>
        <form id="create-event-form" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-700">Event Name:</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold text-gray-700">Event Description:</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-bold text-gray-700">Event Date:</label>
                <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <div class="mb-4">
                <label for="time" class="block text-sm font-bold text-gray-700">Event Time:</label>
                <input type="time" id="time" name="time" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <div class="mb-4">
                <label for="location" class="block text-sm font-bold text-gray-700">Event Location:</label>
                <input type="text" id="location" name="location" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
            </div>
            <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Create Event</button>
        </form>
    </div>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_الفعاليات.js**
javascript
$(document).ready(function() {
    $('#create-event-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/الفعاليات.php',
            data: formData,
            success: function(response) {
                if (response == 'success') {
                    window.location.href = 'list_الفعاليات.php';
                } else {
                    alert('Error creating event');
                }
            }
        });
    });
});


**Note:** Make sure to replace `../backend/الفعاليات.php` with the actual PHP file that handles the form submission. Also, make sure to include the necessary CSS and JavaScript files in your HTML file.