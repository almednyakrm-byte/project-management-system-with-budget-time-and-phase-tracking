<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role for admin-only access
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query to select all records
    $stmt = $pdo->prepare('SELECT * FROM فريق_العمل');
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return HTTP response with application/json Content-Type header
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role for admin-only access
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input_data['description'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to insert new record
    $stmt = $pdo->prepare('INSERT INTO فريق_العمل (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return HTTP response with application/json Content-Type header
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record created successfully']);
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role for admin-only access
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($input_data['id']) || !isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input_data['description'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to update existing record
    $stmt = $pdo->prepare('UPDATE فريق_العمل SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return HTTP response with application/json Content-Type header
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role for admin-only access
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($input_data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to delete existing record
    $stmt = $pdo->prepare('DELETE FROM فريق_العمل WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return HTTP response with application/json Content-Type header
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
    exit;
}