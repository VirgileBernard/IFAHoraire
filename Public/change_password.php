<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilisateur SET password = ?, first_login = 0 WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);

        // Si l'utilisateur est un admin
        $stmt = $pdo->prepare("UPDATE admin SET password = ?, first_login = 0 WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);

        $_SESSION['message'] = "Mot de passe mis à jour avec succès.";
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Changer le mot de passe</title>
</head>
<body>
    <h1>Changer votre mot de passe</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" id="new_password" name="new_password" required>
        
        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        
        <button type="submit">Modifier</button>
    </form>
</body>
</html>
