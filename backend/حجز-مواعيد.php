<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    'GET' => array('/' => 'readAll', '/:id' => 'readOne'),
    'POST' => '/create',
    'PUT' => '/update/:id',
    'DELETE' => '/delete/:id'
);

// Get route
$route = $_SERVER['REQUEST_METHOD'] . $_SERVER['REQUEST_URI'];
$route = explode('?', $route);
$route = explode('/', $route[1]);
$route = $routes[$_SERVER['REQUEST_METHOD']][$route[0]];

// Call route function
call_user_func(array('App', $route), $input);

class App {
    public static function readAll($input = null) {
        // Check if user is admin
        if ($_SESSION['role'] != 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // Validate input
        if ($input) {
            $validate = array(
                'limit' => array('required' => true, 'type' => 'int'),
                'offset' => array('required' => true, 'type' => 'int')
            );
            $errors = validate($input, $validate);
            if ($errors) {
                http_response_code(400);
                echo json_encode(array('error' => $errors));
                exit;
            }
        }

        // Sanitize input
        $limit = isset($input['limit']) ? (int)$input['limit'] : 10;
        $offset = isset($input['offset']) ? (int)$input['offset'] : 0;

        // SQL query
        $sql = "SELECT * FROM حجز_مواعيد LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        // Output
        http_response_code(200);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function readOne($input = null) {
        // Validate input
        $validate = array(
            'id' => array('required' => true, 'type' => 'int')
        );
        $errors = validate($input, $validate);
        if ($errors) {
            http_response_code(400);
            echo json_encode(array('error' => $errors));
            exit;
        }

        // Sanitize input
        $id = (int)$input['id'];

        // SQL query
        $sql = "SELECT * FROM حجز_مواعيد WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Output
        if ($stmt->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(array('error' => 'Not found'));
        } else {
            http_response_code(200);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        }
    }

    public static function create($input = null) {
        // Validate input
        $validate = array(
            'title' => array('required' => true, 'type' => 'string'),
            'description' => array('required' => true, 'type' => 'string')
        );
        $errors = validate($input, $validate);
        if ($errors) {
            http_response_code(400);
            echo json_encode(array('error' => $errors));
            exit;
        }

        // Sanitize input
        $title = trim($input['title']);
        $description = trim($input['description']);

        // SQL query
        $sql = "INSERT INTO حجز_مواعيد (title, description) VALUES (:title, :description)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->execute();

        // Output
        http_response_code(201);
        echo json_encode(array('id' => $pdo->lastInsertId()));
    }

    public static function update($input = null) {
        // Validate input
        $validate = array(
            'id' => array('required' => true, 'type' => 'int'),
            'title' => array('type' => 'string'),
            'description' => array('type' => 'string')
        );
        $errors = validate($input, $validate);
        if ($errors) {
            http_response_code(400);
            echo json_encode(array('error' => $errors));
            exit;
        }

        // Sanitize input
        $id = (int)$input['id'];
        $title = trim($input['title'] ?? '');
        $description = trim($input['description'] ?? '');

        // Check if user is admin
        if ($_SESSION['role'] != 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // SQL query
        $sql = "UPDATE حجز_مواعيد SET title = :title, description = :description WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->execute();

        // Output
        if ($stmt->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(array('error' => 'Not found'));
        } else {
            http_response_code(200);
            echo json_encode(array('message' => 'Updated successfully'));
        }
    }

    public static function delete($input = null) {
        // Validate input
        $validate = array(
            'id' => array('required' => true, 'type' => 'int')
        );
        $errors = validate($input, $validate);
        if ($errors) {
            http_response_code(400);
            echo json_encode(array('error' => $errors));
            exit;
        }

        // Sanitize input
        $id = (int)$input['id'];

        // Check if user is admin
        if ($_SESSION['role'] != 'admin') {
            http_response_code(403);
            echo json_encode(array('error' => 'Forbidden'));
            exit;
        }

        // SQL query
        $sql = "DELETE FROM حجز_مواعيد WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Output
        if ($stmt->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(array('error' => 'Not found'));
        } else {
            http_response_code(200);
            echo json_encode(array('message' => 'Deleted successfully'));
        }
    }
}

function validate($input, $rules) {
    $errors = array();
    foreach ($rules as $field => $rule) {
        if (isset($input[$field])) {
            if ($rule['type'] == 'string' && !is_string($input[$field])) {
                $errors[$field] = 'Invalid type';
            } elseif ($rule['type'] == 'int' && !is_int($input[$field])) {
                $errors[$field] = 'Invalid type';
            } elseif ($rule['required'] && empty($input[$field])) {
                $errors[$field] = 'Required';
            }
        } else {
            $errors[$field] = 'Required';
        }
    }
    return $errors;
}


This code defines a RESTful API for the `حجز_مواعيد` module, supporting full CRUD operations. It uses PDO prepared statements to prevent SQL injections and includes user role authorization checks. The code also includes input validation and sanitization.