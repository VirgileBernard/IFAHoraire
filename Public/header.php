<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">IFA - HORAIRE</a>
        <div class="d-flex">
            <?php if (isset($_SESSION['user'])): ?>
                <p class="m-2 text-success">
                <p class="m-2 text-success">
    Connecté en tant que : <strong><?= htmlspecialchars($_SESSION['prenom']) ?></strong>
</p>
                </p>
                <?php if (!$_SESSION['is_admin']): ?>
                    <!-- Bouton pour les élèves -->
                    <a href="edit_profil.php" class="btn btn-outline-primary">Mon Profil</a>
                <?php else: ?>
                    <!-- Sélecteur pour les admins -->
                    <form method="POST" action="index.php" class="d-flex align-items-center">
                        <select name="classe" class="form-select me-2">
                            <option value="1" <?= (isset($_SESSION['classe']) && $_SESSION['classe'] == '1') ? 'selected' : '' ?>>Classe A</option>
                            <option value="2" <?= (isset($_SESSION['classe']) && $_SESSION['classe'] == '2') ? 'selected' : '' ?>>Classe B</option>
                        </select>
                        <button type="submit" class="btn btn-outline-success">Valider</button>
                    </form>
                <?php endif; ?>
                <a href="login.php" class="btn btn-danger btn-sm ms-2">Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary btn-sm">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</nav>