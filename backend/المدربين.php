<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if input data is valid
if (empty($input)) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM المدربين WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();

    // Fetch result
    $result = $stmt->fetch();

    // Check if result exists
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO المدربين (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Check if insertion was successful
    if ($stmt->rowCount() > 0) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal server error'));
    }
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE المدربين SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM المدربين WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();

    // Check if deletion was successful
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}