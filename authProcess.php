<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    // --- REGISTER ---
    if ($action == 'register') {
        $nama = $conn->real_escape_string($_POST['nama']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        // Cek Email
        $cek = $conn->query("SELECT idUser FROM pelanggan WHERE email = '$email'");
        if ($cek->num_rows > 0) {
            header("Location: index.php?error=emailtaken");
            exit();
        }

        // Hash & Insert
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO pelanggan (nama, email, password) VALUES ('$nama', '$email', '$hashedPwd')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['idUser'] = $conn->insert_id;
            $_SESSION['nama'] = $nama;
            header("Location: index.php");
            exit();
        } else {
            die("Error Register: " . $conn->error);
        }
    }

    // --- LOGIN ---
    elseif ($action == 'login') {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        $result = $conn->query("SELECT * FROM pelanggan WHERE email = '$email'");

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            // PERBAIKAN DI SINI:
            // Cek apakah database mengembalikan kolom 'PASSWORD' (huruf besar) atau 'password' (kecil)
            $dbPassword = isset($row['PASSWORD']) ? $row['PASSWORD'] : (isset($row['password']) ? $row['password'] : '');

            // Debugging (Akan muncul di layar jika password salah/kosong)
            if (empty($dbPassword)) {
                die("Error: Kolom password tidak terbaca. Coba cek struktur database Anda.");
            }

            if (password_verify($password, $dbPassword)) {
                $_SESSION['idUser'] = $row['idUser'];
                $_SESSION['nama'] = $row['nama'];
                header("Location: index.php");
                exit();
            } else {
                // Jangan redirect dulu, biar ketahuan kalau salah password
                die("Login Gagal: Password yang Anda masukkan salah.");
            }
        } else {
            die("Login Gagal: Email tidak ditemukan.");
        }
    }
}
?>