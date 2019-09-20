<?php
namespace core\common\components;

/**
 * Object Date.
 *
 * This class contains everything included with the static date library.
 *
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class date
{

    protected $display;

    protected $timestamp;

    public function __construct($ptime = false,$pdisplay = sdate::mysql)
    {
        $this->display = $pdisplay;
        $this->timestamp = sdate::parse_time($ptime);
    }

    public function __toString()
    {
        return date($this->display,$this->timestamp);
    }

    public function get_timestamp()
    {
        return $this->timestamp;
    }

    public function get_date()
    {
        return date($this->display,$this->timestamp);
    }

    public function get_display_format()
    {
        return $this->display;
    }

    public function is_older_then($pnew_time)
    {
        return sdate::is_older_then($this,$pnew_time);
    }

    public function is_newer_then($pnew_time)
    {
        if(!sdate::is_older_then($this,$pnew_time)) return true;
        return false;
    }

    public function get_today_timestamp()
    {
        return strtotime("00:00:00");
    }

    public function get_today_date()
    {
        return date($this->display,$this->get_today_timestamp());
    }

    public function get_today()
    {
        return new date($this->get_today_date());
    }

    public function add_hours($phours)
    {
        $this->timestamp += $phours * 3600;
        return $this;
    }

    public function is_today()
    {
        if($this->get_day() == date("j") && $this->get_month() == date("n") && $this->get_year() == date("Y")) return true;
        return false;
    }

    public function get_tomorrow_timestamp()
    {
        $today = strtotime("00:00:00");
        return strtotime("+1 day",$today);
    }

    public function get_tomorrow_date()
    {
        $today = $this->get_today_timestamp();
        return date($this->display,strtotime("+1 day",$today));
    }

    public function get_tomorrow()
    {
        return new date($this->get_tomorrow_date());
    }

    public function is_tomorrow()
    {
        return strtotime("00:00:00");
    }

    public function is_this_month()
    {
        if($this->get_month() == date("n") && $this->get_year() == date("Y")) return true;
        return false;
    }

    public function is_this_year()
    {
        if($this->get_year() == date("Y")) return true;
        return false;
    }

    public function was_last_month()
    {

    }

    public function get_yesterday_timestamp()
    {
        $today = strtotime("00:00:00");
        return strtotime("-1 day",$today);
    }

    public function get_yesterday_date()
    {
        $today = $this->get_today_timestamp();
        return date($this->display,strtotime("-1 day",$today));
    }

    public function get_yesterday()
    {
        return new date($this->get_yesterday_date());
    }

    public function is_yesterday()
    {
        $yesterday = $this->get_yesterday_date();
        if(date_parse($yesterday)["day"] == $this->get_day()
        && date_parse($yesterday)["month"] == $this->get_month()
        && date_parse($yesterday)["year"] == $this->get_year()) return true;
        return false;
    }

    public function get_day()
    {
        return date_parse(date($this->display,$this->timestamp))["day"];
    }

    public function get_year()
    {
        return date_parse(date($this->display,$this->timestamp))["year"];
    }

    public function get_month()
    {
        return date_parse(date($this->display,$this->timestamp))["month"];
    }

    public function get_hour()
    {
        return date_parse(date($this->display,$this->timestamp))["hour"];
    }

    public function get_minute()
    {
        return date_parse(date($this->display,$this->timestamp))["minute"];
    }

    public function get_second()
    {
        return date_parse(date($this->display,$this->timestamp))["second"];
    }

    public function get_elasped_time()
    {
        return sdate::get_elapsed_time($this->timestamp);
    }

    public function is_weekend()
    {
        $dayoftheweek = date("N",$this->timestamp);
        if($dayoftheweek == "6" || $dayoftheweek == "7") return true;
        return false;
    }

    public function is_week()
    {
        $dayoftheweek = date("N",$this->timestamp);
        if($dayoftheweek != "6" && $dayoftheweek != "7") return true;
        return false;
    }

}
