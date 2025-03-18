<?php
$password = 'password123';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "Mot de passe : $password <br>";
echo "Hash : $hashedPassword <br>";

if (password_verify($password, $hashedPassword)) {
    echo "✅ Le mot de passe est correct !";
} else {
    echo "❌ Le mot de passe est incorrect !";
}
?>
