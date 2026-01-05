<?php

/**
 * Debug superadmin login - Run this file once to verify
 */
require_once 'config/database.php';
require_once 'config/functions.php';

$username = 'super';
$password = 'super123';
$hashedPassword = hashPassword($password);

echo "<h3>Debug Superadmin Login</h3>";
echo "<p>Username: <strong>$username</strong></p>";
echo "<p>Password: <strong>$password</strong></p>";
echo "<p>MD5 Hash: <strong>$hashedPassword</strong></p>";
echo "<hr>";

// Check if superadmin exists in database
$superadmin = db()->fetch("SELECT * FROM tb_superadmin WHERE username = ?", [$username]);

if ($superadmin) {
    echo "<p>✅ Superadmin found in database</p>";
    echo "<p>DB Username: <strong>" . $superadmin['username'] . "</strong></p>";
    echo "<p>DB Password Hash: <strong>" . $superadmin['password'] . "</strong></p>";

    if ($superadmin['password'] === $hashedPassword) {
        echo "<p style='color:green'>✅ Password MATCH!</p>";
    } else {
        echo "<p style='color:red'>❌ Password MISMATCH</p>";
        echo "<p>Expected: $hashedPassword</p>";
        echo "<p>Got: " . $superadmin['password'] . "</p>";
    }
} else {
    echo "<p style='color:red'>❌ Superadmin NOT found in database</p>";
    echo "<p>Run this SQL in phpMyAdmin:</p>";
    echo "<pre>
DELETE FROM tb_superadmin;
INSERT INTO tb_superadmin (username, password, nama_lengkap, email, created_at) VALUES
('super', '$hashedPassword', 'Super Administrator', 'super@ppdb-smk.id', NOW());
</pre>";
}

// Original login query
echo "<hr><h4>Testing Original Login Query</h4>";
$user = db()->fetch(
    "SELECT * FROM tb_superadmin WHERE username = ? AND password = ?",
    [$username, $hashedPassword]
);

if ($user) {
    echo "<p style='color:green'>✅ Login query SUCCESS!</p>";
} else {
    echo "<p style='color:red'>❌ Login query FAILED</p>";
}
