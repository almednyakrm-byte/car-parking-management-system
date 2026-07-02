<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON or POST
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input)) {
    $input = $_POST;
}

// Define table name
$tableName = 'تتبع';

// Define columns
$columns = array(
    'id' => 'id',
    'name' => 'name',
    'description' => 'description'
);

// Define validation rules
$validationRules = array(
    'name' => array('required' => true, 'min' => 3, 'max' => 50),
    'description' => array('required' => false, 'min' => 10, 'max' => 200)
);

// Validate input data
foreach ($validationRules as $column => $rules) {
    if (isset($input[$column])) {
        if (empty($input[$column])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Validation failed'));
            exit;
        }
        if (isset($rules['min']) && strlen($input[$column]) < $rules['min']) {
            http_response_code(400);
            echo json_encode(array('error' => 'Validation failed'));
            exit;
        }
        if (isset($rules['max']) && strlen($input[$column]) > $rules['max']) {
            http_response_code(400);
            echo json_encode(array('error' => 'Validation failed'));
            exit;
        }
    }
}

// Sanitize input data
foreach ($columns as $column => $dbColumn) {
    if (isset($input[$column])) {
        $input[$column] = htmlspecialchars($input[$column]);
    }
}

// Check if user is admin for edits/deletions
if ($_SESSION['role'] != 'admin') {
    if (isset($input['id']) && $input['id'] != '') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Handle GET request
if (isset($_GET['id'])) {
    // Get single record
    $stmt = $pdo->prepare("SELECT * FROM $tableName WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $record = $stmt->fetch();
    if ($record) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($record);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['all'])) {
    // Get all records
    $stmt = $pdo->prepare("SELECT * FROM $tableName");
    $stmt->execute();
    $records = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}

// Handle POST request
if (isset($_POST['id'])) {
    // Update record
    $stmt = $pdo->prepare("UPDATE $tableName SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record updated successfully'));
} elseif (isset($_POST['name']) && isset($_POST['description'])) {
    // Insert new record
    $stmt = $pdo->prepare("INSERT INTO $tableName (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record created successfully'));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}

// Handle DELETE request
if (isset($_GET['id'])) {
    // Delete record
    $stmt = $pdo->prepare("DELETE FROM $tableName WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Record deleted successfully'));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}