**edit_أعضاء.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('../backend/أعضاء.php?id=' . $id), true);

// Check if record exists
if (empty($record)) {
    echo 'Record not found';
    exit;
}

// Set page title
$page_title = 'Edit أعضاء';

// Include header
include 'header.php';

?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <h1 class="text-3xl font-bold text-emerald-600 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $record['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $record['email'] ?>">
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" value="<?= $record['phone'] ?>">
        </div>

        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<script>
    // Fetch existing record details via GET
    fetch('../backend/أعضاء.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);

        // Send AJAX PUT request
        fetch('../backend/أعضاء.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>'
            }
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page on success
                window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
            })
            .catch(error => console.error(error));
    });
</script>


**header.php**

<?php
// Include CSRF token
include 'csrf.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <!-- Page content -->
    <div id="app">
        <?= $content ?>
    </div>
</body>
</html>


**footer.php**

<?php
// Include CSRF token
include 'csrf.php';
?>

<!-- Include JavaScript files -->
<script src="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>


**csrf.php**

<?php
// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>


**backend/أعضاء.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Get id
$id = $_GET['id'];

// Fetch existing record details
$record = json_decode(file_get_contents('data.json'), true);

// Check if record exists
if (empty($record[$id])) {
    echo 'Record not found';
    exit;
}

// Return record details
echo json_encode($record[$id]);