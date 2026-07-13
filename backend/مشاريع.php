<?php

require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input data
if ($method === 'POST' && !isset($input['name']) || !isset($input['description'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Handle GET request
if ($method === 'GET') {
    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM مشاريع');
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Output projects
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($projects);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// Handle POST request
if ($method === 'POST') {
    try {
        // Validate input data
        if (!isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        // Sanitize input data
        $name = $pdo->quote($input['name']);
        $description = $pdo->quote($input['description']);

        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO مشاريع (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        // Output project ID
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// Handle PUT request
if ($method === 'PUT') {
    try {
        // Validate input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        // Sanitize input data
        $id = (int) $input['id'];
        $name = $pdo->quote($input['name']);
        $description = $pdo->quote($input['description']);

        // Check if user is admin
        if ($user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE مشاريع SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Output success message
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Project updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// Handle DELETE request
if ($method === 'DELETE') {
    try {
        // Validate input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        // Sanitize input data
        $id = (int) $input['id'];

        // Check if user is admin
        if ($user['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM مشاريع WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Output success message
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Project deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}