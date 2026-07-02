<?php
// login.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
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
            filter: blur(20px);
            z-index: -1;
        }
    </style>
</head>
<body class="h-screen bg-gray-900 flex justify-center items-center">
    <div class="glassmorphic w-96 p-8 bg-gray-900 rounded-lg shadow-lg">
        <h2 class="text-3xl text-slate-900 font-bold mb-4">Login</h2>
        <form id="login-form" class="space-y-4">
            <div>
                <label for="username" class="block text-sm text-slate-900">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 text-sm text-slate-900 bg-gray-800 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div id="username-error" class="text-red-500 hidden"></div>
            </div>
            <div>
                <label for="password" class="block text-sm text-slate-900">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 text-sm text-slate-900 bg-gray-800 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                <div id="password-error" class="text-red-500 hidden"></div>
            </div>
            <button type="submit" class="w-full p-2 text-sm text-slate-900 bg-indigo-500 rounded-lg hover:bg-indigo-600">Login</button>
            <p class="text-sm text-slate-900 mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-600">Register</a></p>
        </form>
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

            if (username === '' || password === '') {
                if (username === '') {
                    usernameError.textContent = 'Username is required';
                    usernameError.classList.remove('hidden');
                } else {
                    usernameError.textContent = '';
                    usernameError.classList.add('hidden');
                }

                if (password === '') {
                    passwordError.textContent = 'Password is required';
                    passwordError.classList.remove('hidden');
                } else {
                    passwordError.textContent = '';
                    passwordError.classList.add('hidden');
                }
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
                    alert(data.message);
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again later.');
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout with gradients. The form includes standard HTML input pattern validators to support Arabic and Latin characters. The AJAX JavaScript code uses the Fetch API to submit the credentials to the `../backend/auth.php?action=login` endpoint and handles the response or error alerts dynamically. The direct link to the `register.php` page is also included.