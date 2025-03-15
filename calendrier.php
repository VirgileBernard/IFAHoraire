<?php
require 'C:/wamp64/www/IFAPME/gitIFAHoraire/IFAHoraire/src/Date/data.php';

// Obtenir le mois et l'année sélectionnés par l'utilisateur
$selected_year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('m');

// Filtrer les cours pour le mois sélectionné
$filtered_schedule = array_filter($schedule, function($date) use ($selected_year, $selected_month) {
    return strpos($date, "$selected_year-$selected_month") === 0;
}, ARRAY_FILTER_USE_KEY);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Horaire des cours par mois</title>
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
    <h1>Horaire des cours pour le mois de <?= date('F Y', strtotime("$selected_year-$selected_month-01")) ?></h1>
    <form method="get" action="calendrier.php">
        <label for="month">Sélectionner le mois :</label>
        <select id="month" name="month">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $m == $selected_month ? 'selected' : '' ?>>
                    <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                </option>
            <?php endfor; ?>
        </select>
        <label for="year">Sélectionner l'année :</label>
        <select id="year" name="year">
            <?php for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++): ?>
                <option value="<?= $y ?>" <?= $y == $selected_year ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <input type="submit" value="Afficher">
    </form>
    <table>
        <tr>
            <th>Date</th>
            <th>Heure de début</th>
            <th>Heure de fin</th>
            <th>Cours</th>
            <th>Local</th>
            <th>Professeur</th>
        </tr>
           <!-- Parcours des cours filtrés pour le mois sélectionné -->
        <?php foreach ($filtered_schedule as $date => $day): ?>
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
</html>
