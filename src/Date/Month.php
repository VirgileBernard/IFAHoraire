<?php

namespace App\Date;

class Month {
    public $month;
    public $year;
    public $days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];

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
        
        // Déterminer le premier lundi du calendrier
        $firstMonday = clone $firstDay;
        if ($firstMonday->format('N') !== '1') {
            $firstMonday->modify('last monday');
        }
        
        // Déterminer le dernier dimanche du calendrier
        $lastSunday = clone $lastDay;
        if ($lastSunday->format('N') !== '7') {
            $lastSunday->modify('next sunday');
        }
        
        // Vérifier si la première ou la dernière semaine ne contient que des jours hors du mois
        $weeks = ceil(($lastSunday->diff($firstMonday)->days + 1) / 7);
        
        // Simulation des semaines pour suppression des semaines contenant uniquement des jours hors mois
        $date = clone $firstMonday;
        $validWeeks = 0;
        for ($i = 0; $i < $weeks; $i++) {
            $daysOutOfMonth = 0;
            for ($j = 0; $j < 7; $j++) {
                if (!$this->withinMonth($date)) {
                    $daysOutOfMonth++;
                }
                $date->modify('+1 day');
            }
            // Si la semaine entière est hors du mois, elle n'est pas comptée
            if ($daysOutOfMonth !== 7) {
                $validWeeks++;
            }
        }
        return $validWeeks;
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

    // si il y a 7 jours outOfMonth = ne pas afficher
    public function shouldDisplayWeek(array $dates): bool {
        $outOfMonthDays = 0;
        foreach ($dates as $date) {
            if (!$this->withinMonth($date)) {
                $outOfMonthDays++;
            }
        }
        return $outOfMonthDays < 7;
    }
}

