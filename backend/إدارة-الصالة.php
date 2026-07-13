<?php

// Importing required files
require_once 'db.php';

// Checking if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Reading input data from JSON
$inputData = json_decode(file_get_contents('php://input'), true);

// Handling GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    // Checking user role for admin-only access
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Preparing SQL query to select all records
        $stmt = $pdo->prepare('SELECT * FROM إدارة_الصالة');
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    // Checking user role for admin-only access
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Sanitizing input data
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Preparing SQL query to select one record
        $stmt = $pdo->prepare('SELECT * FROM إدارة_الصالة WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_count') {
    try {
        // Preparing SQL query to count all records
        $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM إدارة_الصالة');
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handling POST request
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    // Checking user role for admin-only access
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validating input data
    if (!isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Sanitizing input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    try {
        // Preparing SQL query to insert new record
        $stmt = $pdo->prepare('INSERT INTO إدارة_الصالة (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handling PUT request
if (isset($_PUT['action']) && $_PUT['action'] == 'update') {
    // Checking user role for admin-only access
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Sanitizing input data
    $id = filter_var($_PUT['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($_PUT['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_PUT['description'], FILTER_SANITIZE_STRING);

    try {
        // Preparing SQL query to update existing record
        $stmt = $pdo->prepare('UPDATE إدارة_الصالة SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handling DELETE request
if (isset($_DELETE['action']) && $_DELETE['action'] == 'delete') {
    // Checking user role for admin-only access
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Sanitizing input data
    $id = filter_var($_DELETE['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Preparing SQL query to delete existing record
        $stmt = $pdo->prepare('DELETE FROM إدارة_الصالة WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

?>