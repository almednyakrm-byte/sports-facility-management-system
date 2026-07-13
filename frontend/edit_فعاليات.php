**edit_فعاليات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/فعاليات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title
$page_title = 'Edit ' . $data['name'];

// Include header
include 'header.php';

?>

<!-- Main content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-emerald-600 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
            <input id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" value="<?= $data['name'] ?>">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
            <textarea id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="5"><?= $data['description'] ?></textarea>
        </div>

        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<script>
    // Fetch existing record details via GET
    fetch('../backend/فعاليات.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error('Error:', error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', (e) => {
        e.preventDefault();

        const formData = new FormData(document.getElementById('edit-form'));

        fetch('../backend/فعاليات.php', {
            method: 'PUT',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>

**Note:** Replace `<?= $mod_slug ?>` with the actual value of the `$mod_slug` variable.

**backend/فعاليات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM فعاليات WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if data exists
if (empty($data)) {
    echo json_encode(['error' => 'Record not found']);
    exit;
}

// Update record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $formData);

    $name = $formData['name'];
    $description = $formData['description'];

    $query = "UPDATE فعاليات SET name = '$name', description = '$description' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error updating record']);
    }
}

// Output existing record details
echo json_encode($data);

**Note:** Replace `<?= $conn ?>` with the actual value of the database connection variable.