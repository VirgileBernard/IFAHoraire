<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php'; // Connexion à la base de données

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}


// Récupérer les informations de l'utilisateur
$email = $_SESSION['email'];
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable !");
}

// Gestion des mises à jour
$message = '';
$has_changes = false; // Indicateur de changements
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mise à jour de la classe
    if (isset($_POST['classe']) && $_POST['classe'] != $user['classe_id']) {
        $classe = $_POST['classe'];
        $stmt = $pdo->prepare("UPDATE utilisateurs SET classe_id = :classe WHERE email = :email");
        $stmt->execute(['classe' => $classe, 'email' => $email]);
        $_SESSION['classe'] = $classe; // Mettre à jour la session
        $message = "Classe mise à jour avec succès.";
        $has_changes = true;
    }

    // Mise à jour du mot de passe
    if (!empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password === $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE utilisateurs SET password = :password WHERE email = :email");
            $stmt->execute(['password' => $hashed_password, 'email' => $email]);
            $message = "Mot de passe mis à jour avec succès.";
            $has_changes = true;
        } else {
            $message = "Les mots de passe ne correspondent pas.";
        }
    }

    // Redirection vers le calendrier si le bouton "Confirmer et revenir au calendrier" est cliqué
    if (isset($_POST['confirm_changes'])) {
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1><?= htmlspecialchars($user['prenom'] ) ?>, souhaitez-vous modifier votre profil?</h1>
        <p>Statut : <strong><?= $_SESSION['is_admin'] ? 'Admin' : 'Élève' ?></strong></p>

        <?php if (!$_SESSION['is_admin']): ?>
            <form method="POST" class="mb-4">
                <label for="classe" class="form-label">Votre classe :</label>
                <select name="classe" id="classe" class="form-select">
                    <option value="1" <?= ($user['classe_id'] == '1') ? 'selected' : '' ?>>Classe A</option>
                    <option value="2" <?= ($user['classe_id'] == '2') ? 'selected' : '' ?>>Classe B</option>
                </select>
                <button type="submit" class="btn btn-primary mt-2">Mettre à jour la classe</button>
            </form>
        <?php endif; ?>

        <form method="POST">
            <h3>Modifier votre mot de passe</h3>
            <div class="mb-3">
                <label for="password" class="form-label">Nouveau mot de passe :</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmer le mot de passe :</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Mettre à jour le mot de passe</button>
        </form>

        <?php if ($has_changes): ?>
            <form method="POST" class="mt-4">
                <button type="submit" name="confirm_changes" class="btn btn-outline-success">Confirmer et revenir au calendrier</button>
            </form>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="alert alert-info mt-3"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </div>
</body>
</html>