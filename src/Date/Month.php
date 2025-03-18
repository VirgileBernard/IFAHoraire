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

}
