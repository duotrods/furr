<?php
// Set your desired password here
$password = 'Furradmin_2025!';

// Generate the bcrypt hash
$hash = password_hash($password, PASSWORD_BCRYPT);

// Output the hash
echo "Password: " . $password . "\n";
echo "Hash: " . $hash;
?>