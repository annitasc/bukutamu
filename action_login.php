<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bukutamu";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan sistem."]);
    exit();
}

$user = mysqli_real_escape_string($conn, $_POST['username']);
$pass = mysqli_real_escape_string($conn, $_POST['password']);

$sql = "SELECT * FROM tbuser WHERE username='$user' AND password='$pass'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['username'] = $user;
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Username atau password salah!"]);
}

$conn->close();




/* 
//BELUM PAKAI AJAX
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bukutamu";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Tangkap data dari form login
$user = $_POST['username'];
$pass = $_POST['password'];

// Amankan input (penting biar gak bisa di-**SQL Injection**)
$user = mysqli_real_escape_string($conn, $user);
$pass = mysqli_real_escape_string($conn, $pass);

// Cek ke database
$sql = "SELECT * FROM tbuser WHERE username='$user' AND password='$pass'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Login sukses
    $_SESSION['username'] = $user;
    header("Location: buku_tamu.php");
    exit();
} else {
    // Login gagal
    $_SESSION['error'] = "Username atau password salah!";
    header("Location: login.php");
    exit();
}

$conn->close();

//BELUM PAKE AJAX & MASIH PAKAI OPERASI FILE

 /* if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Data user disimpan di users.txt
    $users_file = "pengguna.txt";
    $users = file_exists($users_file) ? file($users_file, FILE_IGNORE_NEW_LINES) : [];

    // Cek apakah username dan password cocok
    $is_valid = false;
    foreach ($users as $user) {
        list($stored_user, $stored_pass) = explode("|", $user);
        if ($username === $stored_user && $password === $stored_pass) {
            $is_valid = true;
            break;
        }
    }

    if ($is_valid) {
        $_SESSION['username'] = $username;
        header("Location: buku_tamu.php");
        exit();
    } else {
        $_SESSION['error'] = "Username atau password salah!";
        header("Location: login.php");
        exit();
    }
}
*/

?>