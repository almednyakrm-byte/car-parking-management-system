<?php

// Import database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON body
$inputData = json_decode(file_get_contents('php://input'), true);

// Define table name and columns
$tableName = 'دفعات مواقف';
$columns = array('id', 'name', 'description');

// Define routes for CRUD operations
$routes = array(
    '/get-all' => 'getAll',
    '/get-one' => 'getOne',
    '/create' => 'create',
    '/update' => 'update',
    '/delete' => 'delete'
);

// Define function for each CRUD operation
function getAll($pdo, $inputData) {
    // Validate and sanitize input data
    $limit = isset($inputData['limit']) ? (int)$inputData['limit'] : 10;
    $offset = isset($inputData['offset']) ? (int)$inputData['offset'] : 0;

    // SQL query to get all records
    $sql = "SELECT * FROM $tableName LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Return response with HTTP status code 200
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function getOne($pdo, $inputData) {
    // Validate and sanitize input data
    $id = isset($inputData['id']) ? (int)$inputData['id'] : 0;

    // SQL query to get one record
    $sql = "SELECT * FROM $tableName WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Return response with HTTP status code 200
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Record not found'));
    }
}

function create($pdo, $inputData) {
    // Validate and sanitize input data
    $name = isset($inputData['name']) ? trim($inputData['name']) : '';
    $description = isset($inputData['description']) ? trim($inputData['description']) : '';

    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query to insert new record
    $sql = "INSERT INTO $tableName (name, description) VALUES (:name, :description)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();

    // Return response with HTTP status code 201
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record created successfully'));
}

function update($pdo, $inputData) {
    // Validate and sanitize input data
    $id = isset($inputData['id']) ? (int)$inputData['id'] : 0;
    $name = isset($inputData['name']) ? trim($inputData['name']) : '';
    $description = isset($inputData['description']) ? trim($inputData['description']) : '';

    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query to update existing record
    $sql = "UPDATE $tableName SET name = :name, description = :description WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();

    // Return response with HTTP status code 200
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record updated successfully'));
}

function delete($pdo, $inputData) {
    // Validate and sanitize input data
    $id = isset($inputData['id']) ? (int)$inputData['id'] : 0;

    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query to delete existing record
    $sql = "DELETE FROM $tableName WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Return response with HTTP status code 200
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record deleted successfully'));
}

// Get route from URL
$route = $_SERVER['REQUEST_URI'];
$route = explode('/', $route);
array_shift($route);
array_shift($route);
$route = implode('/', $route);

// Call corresponding function based on route
if (isset($routes[$route])) {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $routes[$route]($pdo, $inputData);
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Route not found'));
}


This code provides a basic implementation of a RESTful API for the 'دفعات مواقف' module. It uses PDO prepared statements to prevent SQL injection and includes user role authorization checks. The code also includes validation and sanitization of input data.