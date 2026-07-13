**edit_حجز-مواعيد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Validate id
if (empty($id)) {
    header('Location: list_حجز-مواعيد.php');
    exit;
}

// Fetch existing record details via GET
$url = '../backend/حجز-مواعيد.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if (empty($data)) {
    header('Location: list_حجز-مواعيد.php');
    exit;
}

// Set page title
$page_title = 'Edit حجز_مواعيد';

// Include header
include 'header.php';

?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
            <input type="date" id="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['date'] ?>">
        </div>

        <div class="mb-4">
            <label for="time" class="block text-gray-700 text-sm font-bold mb-2">Time:</label>
            <input type="time" id="time" name="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['time'] ?>">
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/حجز-مواعيد.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('date').value = data.date;
            document.getElementById('time').value = data.time;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);

        // Send AJAX PUT request
        fetch('../backend/حجز-مواعيد.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page
                window.location.href = 'list_حجز-مواعيد.php';
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**backend/حجز-مواعيد.php**

<?php
// Check if id is set
if (empty($_GET['id'])) {
    header('Location: list_حجز-مواعيد.php');
    exit;
}

// Get id
$id = $_GET['id'];

// Validate id
if (empty($id)) {
    header('Location: list_حجز-مواعيد.php');
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$sql = "SELECT * FROM حجز_مواعيد WHERE id = '$id'";
$result = $conn->query($sql);

// Check if data is available
if ($result->num_rows > 0) {
    // Get data
    $data = $result->fetch_assoc();
    echo json_encode($data);
} else {
    echo json_encode(array());
}

// Close connection
$conn->close();
?>


**backend/update_حجز-مواعيد.php**

<?php
// Check if id is set
if (empty($_GET['id'])) {
    header('Location: list_حجز-مواعيد.php');
    exit;
}

// Get id
$id = $_GET['id'];

// Validate id
if (empty($id)) {
    header('Location: list_حجز-مواعيد.php');
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$date = $_POST['date'];
$time = $_POST['time'];

// Update record
$sql = "UPDATE حجز_مواعيد SET name = '$name', date = '$date', time = '$time' WHERE id = '$id'";
$conn->query($sql);

// Close connection
$conn->close();

// Redirect to list page
header('Location: list_حجز-مواعيد.php');
exit;
?>