<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
        
        .glassmorphic {
            background: linear-gradient(90deg, #1a1d23, #2c2f36);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .glassmorphic::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #1a1d23, #2c2f36);
            filter: blur(10px);
            z-index: -1;
        }
        
        .gradient {
            background: linear-gradient(90deg, #1a1d23, #2c2f36);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="h-screen bg-gray-900 flex justify-center items-center">
    <div class="glassmorphic w-96 p-8 bg-white rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-center mb-4">Login</h1>
        <form id="login-form" class="space-y-4">
            <div class="space-y-1">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="block w-full px-3 py-2 placeholder-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                <div id="username-error" class="text-red-500 text-sm"></div>
            </div>
            <div class="space-y-1">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="block w-full px-3 py-2 placeholder-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Password">
                <div id="password-error" class="text-red-500 text-sm"></div>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-transparent rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">Login</button>
        </form>
        <p class="text-sm text-gray-500 mt-4">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-600">Register</a></p>
    </div>
    
    <script>
        const form = document.getElementById('login-form');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('username-error');
        const passwordError = document.getElementById('password-error');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();
            
            if (username === '') {
                usernameError.textContent = 'Username is required';
                return;
            }
            
            if (password === '') {
                passwordError.textContent = 'Password is required';
                return;
            }
            
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    usernameError.textContent = data.usernameError || '';
                    passwordError.textContent = data.passwordError || '';
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in');
            }
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking login page with a glassmorphic layout and gradients. It includes a form for username and password input, with validation rules and error messages. The form is submitted using AJAX with the Fetch API, and the response is handled dynamically. The code also includes a direct link to the register page.