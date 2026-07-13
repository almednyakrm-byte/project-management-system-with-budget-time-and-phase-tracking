<?php
require_once 'db.php';

// Get user role and authentication status
$userRole = $_SESSION['userRole'];
$isAuthenticated = $_SESSION['isAuthenticated'];

if (!$isAuthenticated) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    $id = $_GET['id'] ?? null;
    $limit = $_GET['limit'] ?? 10;
    $offset = $_GET['offset'] ?? 0;

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM مورد ORDER BY id LIMIT :limit OFFSET :offset');
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

        // Execute query
        $stmt->execute();

        // Fetch results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return results
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($results);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// Handle POST request
elseif ($method === 'POST') {
    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO مورد (name, description) VALUES (:name, :description)');

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        // Execute query
        $stmt->execute();

        // Return result
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Resource created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Check user role for edit permission
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE مورد SET name = :name, description = :description WHERE id = :id');

        // Bind parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        // Execute query
        $stmt->execute();

        // Return result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Resource updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check user role for delete permission
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM مورد WHERE id = :id');

        // Bind parameter
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute query
        $stmt->execute();

        // Return result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Resource deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}