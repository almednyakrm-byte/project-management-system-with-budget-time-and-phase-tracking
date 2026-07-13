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
            background-image: linear-gradient(to bottom, #1a1d23, #2b2f3a);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
        .glassmorphic {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1)), linear-gradient(0deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            background-blend-mode: overlay, overlay;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .glassmorphic::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1)), linear-gradient(0deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            background-blend-mode: overlay, overlay;
            border-radius: 10px;
            z-index: -1;
        }
        .gradient {
            background: linear-gradient(to bottom, #1a1d23, #2b2f3a);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
    </style>
</head>
<body class="bg-gray-900 h-screen">
    <div class="flex justify-center items-center h-screen">
        <div class="glassmorphic max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-emerald-600 mb-4">Login</h2>
            <form id="login-form" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600" required>
                </div>
                <button type="submit" class="w-full px-4 py-2 text-white bg-teal-500 rounded-md hover:bg-teal-700 focus:outline-none focus:ring focus:ring-emerald-600 focus:border-emerald-600">Login</button>
            </form>
            <p class="text-sm text-gray-600 mt-4">Don't have an account? <a href="register.php" class="text-emerald-600 hover:text-emerald-800">Register</a></p>
        </div>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
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
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page using Tailwind CSS. It includes a glassmorphic layout, gradients, and a form for username and password input. The form is validated using standard HTML input pattern validators to support Arabic and Latin characters. The form is submitted using AJAX with the Fetch API to the `../backend/auth.php?action=login` endpoint. The response is handled dynamically, and error alerts are displayed if the login fails. A direct link to the `register.php` page is also included.