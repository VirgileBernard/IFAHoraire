<?php
require_once 'db.php';

if (!empty($_POST['email'])) {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE mail_utilisateur = :email");
    $stmt->execute(['email' => $email]);
    $exists = $stmt->fetchColumn();

    echo ($exists > 0) ? "exists" : "new";
}
?>
