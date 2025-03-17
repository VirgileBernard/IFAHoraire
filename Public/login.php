<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Liste des utilisateurs autorisés (à remplacer par une base de données si nécessaire)
    $users = [
        'admin' => 'password123',
        'user' => 'userpass'
    ];

    if (isset($users[$username]) && password_verify($password, password_hash($users[$username], PASSWORD_DEFAULT))) {
        $_SESSION['user'] = $username;
        setcookie('last_user', $username, time() + (86400 * 30), "/"); // Cookie valide 30 jours
        header('Location: index.php'); // Redirection vers la page protégée
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}

$lastUser = $_COOKIE['last_user'] ?? '';
?>

<!DOCTYPE html> 
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/IFAPME/gitIFAHoraire/IFAHoraire/Public/css/calendar.css?v=<?= time(); ?>">
</head>
<body class=bodyLogin>
    <div class="login-container">
        <h1>IFA - HORAIRE</h1>
        
        <?php if (isset($_SESSION['user'])): ?>
            <p style="color: green;">Connecté en tant que : <?= $_SESSION['user'] ?> <a href="login.php">(Déconnexion)</a></p>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <p style="color: red;"> <?= $error ?> </p>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <label for="username">Votre email :</label>
            <input type="text" id="username" name="username" placeholder="email" value="<?= htmlspecialchars($lastUser) ?>" required>
            <label for="password">Votre mot de passe :</label>
            <input type="password" id="password" name="password" placeholder="password" required>
            <button type="submit">CONNEXION</button>
        </form>
    </div>
</body>
</html>