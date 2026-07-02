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
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all bookings
    $stmt = $pdo->prepare('SELECT * FROM حجز');
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return bookings
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($bookings);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($inputData['customer_name']) || !isset($inputData['booking_date']) || !isset($inputData['room_number'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $customerName = filter_var($inputData['customer_name'], FILTER_SANITIZE_STRING);
    $bookingDate = filter_var($inputData['booking_date'], FILTER_SANITIZE_STRING);
    $roomNumber = filter_var($inputData['room_number'], FILTER_SANITIZE_NUMBER_INT);

    // Insert new booking
    $stmt = $pdo->prepare('INSERT INTO حجز (customer_name, booking_date, room_number) VALUES (:customer_name, :booking_date, :room_number)');
    $stmt->bindParam(':customer_name', $customerName);
    $stmt->bindParam(':booking_date', $bookingDate);
    $stmt->bindParam(':room_number', $roomNumber);
    $stmt->execute();

    // Return new booking
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Booking created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['customer_name']) || !isset($inputData['booking_date']) || !isset($inputData['room_number'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $customerName = filter_var($inputData['customer_name'], FILTER_SANITIZE_STRING);
    $bookingDate = filter_var($inputData['booking_date'], FILTER_SANITIZE_STRING);
    $roomNumber = filter_var($inputData['room_number'], FILTER_SANITIZE_NUMBER_INT);

    // Update booking
    $stmt = $pdo->prepare('UPDATE حجز SET customer_name = :customer_name, booking_date = :booking_date, room_number = :room_number WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':customer_name', $customerName);
    $stmt->bindParam(':booking_date', $bookingDate);
    $stmt->bindParam(':room_number', $roomNumber);
    $stmt->execute();

    // Return updated booking
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Booking updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($userRole !== 'admin') {
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
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete booking
    $stmt = $pdo->prepare('DELETE FROM حجز WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return deleted booking
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Booking deleted successfully'));
    exit;
}

// Return error for invalid request method
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;