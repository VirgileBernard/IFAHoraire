<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/IFAPME/gitIFAHoraire/IFAHoraire/Public/css/calendar.css?v=<?= time(); ?>">
</head>
<body>

<!-- affichage navbar -->
<!-- <nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand">Calendrier IFAPME</a>
    <form class="d-flex" role="search">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>
</nav> -->

<div class="uploaderContainer">
<h2>Uploader votre fichier .txt</h2>

<form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="horaire" accept=".txt" required>
    <button type="submit" name="submit">Envoyer</button>
</form>


<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["horaire"])) {
    $uploadDir = "uploads/";
    $fileName = basename($_FILES["horaire"]["name"]);
    $uploadFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES["horaire"]["tmp_name"], $uploadFile)) {
        echo "<p style='color: green;'>Fichier bien uploadé : " . htmlspecialchars($fileName) . "</p>";
    } else {
        echo "<p style='color: red;'>Erreur lors de l'upload.</p>";
    }
}
?>
</div>

</body>
</html>
