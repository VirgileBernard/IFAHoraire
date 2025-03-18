<?php
// Connexion locale à la base de données
$host = 'localhost'; // Reste en local
$dbname = 'horaireifapme'; // Nom de la base en local
$user = 'root'; // Identifiant local (par défaut sous WAMP/XAMPP)
$pass = ''; // Mot de passe vide sous WAMP (à voir selon ta config)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
    exit;
}
?>
