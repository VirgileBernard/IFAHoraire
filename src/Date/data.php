<?php
// Démarrer la session seulement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php'; // Connexion PDO vers la base horaireifapme

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['email'])) {
    die("Utilisateur non connecté !");
}

$is_admin = $_SESSION['is_admin'] ?? false;
$email    = $_SESSION['email'];

// Pour stocker le planning parsé
$schedule = [];

// CAS 1 : Élève => on récupère la classe en base depuis la table `utilisateurs`
if ($is_admin === 1) {
    $stmt = $pdo->prepare("SELECT classe_id FROM utilisateurs WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $classe = $stmt->fetchColumn();

    if (!$classe) {
        die("Classe non définie pour cet élève !");
    }

// CAS 2 : Admin => la classe n'est pas en base, on la suppose stockée en $_SESSION['classe']
} else {
    // S’il n’a pas de classe en session, on arrête (ou on laisse `$schedule` vide)
    $classe = $_SESSION['classe'] ?? null;
    if (!$classe) {
        // On arrête ici, $schedule reste vide, index.php l’affichera comme “Aucune classe”
        return;
    }
}

// À ce stade, `$classe` vaut '1' ou '2'
$files = [
    '1' => __DIR__ . '/../../public/uploads/horaire_classe A.txt',
    '2' => __DIR__ . '/../../public/uploads/X75_1B_horaire_classe_20241105.txt'
];

if (!isset($files[$classe])) {
    die("Fichier d'horaire introuvable pour la classe '$classe' !");
}

$file_path = $files[$classe];
if (!file_exists($file_path)) {
    die("Le fichier d'horaire pour la classe '$classe' est manquant !");
}

// Lecture du fichier .txt
$content = file_get_contents($file_path);
$lines   = explode("\n", $content);

// Table de correspondance profs
$professeurs = [
    'LBA' => 'GERARD Cédric',
    'LDB' => 'LAMBERT Gauthier',
    'LPB' => 'GERARD Cédric',
    'MDB' => 'LAMBERT Gauthier',
    'WKS' => 'ROUSSEAU Nathan',
    'ADB' => 'ROUSSEAU Nathan',
    'ANG' => 'unknown',
];

// Parcourir le fichier pour remplir $schedule
foreach ($lines as $line) {
    $line = trim($line);

    // Exemple : 18-04-24
    if (preg_match('/^(\d{2}-\d{2}-\d{2})/', $line, $matches)) {
        $date = DateTime::createFromFormat('d-m-y', $matches[1])->format('Y-m-d');
        if (!isset($schedule[$date])) {
            $schedule[$date] = ['blocks' => []];
        }

    // Exemple : 08:30 LPB (GAT5)
    } elseif (preg_match('/^(\d{2}:\d{2}) (.*?) \((.*?)\)/', $line, $matches)) {
        $heure      = $matches[1];
        $fullCourse = $matches[2];
        $location   = $matches[3];
        $code_cours = substr($fullCourse, 0, 3);

        $blocks = &$schedule[$date]['blocks'];

        // Nouveau bloc si code cours change
        if (empty($blocks) || (end($blocks)['code'] ?? '') !== $code_cours) {
            $blocks[] = [
                'code'       => $code_cours,
                'course'     => $fullCourse,
                'location'   => $location,
                'professeur' => $professeurs[$code_cours] ?? 'Inconnu',
                'start_time' => $heure,
                'end_time'   => null,
                'times'      => []
            ];
        }

        $lastIndex = count($blocks) - 1;
        $blocks[$lastIndex]['times'][] = $heure;
    }
}

// Calcul de l'heure de fin : dernier créneau + 50 min
foreach ($schedule as &$day) {
    foreach ($day['blocks'] as &$block) {
        if (!empty($block['times'])) {
            $last_time = end($block['times']);
            $end_time  = strtotime($last_time) + 50 * 60;
            $block['end_time'] = date('H:i', $end_time);
        }
    }
}

ksort($schedule);
// On laisse $schedule dispo pour index.php
