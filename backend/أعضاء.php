<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all members
    $stmt = $pdo->prepare('SELECT * FROM members');
    $stmt->execute();
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return members
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($members);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['role'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
    $role = filter_var($inputData['role'], FILTER_SANITIZE_STRING);

    // Insert new member
    $stmt = $pdo->prepare('INSERT INTO members (name, email, role) VALUES (:name, :email, :role)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    // Return new member
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Member created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['role'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
    $role = filter_var($inputData['role'], FILTER_SANITIZE_STRING);

    // Update member
    $stmt = $pdo->prepare('UPDATE members SET name = :name, email = :email, role = :role WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    // Return updated member
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Member updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete member
    $stmt = $pdo->prepare('DELETE FROM members WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted member
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Member deleted successfully'));
    exit;
}