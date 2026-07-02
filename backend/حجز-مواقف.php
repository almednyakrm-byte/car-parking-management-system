<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/get' => 'get',
    '/create' => 'create',
    '/update/:id' => 'update',
    '/delete/:id' => 'delete'
);

// Get route
$route = $_SERVER['REQUEST_URI'];
foreach ($routes as $pattern => $method) {
    if (preg_match('/^' . preg_quote($pattern, '/') . '$/', $route, $matches)) {
        $route = $method;
        break;
    }
}

// Handle route
switch ($route) {
    case 'get':
        get();
        break;
    case 'create':
        create();
        break;
    case 'update':
        update();
        break;
    case 'delete':
        delete();
        break;
}

// Helper functions

function get() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM حجز_مواقف');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
}

function create() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);
    
    // Insert data
    $stmt = $pdo->prepare('INSERT INTO حجز_مواقف (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    
    // Return created ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $pdo->lastInsertId()));
}

function update() {
    global $pdo;
    // Get ID from URL
    $id = (int) $_GET['id'];
    
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);
    
    // Update data
    $stmt = $pdo->prepare('UPDATE حجز_مواقف SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

function delete() {
    global $pdo;
    // Get ID from URL
    $id = (int) $_GET['id'];
    
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Delete data
    $stmt = $pdo->prepare('DELETE FROM حجز_مواقف WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    
    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}