<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Read inputs from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $limit = isset($input['limit']) ? intval($input['limit']) : 10;
    $offset = isset($input['offset']) ? intval($input['offset']) : 0;

    // SQL query to select all payments
    $sql = "SELECT * FROM دفع ORDER BY id LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and return results
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($payments);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = isset($input['name']) ? trim($input['name']) : '';
    $amount = isset($input['amount']) ? floatval($input['amount']) : 0;
    $userID = $_SESSION['userID'];

    // SQL query to insert new payment
    $sql = "INSERT INTO دفع (name, amount, user_id) VALUES (:name, :amount, :userID)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':amount', $amount, PDO::PARAM_FLOAT);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Payment created successfully'));
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Failed to create payment'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $id = isset($input['id']) ? intval($input['id']) : 0;
    $name = isset($input['name']) ? trim($input['name']) : '';
    $amount = isset($input['amount']) ? floatval($input['amount']) : 0;

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query to update payment
    $sql = "UPDATE دفع SET name = :name, amount = :amount WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':amount', $amount, PDO::PARAM_FLOAT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Payment updated successfully'));
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Failed to update payment'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $id = isset($input['id']) ? intval($input['id']) : 0;

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // SQL query to delete payment
    $sql = "DELETE FROM دفع WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Payment deleted successfully'));
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Failed to delete payment'));
    }
} else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Method not allowed'));
}


This code provides a basic implementation of the RESTful API for the 'دفع' module. It supports full CRUD operations and includes user role authorization checks. The code uses PDO prepared statements to prevent SQL injections and returns proper HTTP response status codes and application/json Content-Type headers.