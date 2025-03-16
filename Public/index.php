<?php 
require '../src/Date/data.php';
require '../src/Date/Month.php';

$month = new App\Date\Month ($_GET['month'] ?? null, $_GET['year'] ?? null); 
$start = $month->getStartingDay()->modify("last monday");
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

<!-- affichage navbar -->
<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand">Calendrier IFAPME</a>
    <form class="d-flex" role="search">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>
</nav>


<div class="calendrierContainer">
<!-- // affichage du mois -->
<div class="d-flex flew-row align-items-center justify-content-start mx-sm-3">
  <div class="monthSelector">
  <a href="index.php?month=<?= $month->previousMonth()->month; ?>&year=<?= $month->previousMonth()->year; ?>" class="btn btn-primary">&lt</a>
  <h1><?php echo $month->toString(); ?></h1>
  <div>
<a href="index.php?month=<?= $month->nextMonth()->month; ?>&year=<?= $month->nextMonth()->year; ?>" class="btn btn-primary">&gt</a>
  </div>
  </div>
</div>


<!-- // affichage du calendrier -->
<table class="calendar__table calendar__table--<?= $month->getWeeks(); ?>weeks">
<?php for ($i=0; $i < $month->getWeeks(); $i++): ?>
  <!-- // afficher les jours au dessus du calendrier -->
<tr class="calendar-header">
  <?php foreach($month->days as $day): ?>
    <!-- // seulement pr la 1er semaine -->
      <?php if ($i === 0): ?>
    <th class="calendar-weekday"><?php echo $day; ?></th>
    <?php endif; ?>
  <?php endforeach; ?>
</tr>
  <tr>
    <?php foreach($month->days as $k => $day): 
      $date = (clone $start)->modify("+" . ($k + $i * 7) . "days");
      $formattedDate = $date->format('Y-m-d');
      $class = isset($schedule[$formattedDate]) ? 'has-course' : ''; // Ajout de la classe bleue
    ?>



<td class="calendar__day <?php echo !$month->withinMonth($date) ? 'calendar__othermonth' : ''; ?> <?php echo in_array($date->format('N'), [6, 7]) ? 'weekend' : ''; ?>">
    <div class="calendar__day-content">
        <span><?php echo $date->format('d'); ?></span>
        <?php if (isset($schedule[$formattedDate])): ?>
            <div class="course-info">
                <?php echo ($schedule[$formattedDate]['start_time'] ?? '') . ' - ' . ($schedule[$formattedDate]['end_time'] ?? ''); ?><br>
                <strong><?php echo $schedule[$formattedDate]['course'] ?? ''; ?> -
                <?php echo $schedule[$formattedDate]['location'] ?? ''; ?><br>
                <?php echo $schedule[$formattedDate]['professeur'] ?? ''; ?></strong>
            </div>
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