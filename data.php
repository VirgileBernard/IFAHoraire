<?php
// Chemin vers le fichier texte
$file_path = 'horaire_classe A.txt';

// Lire le contenu du fichier
$content = file_get_contents($file_path);

// Séparer les lignes du fichier
$lines = explode("\n", $content);

// Initialiser les variables


$schedule = []; // ma structure qui stockera les cours 


$professeurs = [
    'LBA' => 'GERARD Cédric',
    'LDB' => 'LAMBERT Gauthier',
    'LPB' => 'GERARD Cédric',
    'MDB' => 'LAMBERT Gauthier',
    'WKS' => 'ROUSSEAU Nathan',
    'ADB' => 'ROUSSEAU Nathan',
    'ANG' => 'unknown',
]; // liste des profs associés aux cours

// Parcourir les lignes pour extraire les informations
foreach ($lines as $line) {
    $line = trim($line); // pour supprimer les espaces en fin de lignes

    // vérifier si la ligne correspond à une date (REGEX)
    if (preg_match('/^(\d{2}-\d{2}-\d{2})/', $line, $matches)) {
        // Nouvelle date trouvée
        $date = DateTime::createFromFormat('d-m-y', $matches[1])->format('Y-m-d');

        // initialiser l'entrée dans le tableau si elle n'existe pas encore
        if (!isset($schedule[$date])) {
            $schedule[$date] = ['times' => []];
        }

        // vérifier si la ligne correspond à un cours
    } elseif (preg_match('/^(\d{2}:\d{2}) (.*) \((.*)\)/', $line, $matches)) {
        // Horaire et cours trouvés
        // extraire le code cours 
        $code_cours = substr($matches[2], 0, 3);

        // add les infos de cours à la date qui correspond
        if (!isset($schedule[$date]['start_time'])) {
            $schedule[$date]['start_time'] = $matches[1]; // h du 1er cours
            $schedule[$date]['course'] = $matches[2]; // nom du cours
            $schedule[$date]['location'] = $matches[3]; // local
            $schedule[$date]['professeur'] = $professeurs[$code_cours];
        }


        // ajouter le détail de chaque créneau 
        $schedule[$date]['times'][] = [
            'time' => $matches[1],
            'course' => $matches[2],
            'location' => $matches[3],
            'professeur' => $professeurs[$code_cours],
        ];
    }
}

// Ajuster l'heure de fin pour chaque date
foreach ($schedule as &$day) {
    if (!empty($day['times'])) {
        $last_time = end($day['times'])['time'];
        $end_time = strtotime($last_time) + 50 * 60; // Ajouter 50 minutes
        $day['end_time'] = date('H:i', $end_time);
    }
}

// Trier les dates
ksort($schedule);

?>

<!-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste complète des cours</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Liste complète des cours</h1>
    <table>
        <tr>
            <th>Date</th>
            <th>Heure de début</th>
            <th>Heure de fin</th>
            <th>Cours</th>
            <th>Local</th>
            <th>Professeur</th>
        </tr>
        <?php foreach ($schedule as $date => $day): ?>
            <?php if (isset($day['start_time']) && isset($day['end_time']) && isset($day['course']) && isset($day['location']) && isset($day['professeur'])): ?>
                <tr>
                    <td><?= DateTime::createFromFormat('Y-m-d', $date)->format('d-m-y') ?></td>
                    <td><?= $day['start_time'] ?></td>
                    <td><?= $day['end_time'] ?></td>
                    <td><?= $day['course'] ?></td>
                    <td><?= $day['location'] ?></td>
                    <td><?= $day['professeur'] ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</body>
</html> -->