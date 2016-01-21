<?php
// Password to be encrypted for a .htpasswd file
$clearTextPassword = $_GET['pass'];

// Encrypt password
$password = crypt($clearTextPassword, base64_encode($clearTextPassword));

// Print encrypted password
echo "<h3>Copy This To Your .htpasswd file</h3>";

echo $password;
?>