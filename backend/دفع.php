<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define table name
$table_name = 'دفع';

// Define columns
$columns = ['id', 'name', 'amount', 'date'];

// Validate and sanitize input data
if (!isset($input['id']) || !isset($input['name']) || !isset($input['amount']) || !isset($input['date'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

foreach ($columns as $column) {
    $input[$column] = trim($input[$column]);
}

// GET all records
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM $table_name");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// GET single record
if (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM $table_name WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Record not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// POST new record
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    try {
        $stmt = $pdo->prepare("INSERT INTO $table_name (name, amount, date) VALUES (:name, :amount, :date)");
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':amount', $input['amount']);
        $stmt->bindParam(':date', $input['date']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// PUT update record
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    try {
        $stmt = $pdo->prepare("UPDATE $table_name SET name = :name, amount = :amount, date = :date WHERE id = :id");
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':amount', $input['amount']);
        $stmt->bindParam(':date', $input['date']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}

// DELETE record
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    try {
        if ($_SESSION['user_role'] == 'admin') {
            $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
            $stmt->bindParam(':id', $input['id']);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Record deleted successfully']);
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
}