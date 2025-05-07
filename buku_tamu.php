<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "bukutamu";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);
        $date = date("Y-m-d H:i:s");

        // Insert ke database
        $stmt = $conn->prepare("INSERT INTO tbEntry (name, email, message, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $message, $date);
        $stmt->execute();
        $stmt->close();
    }
}

// Ambil data dari database
$guest_entries = [];
$sql = "SELECT * FROM tbEntry ORDER BY date DESC"; // urut terbaru di atas
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $guest_entries[] = $row;
    }
}

$conn->close();

// MASIH PAKAI OPERASI FILE
/* $guestbook_file = "data_buku.txt";

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cek apakah data dikirim lengkap
    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);
        $date = date("Y-m-d H:i:s");

        $entry = "$date | $name | $email | $message" . PHP_EOL;
        file_put_contents($guestbook_file, $entry, FILE_APPEND);
    }
}

// Ambil data yang udah disimpan
$guest_entries = file_exists($guestbook_file) ? file($guestbook_file, FILE_IGNORE_NEW_LINES) : [];*/
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to right, #ffafbd, #ffc3a0);
        margin: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;        
        }

        .welcome-container {
            width: 90%; 
            display: flex;
            justify-content: center;
        }

        .welcome-box {
            width: 100%; /* Biar sama dengan Buku Tamu */
            max-width: 600px; /*  agar tidak terlalu besar di layar besar */
            background: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }


        /* Styling untuk teks welcome */
        .welcome-text h2 {
            font-size: 24px;
            font-weight: bold;
            color: #ff4f8b;
            margin-bottom: 5px; 
        }

        .welcome-text p {
            color: #f99b3e;
            font-size: 18px;
            font-weight: bold;
            margin-top: 0; 
        }


        /* Tombol Logout */
        .logout {
            background: #ff4f8b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout:hover {
            background: #ff2f6b;
            transform: scale(1.05);
        }

        .buku-tamu-container {
            width: 90%; 
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .buku-tamu {
            width: 100%; 
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .buku-tamu h2 {
            font-size: 24px;
            color: #ff4f8b;
            font-weight: bold;
            text-align: center; 
        }

        /* Styling Form */


        label {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            display: block;
            text-align: left; 
            margin-bottom: 5px;
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: flex-start; 
        }


        input, textarea {
            width: 100%; 
            padding: 12px;
            margin: 5px 0 15px 0;
            border: 2px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box; 
        }

        input[type="submit"] {
            width: 100%; 
            background: #FF69B4;
            color: white;
            cursor: pointer;
            transition: 0.3s;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
            padding: 12px;
        }

        
        input:focus, textarea:focus {
            border-color: #FF69B4; /* Border pink */
            box-shadow: 0 0 10px rgba(255, 105, 180, 0.5); /* Glow pink */
            outline: none; 
         }

        input[type="submit"]:hover {
            background:rgb(255, 180, 137);
            
        }

        /* Daftar Guest List */
        .guest-list {
            width: 90%;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        .guest-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            text-align: left; 
        }

        .guest-list h2 {
            color: #ff4f8b; /* Warna pink */
            font-weight: bold;
            text-align: center; 
        }

        /* Tanggal di daftar tamu */
        .date {
            font-size: 14px;
            color: gray;
        }


    </style>
</head>
<body>

<div class="welcome-container">
    <div class="welcome-box">
        <div class="welcome-text">
            <h2>Hi👋🏻 <span><?php echo htmlspecialchars($username); ?></span></h2>
            <p>Welcome Back ༼⁠ ⁠つ⁠ ⁠◕⁠‿⁠◕⁠ ⁠༽⁠つ</p>
        </div>
        <a href="logout.php" class="logout">Log out</a>

    </div>
</div>

    <div class="buku-tamu-container">
        <div class="buku-tamu">
            <h2>📓 BUKU TAMU 📓</h2>
            <form method="POST" action="">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                
                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Please input your message" required></textarea>
                
                <input type="submit" value="Submit">
            </form>

        </div>
    </div>
</div>


        <div class="guest-list">
            <h2>👥 Daftar Tamu 👥</h2>
            <div>
            <?php
                // Menampilkan data dengan format: Nama(Email) di baris pertama, lalu pesan di baris kedua
                foreach ($guest_entries as $entry) {
                    $name = htmlspecialchars($entry['name']);
                    $email = htmlspecialchars($entry['email']);
                    $message = htmlspecialchars($entry['message']);
                    $date = htmlspecialchars($entry['date']);
                
                    echo "<p><strong>$name</strong> <em>($email)</em> <span style='float:right; color: gray'>$date</span><br>$message</p>";
                    echo "<hr>";


                    //MASIH PAKAI OPERASI FILE
                    /* 
                    foreach ($guest_entries as $entry) {
                        $parts = explode("|", $entry, 4);
                    
                        // Pastikan ada 4 bagian sebelum digunakan
                        if (count($parts) < 4) {
                            continue; // Lewati jika data tidak lengkap
                        }
                    
                        list($date, $name, $email, $message) = $parts; */
                }
                
                
            ?>

            </div>
        </div>

        


</div>

    </div>
</body>
</html>
