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
    'GET' => array('/' => 'readAll', '/:id' => 'readOne'),
    'POST' => '/create',
    'PUT' => '/update/:id',
    'DELETE' => '/delete/:id'
);

// Get route
$route = $_SERVER['REQUEST_METHOD'] . $_SERVER['REQUEST_URI'];
$route = explode('?', $route);
$route = end($route);

// Get route parts
$routeParts = explode('/', $route);
$routeParts = array_filter($routeParts);

// Check if route is valid
if (!isset($routes[$_SERVER['REQUEST_METHOD']][$route])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
    exit;
}

// Get route handler
$handler = $routes[$_SERVER['REQUEST_METHOD']][$route];

// Handle route
switch ($handler) {
    case 'readAll':
        $result = readAll();
        break;
    case 'readOne':
        $result = readOne($routeParts[1]);
        break;
    case 'create':
        $result = create();
        break;
    case 'update':
        $result = update($routeParts[1]);
        break;
    case 'delete':
        $result = delete($routeParts[1]);
        break;
}

// Output result
http_response_code($result['status']);
header('Content-Type: application/json');
echo json_encode($result);

// Functions
function readAll() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM مواقف_سيارات");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return array('status' => 200, 'data' => $result);
}

function readOne($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM مواقف_سيارات WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        http_response_code(404);
        return array('status' => 404, 'error' => 'Not Found');
    }
    return array('status' => 200, 'data' => $result);
}

function create() {
    global $db;
    // Validate input
    if (!isset($input['name']) || !isset($input['capacity'])) {
        http_response_code(400);
        return array('status' => 400, 'error' => 'Invalid input');
    }
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $capacity = filter_var($input['capacity'], FILTER_SANITIZE_NUMBER_INT);
    
    // Sanitize input
    $name = PDO::quote($name);
    $capacity = PDO::quote($capacity);
    
    // Insert data
    $stmt = $db->prepare("INSERT INTO مواقف_سيارات (name, capacity) VALUES (:name, :capacity)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->execute();
    return array('status' => 201, 'message' => 'Created successfully');
}

function update($id) {
    global $db;
    // Validate input
    if (!isset($input['name']) || !isset($input['capacity'])) {
        http_response_code(400);
        return array('status' => 400, 'error' => 'Invalid input');
    }
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $capacity = filter_var($input['capacity'], FILTER_SANITIZE_NUMBER_INT);
    
    // Sanitize input
    $name = PDO::quote($name);
    $capacity = PDO::quote($capacity);
    
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        return array('status' => 403, 'error' => 'Forbidden');
    }
    
    // Update data
    $stmt = $db->prepare("UPDATE مواقف_سيارات SET name = :name, capacity = :capacity WHERE id = :id");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':capacity', $capacity);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return array('status' => 200, 'message' => 'Updated successfully');
}

function delete($id) {
    global $db;
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        return array('status' => 403, 'error' => 'Forbidden');
    }
    
    // Delete data
    $stmt = $db->prepare("DELETE FROM مواقف_سيارات WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return array('status' => 200, 'message' => 'Deleted successfully');
}