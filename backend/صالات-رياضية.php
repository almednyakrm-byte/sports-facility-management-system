<?php
require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Check if the user is an admin
if ($method === 'PUT' || $method === 'DELETE') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Get the input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($method === 'GET') {
    try {
        // Prepare the SQL query
        $stmt = $pdo->prepare('SELECT * FROM صالات_رياضية');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Output the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($rows);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Handle POST request
if ($method === 'POST') {
    try {
        // Validate the input data
        if (!isset($input['name']) || !isset($input['address'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }
        
        // Sanitize the input data
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('INSERT INTO صالات_رياضية (name, address) VALUES (:name, :address)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
        
        // Output the result
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Handle PUT request
if ($method === 'PUT') {
    try {
        // Validate the input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['address'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }
        
        // Sanitize the input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $address = filter_var($input['address'], FILTER_SANITIZE_STRING);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('UPDATE صالات_رياضية SET name = :name, address = :address WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
        
        // Output the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Handle DELETE request
if ($method === 'DELETE') {
    try {
        // Validate the input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }
        
        // Sanitize the input data
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        
        // Prepare the SQL query
        $stmt = $pdo->prepare('DELETE FROM صالات_رياضية WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Output the result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>