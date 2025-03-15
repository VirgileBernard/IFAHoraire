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
    <link rel="stylesheet" href="/IFAPME/gitIFAHoraire/IFAHoraire/Public/css/calendar.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier IFAPME</title>
</head>
<body>
<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand">Calendrier IFAPME</a>
    <form class="d-flex" role="search">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>
</nav>

<div class="d-flex flew-row align-items-center justify-content-start mx-sm-3">
  <h1><?php echo $month->toString(); ?></h1>
  <div>
  <a href="index.php?month=<?= $month->previousMonth()->month; ?>&year=<?= $month->previousMonth()->year; ?>" class="btn btn-primary">&lt</a>
<a href="index.php?month=<?= $month->nextMonth()->month; ?>&year=<?= $month->nextMonth()->year; ?>" class="btn btn-primary">&gt</a>
  </div>
</div>

<table class="calendar__table calendar__table--<?= $month->getWeeks(); ?>weeks">
<?php for ($i=0; $i < $month->getWeeks(); $i++): ?>
  <tr>
    <?php foreach($month->days as $k => $day): 
      $date = (clone $start)->modify("+" . ($k + $i * 7) . "days");
      $formattedDate = $date->format('Y-m-d');
      $class = isset($schedule[$formattedDate]) ? 'has-course' : ''; // Ajout de la classe bleue
    ?>
    <td class="<?= $month->withinMonth($date) ? $class : 'calendar__othermonth'; ?>">
      <div class="calendar__day">
        <?php if ($i === 0): ?>
          <div class="calendar-weekday"><?php echo $day; ?></div>
        <?php endif; ?>
        <?php echo $date->format('d'); ?>
        <?php if (isset($schedule[$formattedDate])): ?>
          <div class="course-info">
            <strong><?php echo $schedule[$formattedDate]['course'] ?? ''; ?></strong><br>
            <?php echo ($schedule[$formattedDate]['start_time'] ?? '') . ' - ' . ($schedule[$formattedDate]['end_time'] ?? ''); ?><br>
            <?php echo $schedule[$formattedDate]['location'] ?? ''; ?><br>
            <?php echo $schedule[$formattedDate]['professeur'] ?? ''; ?>
          </div>
        <?php endif; ?>
      </div>
    </td>
    <?php endforeach; ?>
  </tr>
<?php endfor; ?>
</table>

</body>
</html>