<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: buku_tamu.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #ffafbd, #ffc3a0);
            font-family: 'Poppins', sans-serif;
            color: white;
        }
        .container {
            background: white; 
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 300px;
            color:#FFA7A6;
        }
        h2 {
            color: #FF69B4;
            font-weight: bold;
        }

        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-top: 10px;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ccc; 
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.8);
            font-family: 'Poppins', sans-serif;

        }
        input:focus {
            border-color: #FF69B4; /* Border pink */
            box-shadow: 0 0 10px rgba(255, 105, 180, 0.5); /* Glow pink */
            outline: none; /* Hilangkan outline default */
        }
        input[type="submit"] {
            background: #FF69B4;
            width: 290px;
            color: white;
            cursor: pointer;
            transition: 0.3s;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
        }
        input[type="submit"]:hover {
            background:rgb(255, 180, 137);
            
        }

        .title-login {
            margin-bottom: 1px;
        }

        .subtitle {
            margin-top: 0; 
        }

    </style>
</head>
<body>

    <div class="container">
        <h2 class="title-login">Login</h2>
        <h2 class="subtitle" style="color:rgb(250, 171, 114)">⁠*✧⁠。Buku Tamu ⁠*⁠✧。</h2>

        <form id="login-form">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <p id="error-message" style="color: rgb(255, 0, 123); font-size: 12px;"></p>

            <input type="submit" value="Login">
        </form>
    </div>

    <script>
    document.getElementById("login-form").addEventListener("submit", function(e) {
        e.preventDefault(); // Mencegah reload halaman

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "action_login.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Ambil data form
        const username = encodeURIComponent(document.getElementById("username").value);
        const password = encodeURIComponent(document.getElementById("password").value);
        const data = `username=${username}&password=${password}`;

        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    window.location.href = "buku_tamu.php";
                } else {
                    document.getElementById("error-message").textContent = response.message || "Login gagal.";
                }
            } else {
                document.getElementById("error-message").textContent = "Terjadi kesalahan pada server.";
            }
        };

        xhr.onerror = function() {
            document.getElementById("error-message").textContent = "Tidak dapat terhubung ke server.";
        };

        xhr.send(data);
    });
    </script>


</body>

</html>
