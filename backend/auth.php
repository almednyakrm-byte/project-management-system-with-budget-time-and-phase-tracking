<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, send a JSON response with their status
    $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle the login request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'login') {
    // Check if the username and password are provided
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $response = array('status' => 'error', 'message' => 'Username and password are required');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Sanitize the username and password
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Prepare the SQL query to select the user
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch();

    // Check if the user exists and the password is correct
    if ($user && password_verify($password, $user['password'])) {
        // If the user is valid, log them in and send a JSON response
        $_SESSION['user_id'] = $user['id'];
        $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        // If the user is not valid, send an error response
        $response = array('status' => 'error', 'message' => 'Invalid username or password');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Handle the register request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'register') {
    // Check if the username, email, and password are provided
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        $response = array('status' => 'error', 'message' => 'Username, email, and password are required');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Sanitize the username, email, and password
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Check if the username and email are unique
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch();

    // Check if the user already exists
    if ($user) {
        // If the user already exists, send an error response
        $response = array('status' => 'error', 'message' => 'Username or email already taken');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the user
    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Send a JSON response with the user's ID
    $response = array('status' => 'registered', 'user_id' => $db->lastInsertId());
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle the logout request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'logout') {
    // Destroy the session and send a JSON response
    session_destroy();
    $response = array('status' => 'logged_out');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}


This code handles user registration, login, logout, and checks the current session user status. It uses prepared statements to prevent SQL injection and hashes passwords using `password_hash()` and verifies them using `password_verify()`. It also includes input field validation and sanitization to prevent XSS attacks.