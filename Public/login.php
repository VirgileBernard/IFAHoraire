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
<body>


    <div class="login-container">
        <h1>IFA - HORAIRE</h1>
        <label for="email">votre email :</label>
        <input type="email" id="email" placeholder="email" required>
        <label for="password">votre mot de passe :</label>
        <input type="password" id="password" placeholder="password" required>
        <button>CONNEXION</button>
    </div>
</body>
</html>