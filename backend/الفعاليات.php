<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define allowed roles for each operation
$allowedRoles = [
    'GET' => ['admin', 'user'],
    'POST' => ['admin', 'user'],
    'PUT' => ['admin'],
    'DELETE' => ['admin']
];

// Check if user has permission to perform the requested operation
if (!in_array($_SESSION['user_role'], $allowedRoles[$_SERVER['REQUEST_METHOD']])) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Validate input data
if (empty($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Sanitize input data
$input = array_map('trim', $input);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM الفعاليات');
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Output data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($events);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO الفعاليات (title, description, start_date, end_date) VALUES (:title, :description, :start_date, :end_date)');
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->bindParam(':start_date', $input['start_date']);
        $stmt->bindParam(':end_date', $input['end_date']);
        $stmt->execute();
        
        // Output data
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Event created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE الفعاليات SET title = :title, description = :description, start_date = :start_date, end_date = :end_date WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':title', $input['title']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->bindParam(':start_date', $input['start_date']);
        $stmt->bindParam(':end_date', $input['end_date']);
        $stmt->execute();
        
        // Output data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Event updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM الفعاليات WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        
        // Output data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Event deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}