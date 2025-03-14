<?php
require 'calendrier.php'; // Inclure le fichier contenant les cours

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier Dynamique</title>
    <style>
        .month {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .week {
            display: flex;
            justify-content: space-between;
        }
        .day {
            width: 14%;
            padding: 20px;
            border: 1px solid #000;
            text-align: center;
            background-color: #f2f2f2;
        }
        .has-course {
            background-color: blue;
            color: white;
        }
        .week-labels {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            text-align: center;
        }
        .week-label {
            width: 14%;
        }
    </style>
</head>
<body>
    <h1>Calendrier Dynamique</h1>
    <form method="get" action="">
        <label for="month">Mois :</label>
        <select id="month" name="month">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= isset($_GET['month']) && $_GET['month'] == $m ? 'selected' : '' ?>>
                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                </option>
            <?php endfor; ?>
        </select>
        <label for="year">Année :</label>
        <select id="year" name="year">
            <?php for ($y = date('Y') - 5; $y <= date('Y') + 5; $y++): ?>
                <option value="<?= $y ?>" <?= isset($_GET['year']) && $_GET['year'] == $y ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <input type="submit" value="Afficher">
    </form>
    
    <?php
        $selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
        $selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y');
        echo "<h2>Calendrier de " . date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)) . "</h2>";
        
        $date = new DateTime("$selectedYear-$selectedMonth-01");
        $end = new DateTime("$selectedYear-$selectedMonth-" . $date->format('t'));
        $firstDayOfWeek = (int)$date->format('N'); // 1 (Lundi) à 7 (Dimanche)
        $lastDayOfWeek = (int)$end->format('N');
    ?>
    
    <div class="week-labels">
        <?php
            $daysOfWeek = ['L', 'M', 'M', 'J', 'V', 'S', 'D'];
            foreach ($daysOfWeek as $day) {
                echo "<div class='week-label'>$day</div>";
            }
        ?>
    </div>
    
    <div class="month">
        <?php
            echo "<div class='week'>";
            // Affichage des jours vides avant le 1er du mois
            for ($i = 1; $i < $firstDayOfWeek; $i++) {
                echo "<div class='day' style='background-color:#ddd;'></div>";
            }
            
            // Affichage des jours du mois
            while ($date <= $end) {
                $formattedDate = $date->format('Y-m-d');
                $class = isset($schedule[$formattedDate]) ? 'has-course' : '';
                echo "<div class='day $class'>" . $date->format('d') . "</div>";
                
                // Si on atteint dimanche (7), on passe à la ligne suivante
                if ($date->format('N') == 7) {
                    echo "</div><div class='week'>";
                }
                
                $date->modify('+1 day');
            }
            
            // Complétion de la dernière semaine avec des cases vides
            for ($i = $lastDayOfWeek; $i < 7; $i++) {
                echo "<div class='day' style='background-color:#ddd;'></div>";
            }
            
            echo "</div>";
        ?>
    </div>
</body>
</html>
