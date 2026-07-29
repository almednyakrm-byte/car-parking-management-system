<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['description'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Sanitize input data
$input['name'] = trim($input['name'] ?? '');
$input['description'] = trim($input['description'] ?? '');

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// GET all records
if (isset($input['action']) && $input['action'] == 'get_all') {
    $stmt = $db->prepare('SELECT * FROM دفعات_مواقف');
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
    exit;
}

// GET single record
if (isset($input['action']) && $input['action'] == 'get') {
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }
    $stmt = $db->prepare('SELECT * FROM دفعات_مواقف WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$record) {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($record);
    exit;
}

// POST new record
if (isset($input['action']) && $input['action'] == 'create') {
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }
    $stmt = $db->prepare('INSERT INTO دفعات_مواقف (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record created successfully']);
    exit;
}

// PUT update record
if (isset($input['action']) && $input['action'] == 'update') {
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }
    $stmt = $db->prepare('UPDATE دفعات_مواقف SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
    exit;
}

// DELETE record
if (isset($input['action']) && $input['action'] == 'delete') {
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }
    $stmt = $db->prepare('DELETE FROM دفعات_مواقف WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
exit;