<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Define a function to check session status
function checkSessionStatus() {
    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

// Define a function to handle user registration
function registerUser() {
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check if the form fields are set
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            // Sanitize the input fields
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            // Hash the password using password_hash()
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare the SQL query to insert the user data into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            // Execute the query
            if ($stmt->execute()) {
                // Return a JSON response indicating success
                echo json_encode(array('success' => true, 'message' => 'User registered successfully'));
            } else {
                // Return a JSON response indicating failure
                echo json_encode(array('success' => false, 'message' => 'Failed to register user'));
            }
        } else {
            // Return a JSON response indicating failure
            echo json_encode(array('success' => false, 'message' => 'Invalid form submission'));
        }
    } else {
        // Return a JSON response indicating failure
        echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
    }
}

// Define a function to handle user login
function loginUser() {
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check if the form fields are set
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Sanitize the input fields
            $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

            // Prepare the SQL query to select the user data from the database
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);

            // Execute the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists
            if ($result->num_rows > 0) {
                // Fetch the user data
                $row = $result->fetch_assoc();

                // Verify the password using password_verify()
                if (password_verify($password, $row['password'])) {
                    // Start a new session for the user
                    session_start();
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];

                    // Return a JSON response indicating success
                    echo json_encode(array('success' => true, 'message' => 'User logged in successfully'));
                } else {
                    // Return a JSON response indicating failure
                    echo json_encode(array('success' => false, 'message' => 'Invalid password'));
                }
            } else {
                // Return a JSON response indicating failure
                echo json_encode(array('success' => false, 'message' => 'User not found'));
            }
        } else {
            // Return a JSON response indicating failure
            echo json_encode(array('success' => false, 'message' => 'Invalid form submission'));
        }
    } else {
        // Return a JSON response indicating failure
        echo json_encode(array('success' => false, 'message' => 'Invalid request method'));
    }
}

// Define a function to handle user logout
function logoutUser() {
    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        // Destroy the session
        session_destroy();

        // Return a JSON response indicating success
        echo json_encode(array('success' => true, 'message' => 'User logged out successfully'));
    } else {
        // Return a JSON response indicating failure
        echo json_encode(array('success' => false, 'message' => 'User not logged in'));
    }
}

// Handle AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    // Check if the request is for the session status
    if (isset($_GET['action']) && $_GET['action'] == 'check_session') {
        // Return a JSON response indicating the session status
        echo json_encode(array('success' => checkSessionStatus()));
    } elseif (isset($_POST['action']) && $_POST['action'] == 'login') {
        // Handle user login
        loginUser();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'register') {
        // Handle user registration
        registerUser();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'logout') {
        // Handle user logout
        logoutUser();
    }
}


This code includes the following security features:

*   **Input validation and sanitization**: The code uses `filter_var()` to sanitize user input and prevent SQL injection attacks.
*   **Password hashing**: The code uses `password_hash()` to securely store passwords in the database.
*   **Prepared statements**: The code uses prepared statements to prevent SQL injection attacks.
*   **Session handling**: The code uses `session_start()` and `session_destroy()` to securely manage user sessions.
*   **JSON responses**: The code returns JSON responses to handle AJAX requests, making it easier to handle client-side errors and provide feedback to the user.