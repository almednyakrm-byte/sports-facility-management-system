<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Check if user is admin
$is_admin = ($user_role == 'admin');

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (empty($input_data)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is admin to allow edit/deletion
    if ($is_admin) {
        $stmt = $pdo->prepare('SELECT * FROM المنشئات');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM المنشئات WHERE id = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input_data['description'], FILTER_SANITIZE_STRING);

    // Insert data
    $stmt = $pdo->prepare('INSERT INTO المنشئات (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Get inserted ID
    $id = $pdo->lastInsertId();

    // Return inserted data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id, 'name' => $name, 'description' => $description));
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is admin to allow edit
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input_data['id']) || !isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input_data['description'], FILTER_SANITIZE_STRING);

    // Update data
    $stmt = $pdo->prepare('UPDATE المنشئات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return updated data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id, 'name' => $name, 'description' => $description));
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is admin to allow deletion
    if (!$is_admin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input_data['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete data
    $stmt = $pdo->prepare('DELETE FROM المنشئات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
}

?>