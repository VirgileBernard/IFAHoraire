<?php 

namespace App\Date;

class Month {
    public $month;
    public $year;
    public $days = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];

    public function __construct(?int $month = null, ?int $year = null) {
        if ($month === null || $month < 1 || $month > 12) {
            $month = intval(date('m'));
        }
        if ($year === null) {
            $year = intval(date('Y'));
        }
        $this->month = $month;
        $this->year = $year;
    }

    public function toString(): string {
        return strftime('%B %Y', mktime(0, 0, 0, $this->month, 1, $this->year));
    }

    public function getWeeks(): int {
        $firstDay = new \DateTime("{$this->year}-{$this->month}-01");
        $lastDay = (clone $firstDay)->modify('last day of this month');
        
        $firstMonday = clone $firstDay;
        if ($firstMonday->format('N') !== '1') {
            $firstMonday->modify('last monday');
        }
        
        $lastSunday = clone $lastDay;
        if ($lastSunday->format('N') !== '7') {
            $lastSunday->modify('next sunday');
        }
        
        return ceil(($lastSunday->diff($firstMonday)->days + 1) / 7);
    }

    public function getStartingDay(): \DateTime {
        return new \DateTime("{$this->year}-{$this->month}-01");
    }

    public function withinMonth(\DateTime $date): bool {
        return $date->format('m') == $this->month;
    }
    
    public function previousMonth(): self {
        $month = $this->month - 1;
        $year = $this->year;
        if ($month < 1) {
            $month = 12;
            $year--;
        }
        return new self($month, $year);
    }
    
    public function nextMonth(): self {
        $month = $this->month + 1;
        $year = $this->year;
        if ($month > 12) {
            $month = 1;
            $year++;
        }
        return new self($month, $year);
    }

    // Si une semaine est entièrement hors du mois, elle est supprimée, sauf si le mois commence un lundi
    public function shouldDisplayWeek(array $dates, int $weeksDisplayed): bool {
        $outOfMonthDays = 0;
        $inMonthDays = 0;
        foreach ($dates as $date) {
            if ($this->withinMonth($date)) {
                $inMonthDays++;
            } else {
                $outOfMonthDays++;
            }
        }
        // Si la semaine contient au moins un jour du mois, elle doit être affichée
        if ($inMonthDays > 0) {
            return true;
        }
        
        // Vérifier si le mois commence un lundi et forcer 5 semaines d'affichage
        $firstDayOfMonth = $this->getStartingDay();
        $firstDayIsMonday = $firstDayOfMonth->format('N') === '1';
        
        // On s'assure que le calendrier affiche toujours au moins 5 semaines
        if ($firstDayIsMonday && $weeksDisplayed < 4) {
            return true;
        }
        
        // Si la semaine ne contient que des jours hors du mois ET que 5 semaines sont déjà affichées, on l'exclut
        return $weeksDisplayed < 5;
    }
}
