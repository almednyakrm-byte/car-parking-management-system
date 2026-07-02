<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/tazakir' => array('GET', 'POST'),
    '/tazakir/:id' => array('GET', 'PUT', 'DELETE'),
);

// Check route and method
$match = false;
foreach ($routes as $route => $methods) {
    if (strpos($route, '/') !== false) {
        $parts = explode('/', $route);
        if (count($parts) == 2 && $parts[0] == 'tazakir' && $parts[1] == $input['id']) {
            if (in_array($_SERVER['REQUEST_METHOD'], $methods)) {
                $match = true;
                break;
            }
        }
    } else {
        if ($route == 'tazakir' && in_array($_SERVER['REQUEST_METHOD'], $methods)) {
            $match = true;
            break;
        }
    }
}

if (!$match) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

// Validate input data
if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate required fields
    $requiredFields = array('name', 'description');
    foreach ($requiredFields as $field) {
        if (!isset($input[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
    }

    // Sanitize input data
    $input['name'] = htmlspecialchars($input['name']);
    $input['description'] = htmlspecialchars($input['description']);
}

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($input['id'])) {
        // Get single record
        $stmt = $db->prepare('SELECT * FROM tazakir WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
        }
    } else {
        // Get all records
        $stmt = $db->prepare('SELECT * FROM tazakir');
        $stmt->execute();
        $results = $stmt->fetchAll();
        echo json_encode($results);
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert new record
    $stmt = $db->prepare('INSERT INTO tazakir (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();
    http_response_code(201);
    echo json_encode(array('message' => 'Created'));
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Update existing record
    $stmt = $db->prepare('UPDATE tazakir SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(array('message' => 'Updated'));
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Delete existing record
    $stmt = $db->prepare('DELETE FROM tazakir WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(204);
    echo json_encode(array('message' => 'Deleted'));
}

// Close database connection
$db = null;