<?php
// Éviter d'appeler session_start() si une session est déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

if (!isset($_SESSION['email'])) {
    die("Utilisateur non connecté !");
}

// Récupérer l'email de l'utilisateur connecté
$email = $_SESSION['email'];

// Récupérer la classe de l'utilisateur
$stmt = $pdo->prepare("
    SELECT c.nom_classe 
    FROM utilisateur u
    JOIN classe c ON u.classe_id = c.id
    WHERE u.email = :email
");
$stmt->execute(['email' => $email]);
$classe = $stmt->fetchColumn();

// Vérifier si une classe a été trouvée
if (!$classe) {
    die("Classe non définie pour cet utilisateur !");
}

// Déterminer le fichier en fonction de la classe
$files = [
    'A' => 'uploads/horaire_classe A.txt',
    'B' => 'uploads/X75_1B_horaire_classe_20241105.txt'
];

if (!isset($files[$classe])) {
    die("Fichier d'horaire introuvable pour la classe $classe !");
}

// Chemin vers le fichier de l'horaire correspondant
$file_path = $files[$classe];

// Vérifier si le fichier existe
if (!file_exists($file_path)) {
    die("Le fichier d'horaire pour la classe $classe est manquant !");
}

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
        $date = DateTime::createFromFormat('d-m-y', $matches[1])->format('Y-m-d');

        if (!isset($schedule[$date])) {
            $schedule[$date] = ['blocks' => []];
        }
    }
    // Vérifier si la ligne correspond à un créneau de cours
    elseif (preg_match('/^(\d{2}:\d{2}) (.*) \((.*)\)/', $line, $matches)) {
        $heure = $matches[1];
        $fullCourseName = $matches[2];
        $location = $matches[3];
        $code_cours = substr($fullCourseName, 0, 3);

        $blocks = &$schedule[$date]['blocks'];

        if (empty($blocks) || (end($blocks)['code'] ?? '') !== $code_cours) {
            $blocks[] = [
                'code' => $code_cours,
                'course' => $fullCourseName,
                'location' => $location,
                'professeur' => $professeurs[$code_cours] ?? 'Inconnu',
                'start_time' => $heure,
                'end_time' => null,
                'times' => []
            ];
        }

        $lastIndex = count($blocks) - 1;
        $blocks[$lastIndex]['times'][] = $heure;
    }
}

// Ajuster l'heure de fin pour chaque bloc
foreach ($schedule as $date => &$data) {
    if (!empty($data['blocks'])) {
        foreach ($data['blocks'] as &$block) {
            if (!empty($block['times'])) {
                $last_time = end($block['times']);
                $end_time = strtotime($last_time) + 50 * 60;
                $block['end_time'] = date('H:i', $end_time);
            }
        }
    }
}

// Trier les dates
ksort($schedule);

?>
