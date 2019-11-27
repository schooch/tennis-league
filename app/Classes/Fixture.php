<?php
namespace App\Classes;

class Fixture{
    public $week;
    public $home;
    public $away;
    public $monday;
    public $matchDate;

    public function __construct($week, $home, $away, $monday, $day)
    {
        $this->week = $week;
        $this->home = $home;
        $this->away = $away;
        $this->monday = date_format($monday, "d/m/Y");

        $matchDay = date_create(date_format($monday, "Y-m-d"));
		date_add($matchDay, date_interval_create_from_date_string("$day days"));
        $this->day = date_format($matchDay, "d/m/Y");
    }

    public static function restWeek($monday){
        $instance = new self(' ', ' ', ' ', $monday, 0);
        $instance->day = '';
        return $instance;
    }
}