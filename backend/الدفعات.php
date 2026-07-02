<?php

// Include database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON request body
$inputData = json_decode(file_get_contents('php://input'), true);

// Define table name
$tableName = 'الدفعات';

// Define allowed columns for CRUD operations
$allowedColumns = array('id', 'name', 'description', 'amount');

// Define allowed roles for CRUD operations
$allowedRoles = array('admin' => array('create', 'read', 'update', 'delete'), 'user' => array('read'));

// Define HTTP response codes
$statusCode = array(
    'success' => 200,
    'created' => 201,
    'updated' => 200,
    'deleted' => 204,
    'error' => 500,
    'not_found' => 404,
    'unauthorized' => 401
);

// Define HTTP headers
$headers = array(
    'Content-Type' => 'application/json',
    'charset' => 'UTF-8'
);

// Define function for CRUD operations
function crudOperation($method, $data, $id = null) {
    global $pdo, $tableName, $allowedColumns, $allowedRoles, $statusCode, $headers;

    // Check user role
    if (!isset($_SESSION['role']) || !in_array($method, $allowedRoles[$_SESSION['role']])) {
        http_response_code($statusCode['unauthorized']);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    try {
        // Prepare SQL query
        $query = '';
        if ($method === 'create') {
            $query = "INSERT INTO $tableName (" . implode(', ', $allowedColumns) . ") VALUES (" . implode(', ', array_fill(0, count($allowedColumns), '?')) . ")";
        } elseif ($method === 'read') {
            $query = "SELECT * FROM $tableName WHERE id = ?";
        } elseif ($method === 'update') {
            $query = "UPDATE $tableName SET " . implode(', ', array_map(function($column) { return "$column = ?"; }, $allowedColumns)) . " WHERE id = ?";
        } elseif ($method === 'delete') {
            $query = "DELETE FROM $tableName WHERE id = ?";
        }

        // Sanitize input data
        $sanitizedData = array();
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedColumns)) {
                $sanitizedData[$key] = $pdo->quote($value);
            }
        }

        // Execute SQL query
        $stmt = $pdo->prepare($query);
        if ($id) {
            $stmt->execute(array_merge($sanitizedData, array($id)));
        } else {
            $stmt->execute($sanitizedData);
        }

        // Check if query was successful
        if ($stmt->rowCount() > 0) {
            // Return success response
            http_response_code($statusCode['success']);
            echo json_encode(array('message' => 'Operation successful'));
        } else {
            // Return error response
            http_response_code($statusCode['error']);
            echo json_encode(array('error' => 'Operation failed'));
        }
    } catch (PDOException $e) {
        // Return error response
        http_response_code($statusCode['error']);
        echo json_encode(array('error' => $e->getMessage()));
    }
}

// Handle CRUD operations
if (isset($inputData['method'])) {
    $method = $inputData['method'];
    $data = $inputData['data'];
    $id = $inputData['id'] ?? null;

    if ($method === 'create') {
        crudOperation('create', $data);
    } elseif ($method === 'read') {
        crudOperation('read', array(), $id);
    } elseif ($method === 'update') {
        crudOperation('update', $data, $id);
    } elseif ($method === 'delete') {
        crudOperation('delete', array(), $id);
    } else {
        http_response_code($statusCode['error']);
        echo json_encode(array('error' => 'Invalid method'));
    }
} else {
    http_response_code($statusCode['error']);
    echo json_encode(array('error' => 'Invalid request'));
}