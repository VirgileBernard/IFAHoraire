<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Vérifier si l'utilisateur existe dans la table utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si l'utilisateur n'est pas trouvé dans `utilisateur`, chercher dans `admin`
    if (!$user) {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($user) {
        // Si le mot de passe est NULL, rediriger vers set_password.php
        if (is_null($user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = isset($user['classe_id']) ? 'utilisateur' : 'admin';
            $_SESSION['is_admin'] = !isset($user['classe_id']); // Définir is_admin
            header('Location: set_password.php');
            exit;
        }

        // Vérification du mot de passe
        if (password_verify($password, $user['password'])) {
            $_SESSION['prenom'] = $user['prenom']; // Stocker le prénom
            $_SESSION['nom'] = $user['nom'];  // Stocker le nom
            $_SESSION['user'] = $user['prenom'] . ' ' . $user['nom']; // le nom en entier
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = isset($user['classe_id']) ? 'utilisateur' : 'admin';
            $_SESSION['is_admin'] = !isset($user['classe_id']); // Définir is_admin
            header('Location: index.php');
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/IFAPME/gitIFAHoraire/IFAHoraire/Public/css/calendar.css?v=<?= time(); ?>">
</head>
<body class="bodyLogin">
    <div class="login-container">
        <h1>IFA - HORAIRE</h1>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="email">Votre email :</label>
            <input type="email" id="email" name="email" placeholder="email" required>
            <label for="password">Votre mot de passe :</label>
            <input type="password" id="password" name="password" placeholder="password">
            <button type="submit">CONNEXION</button>
        </form>
    </div>
</body>
</html>
