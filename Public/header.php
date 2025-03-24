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
                    Connecté en tant que : <strong><?= htmlspecialchars($_SESSION['user']) ?></strong>
                </p>
                <?php if (!$_SESSION['is_admin']): ?>
  <a href="edit_profil.php" class="btn btn-outline-primary">Mon Profil</a>
<?php endif; ?>
                <a href="login.php" class="btn btn-danger btn-sm">Déconnexion</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary btn-sm">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
