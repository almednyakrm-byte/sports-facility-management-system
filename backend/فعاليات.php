<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input)) {
    $input = $_POST;
}

// Validate input data
if (empty($input['id'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'ID is required'));
    exit;
}

// Check if user is admin for edit/deletion
if (isset($input['action']) && in_array($input['action'], array('edit', 'delete'))) {
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Handle CRUD operations
switch ($input['action']) {
    case 'get':
        // Get all records
        $stmt = $pdo->prepare('SELECT * FROM فعاليات');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($records);
        break;

    case 'getById':
        // Get record by ID
        $stmt = $pdo->prepare('SELECT * FROM فعاليات WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($record) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($record);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
        }
        break;

    case 'create':
        // Create new record
        $stmt = $pdo->prepare('INSERT INTO فعاليات (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        if ($stmt->execute()) {
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Record created successfully'));
        } else {
            http_response_code(500);
            echo json_encode(array('error' => 'Internal Server Error'));
        }
        break;

    case 'update':
        // Update existing record
        $stmt = $pdo->prepare('UPDATE فعاليات SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        if ($stmt->execute()) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Record updated successfully'));
        } else {
            http_response_code(500);
            echo json_encode(array('error' => 'Internal Server Error'));
        }
        break;

    case 'delete':
        // Delete record
        $stmt = $pdo->prepare('DELETE FROM فعاليات WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        if ($stmt->execute()) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Record deleted successfully'));
        } else {
            http_response_code(500);
            echo json_encode(array('error' => 'Internal Server Error'));
        }
        break;

    default:
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid action'));
        break;
}