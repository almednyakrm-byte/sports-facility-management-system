<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #0f0f0f, #0f0f0f);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
        .glassmorphic {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .gradient {
            background: linear-gradient(90deg, #0f0f0f, #0f0f0f);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .gradient::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #0f0f0f, #0f0f0f);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="h-screen flex justify-center items-center bg-gray-200">
        <div class="glassmorphic w-96 p-10 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold text-emerald-600 mb-4">Login</h1>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <div id="username-error" class="text-red-500 hidden"></div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <div id="password-error" class="text-red-500 hidden"></div>
                </div>
                <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
                <p class="text-gray-500 text-xs mt-2">Don't have an account? <a href="register.php" class="text-teal-500 hover:text-teal-700">Register</a></p>
            </form>
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
                    document.getElementById('username-error').textContent = data.username_error;
                    document.getElementById('password-error').textContent = data.password_error;
                    document.getElementById('username-error').classList.remove('hidden');
                    document.getElementById('password-error').classList.remove('hidden');
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again.');
            }
        });
    </script>
</body>
</html>