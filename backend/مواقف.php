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

// Define table name
$tableName = 'مواقف';

// Define allowed fields for CRUD operations
$allowedFields = array('id', 'field1', 'field2', 'field3');

// Validate and sanitize input data
if (!empty($input)) {
    foreach ($input as $field => $value) {
        if (!in_array($field, $allowedFields)) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid field'));
            exit;
        }
        $input[$field] = trim($value);
    }
}

// Handle GET request
if (isset($_GET['id'])) {
    // Get single record by ID
    $stmt = $pdo->prepare("SELECT * FROM $tableName WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($record) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($record);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Record not found'));
    }
} elseif (isset($_GET['limit']) && isset($_GET['offset'])) {
    // Get all records with pagination
    $stmt = $pdo->prepare("SELECT * FROM $tableName LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $_GET['limit']);
    $stmt->bindParam(':offset', $_GET['offset']);
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
} else {
    // Get all records
    $stmt = $pdo->prepare("SELECT * FROM $tableName");
    $stmt->execute();
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
}

// Handle POST request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert new record
    $stmt = $pdo->prepare("INSERT INTO $tableName (field1, field2, field3) VALUES (:field1, :field2, :field3)");
    $stmt->bindParam(':field1', $input['field1']);
    $stmt->bindParam(':field2', $input['field2']);
    $stmt->bindParam(':field3', $input['field3']);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Record created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create record'));
    }
}

// Handle PUT request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update existing record
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'ID is required'));
        exit;
    }
    $stmt = $pdo->prepare("UPDATE $tableName SET field1 = :field1, field2 = :field2, field3 = :field3 WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->bindParam(':field1', $input['field1']);
    $stmt->bindParam(':field2', $input['field2']);
    $stmt->bindParam(':field3', $input['field3']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Record updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update record'));
    }
}

// Handle DELETE request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Delete existing record
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'ID is required'));
        exit;
    }
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $pdo->prepare("DELETE FROM $tableName WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Record deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete record'));
    }
}