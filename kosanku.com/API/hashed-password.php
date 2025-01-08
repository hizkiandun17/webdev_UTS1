<?php
// Ganti 'your_password' dengan password yang ingin Anda hash
$password = 'admin1234'; // Misalnya, 'admin123'
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Tampilkan hasil hash
echo $hashed_password;
?>