**create_حجز.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $guests = $_POST['guests'];

    // Validate form data
    if (!empty($name) && !empty($email) && !empty($phone) && !empty($date) && !empty($time) && !empty($guests)) {
        // Prepare data for AJAX request
        $data = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'date' => $date,
            'time' => $time,
            'guests' => $guests
        );

        // Send AJAX request
        $ajaxResponse = sendAjaxRequest($data);

        // Check if AJAX request was successful
        if ($ajaxResponse['success']) {
            // Redirect back to list page
            header('Location: list_حجز.php');
            exit;
        } else {
            // Display error message
            echo '<div class="bg-red-500 text-white p-4 mb-4 rounded-lg">' . $ajaxResponse['message'] . '</div>';
        }
    } else {
        // Display error message
        echo '<div class="bg-red-500 text-white p-4 mb-4 rounded-lg">Please fill in all fields.</div>';
    }
}

// Function to send AJAX request
function sendAjaxRequest($data) {
    $url = '../backend/حجز.php';
    $options = array(
        'http' => array(
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $response = json_decode(file_get_contents($url, false, $context), true);
    return $response;
}

// Include footer
include 'footer.php';
?>

<!-- Create new حجز form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New حجز</h2>
    <form id="create_حجز_form" class="space-y-4">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="name">Name</label>
                <input class="appearance-none block w-full bg-slate-100 text-slate-900 border border-slate-300 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" id="name" type="text" name="name" required>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="email">Email</label>
                <input class="appearance-none block w-full bg-slate-100 text-slate-900 border border-slate-300 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" id="email" type="email" name="email" required>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="phone">Phone</label>
                <input class="appearance-none block w-full bg-slate-100 text-slate-900 border border-slate-300 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" id="phone" type="tel" name="phone" required>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="date">Date</label>
                <input class="appearance-none block w-full bg-slate-100 text-slate-900 border border-slate-300 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" id="date" type="date" name="date" required>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="time">Time</label>
                <input class="appearance-none block w-full bg-slate-100 text-slate-900 border border-slate-300 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" id="time" type="time" name="time" required>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="guests">Guests</label>
                <input class="appearance-none block w-full bg-slate-100 text-slate-900 border border-slate-300 rounded-lg py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-indigo-500" id="guests" type="number" name="guests" required>
            </div>
        </div>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg" type="submit">Create حجز</button>
    </form>
</div>

<!-- JavaScript code to handle form submission -->
<script>
    document.getElementById('create_حجز_form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/حجز.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_حجز.php';
            } else {
                document.getElementById('create_حجز_form').innerHTML += '<div class="bg-red-500 text-white p-4 mb-4 rounded-lg">' + data.message + '</div>';
            }
        })
        .catch(error => console.error(error));
    });
</script>

This code creates a premium Tailwind UI form with all necessary fields for the 'حجز' module. It includes session validation and uses AJAX to POST the form data to the backend script. On success, it redirects back to the list page.