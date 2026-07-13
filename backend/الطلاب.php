<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden access'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    // Validate input
    if (!isset($input['limit']) || !isset($input['offset'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $limit = (int) $input['limit'];
    $offset = (int) $input['offset'];

    // SQL query
    $stmt = $pdo->prepare('SELECT * FROM students LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Output processing
    $data = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    // Validate input
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $name = trim($input['name']);
    $email = trim($input['email']);
    $phone = trim($input['phone']);

    // SQL query
    $stmt = $pdo->prepare('INSERT INTO students (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->execute();

    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student created successfully'));
    exit;
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    // Validate input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $id = (int) $input['id'];
    $name = trim($input['name']);
    $email = trim($input['email']);
    $phone = trim($input['phone']);

    // SQL query
    $stmt = $pdo->prepare('UPDATE students SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student updated successfully'));
    exit;
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $id = (int) $input['id'];

    // SQL query
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student deleted successfully'));
    exit;
}

// Handle unknown request
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;