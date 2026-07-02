<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, send a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array('status' => 'logged_in', 'user_id' => $user_id, 'username' => $username);
    echo json_encode($response);
    exit;
}

// Check if the user is trying to register or login
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Check if the user is trying to register
    if ($action == 'register') {
        // Check if all required fields are present
        if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Check if the password and confirm password match
            if ($password == $confirm_password) {
                // Check if the username and email are already taken
                $query = "SELECT * FROM users WHERE username = ? OR email = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("ss", $username, $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();

                // If the username or email is already taken, send an error response
                if ($user) {
                    $response = array('status' => 'error', 'message' => 'Username or email already taken');
                    echo json_encode($response);
                    exit;
                }

                // Hash the password using password_hash()
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insert the user into the database
                $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("sss", $username, $email, $password_hash);
                $stmt->execute();

                // Send a success response
                $response = array('status' => 'success', 'message' => 'User registered successfully');
                echo json_encode($response);
                exit;
            } else {
                // If the password and confirm password do not match, send an error response
                $response = array('status' => 'error', 'message' => 'Password and confirm password do not match');
                echo json_encode($response);
                exit;
            }
        } else {
            // If any required field is missing, send an error response
            $response = array('status' => 'error', 'message' => 'Missing required fields');
            echo json_encode($response);
            exit;
        }
    }

    // Check if the user is trying to login
    elseif ($action == 'login') {
        // Check if all required fields are present
        if (isset($_POST['username'], $_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Check if the username and password are valid
            $query = "SELECT * FROM users WHERE username = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // If the username is not found, send an error response
            if (!$user) {
                $response = array('status' => 'error', 'message' => 'Invalid username or password');
                echo json_encode($response);
                exit;
            }

            // Verify the password using password_verify()
            if (password_verify($password, $user['password'])) {
                // If the password is correct, log the user in and send a success response
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $response = array('status' => 'success', 'message' => 'User logged in successfully');
                echo json_encode($response);
                exit;
            } else {
                // If the password is incorrect, send an error response
                $response = array('status' => 'error', 'message' => 'Invalid username or password');
                echo json_encode($response);
                exit;
            }
        } else {
            // If any required field is missing, send an error response
            $response = array('status' => 'error', 'message' => 'Missing required fields');
            echo json_encode($response);
            exit;
        }
    }

    // If the action is neither register nor login, send an error response
    else {
        $response = array('status' => 'error', 'message' => 'Invalid action');
        echo json_encode($response);
        exit;
    }
}

// If the user is not logged in, send a JSON response indicating that they are not logged in
$response = array('status' => 'not_logged_in');
echo json_encode($response);

// Close the database connection
$mysqli->close();
?>