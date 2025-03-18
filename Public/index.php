<?php 
include 'header.php';
require '../src/Date/data.php';
require '../src/Date/Month.php';

$month = new App\Date\Month($_GET['month'] ?? null, $_GET['year'] ?? null);
$start = $month->getStartingDay();
$start = $start->format('N') === '1' ? $start : $month->getStartingDay()->modify("last monday");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/IFAPME/gitIFAHoraire/IFAHoraire/Public/css/calendar.css?v=<?= time(); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier IFAPME</title>
</head>
<body>


<div class="calendrierContainer">
<!-- // affichage du mois -->
<div class="d-flex flew-row align-items-center justify-content-start mx-sm-3">
  <div class="monthSelector">
  <a href="index.php?month=<?= $month->previousMonth()->month; ?>&year=<?= $month->previousMonth()->year; ?>" class="btn btn-primary">&lt</a>
  <h1><?= $month->toString(); ?></h1>
  <a href="index.php?month=<?= $month->nextMonth()->month; ?>&year=<?= $month->nextMonth()->year; ?>" class="btn btn-primary">&gt</a>
  </div>
</div>

<!-- // affichage du calendrier -->
<table class="calendar__table calendar__table--<?= $month->getWeeks(); ?>weeks">
<?php
$weeksDisplayed = 0;
for ($i = 0; $i < $month->getWeeks(); $i++): ?>
    <?php
    $weekDates = [];
    for ($k = 0; $k < 7; $k++) {
        $weekDates[] = (clone $start)->modify("+" . ($k + $i * 7) . " days");
    }
    
    // Vérifier si la semaine doit être affichée
    if (!$month->shouldDisplayWeek($weekDates, $weeksDisplayed)) {
        continue; // On saute cette semaine si elle ne doit pas être affichée
    }

    $weeksDisplayed++;
    ?>
    
    <tr>
        <?php foreach ($month->days as $k => $day): 
            $date = (clone $start)->modify("+" . ($k + $i * 7) . " days");
            $formattedDate = $date->format('Y-m-d');
            $class = isset($schedule[$formattedDate]) ? 'has-course' : ''; // Ajout de la classe pour les cours
        ?>
        
        <td class="calendar__day <?php echo !$month->withinMonth($date) ? 'calendar__othermonth' : ''; ?> <?php echo in_array($date->format('N'), [6, 7]) ? 'weekend' : ''; ?>">
    <div class="calendar__day-content">
<!-- à l'intérieur des cases  -->
        <span><?php echo $date->format('d'); ?></span>
        <?php if (!empty($schedule[$formattedDate]['blocks'])): ?>
            <?php foreach ($schedule[$formattedDate]['blocks'] as $block): ?>
                <div class="course-time">
                    <!-- Heure début - fin -->
                    <strong> <?= $block['start_time'] . ' - ' . $block['end_time'] ?></strong><br>
                    </div>
                    <div class="course-info">
                    <!-- Nom du cours, local et prof -->
                    <strong>
                        <?= $block['course'] ?> - 
                        </strong><?= $block['location'] ?><br>
                        <?= $block['professeur'] ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</td>


        <?php endforeach; ?>
    </tr>
<?php endfor; ?>
                  
</table>
</div>

</body>
</html>