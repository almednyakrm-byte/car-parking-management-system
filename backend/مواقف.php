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
    '/get' => 'get',
    '/post' => 'post',
    '/put' => 'put',
    '/delete' => 'delete'
);

// Get route
$route = $_SERVER['REQUEST_URI'];
$route = explode('/', $route);
$route = end($route);

// Check if route is valid
if (!isset($routes[$route])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Route not found'));
    exit;
}

// Process route
switch ($routes[$route]) {
    case 'get':
        get();
        break;
    case 'post':
        post();
        break;
    case 'put':
        put();
        break;
    case 'delete':
        delete();
        break;
}

// Function to get all records
function get() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM مواقف');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
}

// Function to create a new record
function post() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $name = trim($input['name']);
    $description = trim($input['description']);
    
    // Prepare statement
    $stmt = $pdo->prepare('INSERT INTO مواقف (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    
    // Execute statement
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Record created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create record'));
    }
}

// Function to update a record
function put() {
    global $pdo;
    // Validate input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $id = (int) $input['id'];
    $name = trim($input['name']);
    $description = trim($input['description']);
    
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Prepare statement
    $stmt = $pdo->prepare('UPDATE مواقف SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    
    // Execute statement
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Record updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update record'));
    }
}

// Function to delete a record
function delete() {
    global $pdo;
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input
    $id = (int) $input['id'];
    
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Prepare statement
    $stmt = $pdo->prepare('DELETE FROM مواقف WHERE id = :id');
    $stmt->bindParam(':id', $id);
    
    // Execute statement
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Record deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete record'));
    }
}