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

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all bookings
    $stmt = $pdo->prepare('SELECT * FROM حجز_مواقف');
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return bookings
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($bookings);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($inputData['parking_id']) || !isset($inputData['user_id']) || !isset($inputData['start_time']) || !isset($inputData['end_time'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $parkingID = (int) $inputData['parking_id'];
    $userID = (int) $inputData['user_id'];
    $startTime = date('Y-m-d H:i:s', strtotime($inputData['start_time']));
    $endTime = date('Y-m-d H:i:s', strtotime($inputData['end_time']));

    // Insert new booking
    $stmt = $pdo->prepare('INSERT INTO حجز_مواقف (parking_id, user_id, start_time, end_time) VALUES (:parking_id, :user_id, :start_time, :end_time)');
    $stmt->bindParam(':parking_id', $parkingID);
    $stmt->bindParam(':user_id', $userID);
    $stmt->bindParam(':start_time', $startTime);
    $stmt->bindParam(':end_time', $endTime);
    $stmt->execute();

    // Return new booking
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Booking created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['parking_id']) || !isset($inputData['user_id']) || !isset($inputData['start_time']) || !isset($inputData['end_time'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = (int) $inputData['id'];
    $parkingID = (int) $inputData['parking_id'];
    $userID = (int) $inputData['user_id'];
    $startTime = date('Y-m-d H:i:s', strtotime($inputData['start_time']));
    $endTime = date('Y-m-d H:i:s', strtotime($inputData['end_time']));

    // Update booking
    $stmt = $pdo->prepare('UPDATE حجز_مواقف SET parking_id = :parking_id, user_id = :user_id, start_time = :start_time, end_time = :end_time WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':parking_id', $parkingID);
    $stmt->bindParam(':user_id', $userID);
    $stmt->bindParam(':start_time', $startTime);
    $stmt->bindParam(':end_time', $endTime);
    $stmt->execute();

    // Return updated booking
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Booking updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = (int) $inputData['id'];

    // Delete booking
    $stmt = $pdo->prepare('DELETE FROM حجز_مواقف WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted booking
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Booking deleted successfully'));
    exit;
}