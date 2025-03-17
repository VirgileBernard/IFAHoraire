<?php
// Chemin vers le fichier texte
$file_path = 'C:/wamp64/www/IFAPME/gitIFAHoraire/IFAHoraire/src/Date/horaire_classe A.txt';

// Lire le contenu du fichier
$content = file_get_contents($file_path);

// Séparer les lignes du fichier
$lines = explode("\n", $content);

// Initialiser la structure de données
$schedule = [];

// Liste des professeurs associés aux codes
$professeurs = [
    'LBA' => 'GERARD Cédric',
    'LDB' => 'LAMBERT Gauthier',
    'LPB' => 'GERARD Cédric',
    'MDB' => 'LAMBERT Gauthier',
    'WKS' => 'ROUSSEAU Nathan',
    'ADB' => 'ROUSSEAU Nathan',
    'ANG' => 'unknown',
];

// Parcourir chaque ligne du fichier
foreach ($lines as $line) {
    $line = trim($line);

    // Vérifier si la ligne correspond à une date (format dd-mm-yy)
    if (preg_match('/^(\d{2}-\d{2}-\d{2})/', $line, $matches)) {
        // Convertir la date au format Y-m-d
        $date = DateTime::createFromFormat('d-m-y', $matches[1])->format('Y-m-d');

        // Initialiser la structure si elle n'existe pas
        if (!isset($schedule[$date])) {
            // blocks contiendra un tableau de blocs (chaque bloc = un cours différent)
            $schedule[$date] = [
                'blocks' => [],
            ];
        }

    // Vérifier si la ligne correspond à un créneau de cours (ex : 08:30 LBA... )
    } elseif (preg_match('/^(\d{2}:\d{2}) (.*) \((.*)\)/', $line, $matches)) {
        $heure = $matches[1];        // ex: 08:30
        $fullCourseName = $matches[2]; // ex: LPB (GAT5)
        $location = $matches[3];    // ex: GAT5

        // Extraire le code cours (ex: 'LPB')
        $code_cours = substr($fullCourseName, 0, 3);

        // --- Nouveau bloc ou bloc existant ? ---
        // Récupérer la liste des blocs existants pour ce $date
        $blocks = &$schedule[$date]['blocks'];

        // Vérifier si on n'a aucun bloc ou si le code a changé
        if (empty($blocks) || (end($blocks)['code'] ?? '') !== $code_cours) {
            // Créer un nouveau bloc
            $blocks[] = [
                'code'       => $code_cours,
                'course'     => $fullCourseName,
                'location'   => $location,
                'professeur' => $professeurs[$code_cours] ?? 'Inconnu',
                'start_time' => $heure,
                'end_time'   => null, // On la précisera plus tard
                'times'      => []    // Les créneaux intermédiaires
            ];
        }

        // Ajouter ce créneau à la fin du bloc en cours
        $lastIndex = count($blocks) - 1;
        $blocks[$lastIndex]['times'][] = $heure;
    }
}

// Ajuster l'heure de fin pour chaque bloc
foreach ($schedule as $date => &$data) {
    if (!empty($data['blocks'])) {
        foreach ($data['blocks'] as $idx => &$block) {
            // end_time = heure du dernier créneau + 50 minutes
            if (!empty($block['times'])) {
                $last_time = end($block['times']);
                $end_time = strtotime($last_time) + 50 * 60;
                $block['end_time'] = date('H:i', $end_time);
            }
        }
    }
}

// Trier les dates (ordre chronologique)
ksort($schedule);

// echo '<pre>'; print_r($schedule); echo '</pre>';


?>

