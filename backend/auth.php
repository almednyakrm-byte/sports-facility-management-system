<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, send a JSON response with their details
    $response = array(
        'status' => 'logged_in',
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username']
    );
    echo json_encode($response);
    exit;
}

// Handle the login request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to select the user
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows == 1) {
            // Fetch the user details
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // If the password is correct, log the user in
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $response = array(
                    'status' => 'logged_in',
                    'user_id' => $_SESSION['user_id'],
                    'username' => $_SESSION['username']
                );
                echo json_encode($response);
                exit;
            } else {
                // If the password is incorrect, send an error response
                $response = array(
                    'status' => 'error',
                    'message' => 'Invalid password'
                );
                echo json_encode($response);
                exit;
            }
        } else {
            // If the user does not exist, send an error response
            $response = array(
                'status' => 'error',
                'message' => 'Invalid username or password'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        // If the username or password is not set, send an error response
        $response = array(
            'status' => 'error',
            'message' => 'Username and password are required'
        );
        echo json_encode($response);
        exit;
    }
}

// Handle the register request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are unique
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the username and email are already taken
        if ($result->num_rows > 0) {
            // If the username or email is already taken, send an error response
            $response = array(
                'status' => 'error',
                'message' => 'Username or email already taken'
            );
            echo json_encode($response);
            exit;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert the user
        $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        $stmt->execute();

        // If the user is registered successfully, send a success response
        $response = array(
            'status' => 'registered',
            'message' => 'User registered successfully'
        );
        echo json_encode($response);
        exit;
    } else {
        // If the username, email, or password is not set, send an error response
        $response = array(
            'status' => 'error',
            'message' => 'Username, email, and password are required'
        );
        echo json_encode($response);
        exit;
    }
}

// Handle the logout request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();
    $response = array(
        'status' => 'logged_out'
    );
    echo json_encode($response);
    exit;
}
?>