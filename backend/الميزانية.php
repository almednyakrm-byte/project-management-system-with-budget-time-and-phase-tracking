<?php
require_once 'db.php';

// Get the user role from the session
$userRole = $_SESSION['userRole'];

// Check if the user is logged in
if (!$userRole) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET requests
if ($method === 'GET') {
    // Get the budget ID from the URL query string
    $budgetId = $_GET['id'] ?? null;

    // Check if the user is an admin or the owner of the budget
    if ($budgetId && ($userRole !== 'admin' || $_SESSION['userId'] !== $budgetId)) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Select all budgets or a specific budget
    if ($budgetId) {
        $stmt = $pdo->prepare('SELECT * FROM الميزانية WHERE id = :id');
        $stmt->bindParam(':id', $budgetId);
        $stmt->execute();
        $budget = $stmt->fetch();
        if (!$budget) {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
            exit;
        }
        echo json_encode($budget);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM الميزانية');
        $stmt->execute();
        $budgets = $stmt->fetchAll();
        echo json_encode($budgets);
    }
}

// Handle POST requests
if ($method === 'POST') {
    // Get the budget data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the budget data
    if (!isset($data['name']) || !isset($data['amount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the budget data
    $name = $pdo->quote($data['name']);
    $amount = $pdo->quote($data['amount']);

    // Insert the budget
    $stmt = $pdo->prepare('INSERT INTO الميزانية (name, amount) VALUES (:name, :amount)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();

    // Return the created budget
    $budget = $pdo->lastInsertId();
    echo json_encode(array('id' => $budget));
}

// Handle PUT requests
if ($method === 'PUT') {
    // Get the budget ID from the URL query string
    $budgetId = $_GET['id'] ?? null;

    // Check if the user is an admin or the owner of the budget
    if (!$budgetId || ($userRole !== 'admin' && $_SESSION['userId'] !== $budgetId)) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get the budget data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the budget data
    if (!isset($data['name']) || !isset($data['amount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the budget data
    $name = $pdo->quote($data['name']);
    $amount = $pdo->quote($data['amount']);

    // Update the budget
    $stmt = $pdo->prepare('UPDATE الميزانية SET name = :name, amount = :amount WHERE id = :id');
    $stmt->bindParam(':id', $budgetId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();

    // Return the updated budget
    echo json_encode(array('message' => 'Budget updated successfully'));
}

// Handle DELETE requests
if ($method === 'DELETE') {
    // Get the budget ID from the URL query string
    $budgetId = $_GET['id'] ?? null;

    // Check if the user is an admin or the owner of the budget
    if (!$budgetId || ($userRole !== 'admin' && $_SESSION['userId'] !== $budgetId)) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete the budget
    $stmt = $pdo->prepare('DELETE FROM الميزانية WHERE id = :id');
    $stmt->bindParam(':id', $budgetId);
    $stmt->execute();

    // Return a success message
    echo json_encode(array('message' => 'Budget deleted successfully'));
}

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Set the HTTP response status code
http_response_code(200);