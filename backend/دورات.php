<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Check if user is admin
$isAdmin = ($userRole == 'admin');

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate input parameters
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input parameters
    $id = intval($inputData['id']);

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM دورات WHERE id = :id');
    $stmt->bindParam(':id', $id);
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
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input parameters
    if (!isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input parameters
    $name = trim($inputData['name']);
    $description = trim($inputData['description']);

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO دورات (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Get inserted ID
    $id = $pdo->lastInsertId();

    // Return inserted ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id));
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input parameters
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input parameters
    $id = intval($inputData['id']);
    $name = trim($inputData['name']);
    $description = trim($inputData['description']);

    // Check if user is admin
    if (!$isAdmin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE دورات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->rowCount() == 1) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input parameters
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input parameters
    $id = intval($inputData['id']);

    // Check if user is admin
    if (!$isAdmin) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM دورات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if delete was successful
    if ($stmt->rowCount() == 1) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}