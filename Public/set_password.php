<?php
session_start();
require 'db.php';

// Vérifier si l'utilisateur est bien connecté
// if (!isset($_SESSION['email'])) {
//     header('Location: login.php');
//     exit;
// }

// $email = $_SESSION['email'];
// $role = $_SESSION['role'];

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $newPassword = $_POST['new_password'];
//     $confirmPassword = $_POST['confirm_password'];
//     $classe = $_POST['classe'] ?? null;

//     if ($newPassword !== $confirmPassword) {
//         $error = "Les mots de passe ne correspondent pas.";
//     } else {
//         $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

<<<<<<< HEAD
        if ($role === 'utilisateur') {
            if (!$classe) {
                $error = "Veuillez sélectionner une classe.";
            } else {
                $stmt = $pdo->prepare("UPDATE utilisateurs SET password = ?, classe_id = ? WHERE email = ?");
                $stmt->execute([$hashedPassword, $classe, $email]);
            }
        } else {
            $stmt = $pdo->prepare("UPDATE utilisateurs SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);
        }
=======
//         if ($role === 'utilisateur') {
//             if (!$classe) {
//                 $error = "Veuillez sélectionner une classe.";
//             } else {
//                 $stmt = $pdo->prepare("UPDATE utilisateur SET password = ?, classe_id = ? WHERE email = ?");
//                 $stmt->execute([$hashedPassword, $classe, $email]);
//             }
//         } else {
//             $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE email = ?");
//             $stmt->execute([$hashedPassword, $email]);
//         }
>>>>>>> cf7cb40a579bb20985039b1799eb32230e541a23

//         $_SESSION['message'] = "Mot de passe défini avec succès.";
//         header('Location: index.php');
//         exit;
//     }
// }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Définir votre mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/IFAPME/gitIFAHoraire/IFAHoraire/Public/css/calendar.css?v=<?= time(); ?>">
</head>
<body class="body_set_password">
    <div class="set_passwordContainer">
    <h1>Définissez votre mot de passe</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" id="new_password" name="new_password" required placeholder="mot de passe">

        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required placeholder="confirmation du mot de passe">

        <?php if ($role === 'utilisateur'): ?>
            <div class="select-classe-group">
            <label for="classe">Sélectionnez votre classe :</label>
            <select id="classe" name="classe" required>
                <option value="1">Classe A</option>
                <option value="2">Classe B</option>
            </select>
            </div>
        <?php endif; ?>

        <button type="submit">Valider</button>
    </form>
    </div>
</body>
</html>
