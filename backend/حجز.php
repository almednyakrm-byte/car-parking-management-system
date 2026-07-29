<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define validation rules
$validationRules = [
    'id' => 'integer',
    'name' => 'string',
    'description' => 'string',
    'date' => 'date',
    'time' => 'string',
    'user_id' => 'integer'
];

// Validate input data
foreach ($validationRules as $field => $rule) {
    if (!isset($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
    switch ($rule) {
        case 'integer':
            if (!is_int($input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Invalid type for field: $field"]);
                exit;
            }
            break;
        case 'string':
            if (!is_string($input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Invalid type for field: $field"]);
                exit;
            }
            break;
        case 'date':
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Invalid date format for field: $field"]);
                exit;
            }
            break;
    }
}

// Sanitize input data
$input = array_map('intval', $input);

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get all bookings
    $stmt = $db->prepare('SELECT * FROM bookings');
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($bookings);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert new booking
    $stmt = $db->prepare('INSERT INTO bookings (name, description, date, time, user_id) VALUES (:name, :description, :date, :time, :user_id)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':date', $input['date']);
    $stmt->bindParam(':time', $input['time']);
    $stmt->bindParam(':user_id', $input['user_id']);
    $stmt->execute();
    http_response_code(201);
    echo json_encode(['message' => 'Booking created successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Update existing booking
    $stmt = $db->prepare('UPDATE bookings SET name = :name, description = :description, date = :date, time = :time WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':description', $input['description']);
    $stmt->bindParam(':date', $input['date']);
    $stmt->bindParam(':time', $input['time']);
    $stmt->execute();
    http_response_code(200);
    echo json_encode(['message' => 'Booking updated successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Delete booking
    $stmt = $db->prepare('DELETE FROM bookings WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(204);
    echo json_encode(['message' => 'Booking deleted successfully']);
}

// Close database connection
$db = null;

// Set response headers
header('Content-Type: application/json');