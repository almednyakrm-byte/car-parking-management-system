<?php

require_once 'db.php';

// Get user role and check if user is logged in
if (!isset($_SESSION['role']) || !isset($_SESSION['logged_in'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$userRole = $_SESSION['role'];

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['id'])) {
    // Get payment system by ID
    $stmt = $pdo->prepare("SELECT * FROM payment_systems WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $paymentSystem = $stmt->fetch();

    if ($paymentSystem) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($paymentSystem);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Payment system not found'));
    }
} elseif (isset($_GET['all'])) {
    // Get all payment systems
    $stmt = $pdo->prepare("SELECT * FROM payment_systems");
    $stmt->execute();
    $paymentSystems = $stmt->fetchAll();

    if ($paymentSystems) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($paymentSystems);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'No payment systems found'));
    }
} elseif (isset($_GET['search'])) {
    // Search payment systems
    $searchQuery = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM payment_systems WHERE name LIKE :search OR description LIKE :search");
    $stmt->bindParam(':search', '%' . $searchQuery . '%');
    $stmt->execute();
    $paymentSystems = $stmt->fetchAll();

    if ($paymentSystems) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($paymentSystems);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'No payment systems found'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}

// Handle POST request
if (isset($_POST['name']) && isset($_POST['description'])) {
    // Validate input data
    if (empty($_POST['name']) || empty($_POST['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Insert payment system
    $stmt = $pdo->prepare("INSERT INTO payment_systems (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Payment system created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create payment system'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}

// Handle PUT request
if (isset($_PUT['id']) && isset($_PUT['name']) && isset($_PUT['description'])) {
    // Validate input data
    if (empty($_PUT['name']) || empty($_PUT['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($_PUT['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_PUT['description'], FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update payment system
    $stmt = $pdo->prepare("UPDATE payment_systems SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $_PUT['id']);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Payment system updated successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Payment system not found'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}

// Handle DELETE request
if (isset($_DELETE['id'])) {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete payment system
    $stmt = $pdo->prepare("DELETE FROM payment_systems WHERE id = :id");
    $stmt->bindParam(':id', $_DELETE['id']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Payment system deleted successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Payment system not found'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}