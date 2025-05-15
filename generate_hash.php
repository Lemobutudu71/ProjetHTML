<?php
// generate_hash.php

// The password to hash
$passwordToHash = "123";

// Generate the hash using PASSWORD_BCRYPT (same as your existing hashes)
$hashedPassword = password_hash($passwordToHash, PASSWORD_BCRYPT);

// Output the hash
// You can then copy this output and paste it into your utilisateur.json file
if ($hashedPassword === false) {
    echo "Error: Password hashing failed.";
} else {
    echo "Password: " . htmlspecialchars($passwordToHash) . "<br>";
    echo "Hashed Password (copy this): <pre>" . htmlspecialchars($hashedPassword) . "</pre>";
}

?> 