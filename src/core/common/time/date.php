<?php
namespace core\common\time;
use core\common\exception;

/**
 * Static Date Library.
 *
 * This act as the backbone of the Object Date Library and can be used directly.
 *
 * @Version 1.0
 * @Author  mick@blackoutzz.me
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

abstract class date
{

    // Datetime standard displays
    const atom = "Y-m-d\TH:i:sP";
    const cookie = "l, d-M-Y H:i:s T";
    const iso8601 = "Y-m-d\TH:i:sO";
    const rfc822 = "D, d M y H:i:s O";
    const rfc850 = "l, d-M-y H:i:s T";
    const rfc1036 = "D, d M y H:i:s O";
    const rfc1123 = "D, d M Y H:i:s O";
    const rfc2822 = "D, d M Y H:i:s O";
    const rfc3339 = "Y-m-d\TH:i:sP";
    const rss = "D, d M Y H:i:s O";
    const w3c = "Y-m-d\TH:i:sP";
    const mysql = "Y-m-d H:i:s";
    const today = "H:i:s";

    // Days of week
    const sunday = 0;
    const monday = 1;
    const tuesday = 2;
    const wednesday = 3;
    const thursday = 4;
    const friday = 5;
    const saturday = 6;

    // Months of year
    const january = 1;
    const febuary = 2;
    const march = 3;
    const april = 4;
    const may = 5;
    const june = 6;
    const july = 7;
    const august = 8;
    const september = 9;
    const october = 10;
    const november = 11;
    const december = 12;

    // Time Conversion Units
    const years_per_century = 100;
    const decades_per_century = 10;
    const years_per_decade = 10;
    const months_per_year = 12;
    const weeks_per_year = 52;
    const weeks_per_month = 4;
    const days_per_week = 7;
    const hours_per_day = 24;
    const minutes_per_day = 1440;
    const minutes_per_hour = 60;
    const seconds_per_day = 86400;
    const seconds_per_hour = 3600;
    const seconds_per_minute = 60;

    static public function is_today($ptime)
    {

    }

    static public function is_tomorrow($ptime)
    {

    }

    static public function is_older_then($pold_time,$pnew_time = "now")
    {

        if($pnew_time === "now"){
            $new_time = time();
        } else {
            if($pnew_time instanceof date){
                $new_time = $pnew_time->get_timestamp();
            } else {
                $new_time = sdate::parse_time($pnew_time);
            }
        }
        if($pold_time instanceof date){
            $old_time = $pold_time->get_timestamp();
        } else {
            $old_time = sdate::parse_time($pold_time);
        }

        if(isset($old_time) && isset($new_time)){
            $old_time = date_parse(date(self::mysql,$old_time));
            $new_time = date_parse(date(self::mysql,$new_time));

            if($new_time["year"] > $old_time["year"]) return true;
            if($new_time["year"] < $old_time["year"]) return false;
            if($new_time["year"] == $old_time["year"]){
                if($new_time["month"] > $old_time["month"]) return true;
                if($new_time["month"] < $old_time["month"]) return false;
                if($new_time["month"] == $old_time["month"]){
                    if($new_time["day"] > $old_time["day"]) return true;
                    if($new_time["day"] < $old_time["day"]) return false;
                    if($new_time["day"] == $old_time["day"]){
                        if($new_time["hour"] > $old_time["hour"]) return true;
                        if($new_time["hour"] < $old_time["hour"]) return false;
                        if($new_time["hour"] == $old_time["hour"]){
                            if($new_time["minute"] > $old_time["minute"]) return true;
                            if($new_time["minute"] < $old_time["minute"]) return false;
                            if($new_time["minute"] == $old_time["minute"]){
                                if($new_time["second"] > $old_time["second"]) return true;
                                if($new_time["second"] < $old_time["second"]) return false;
                                if($new_time["second"] == $old_time["second"]){
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;

    }

    static public function parse_time($ptime)
    {
        if($ptime === false) return time();
        if(is_int($ptime)) return $ptime;
        if(strtotime($ptime)) return strtotime($ptime);
        $parsed_date = date_parse($ptime);
        return mktime($parsed_date["hour"],$parsed_date["minute"],$parsed_date["second"],$parsed_date["month"],$parsed_date["day"],$parsed_date["year"]);
    }

    static public function yesterday($ptime = false,$pdisplay = mysql)
    {

        if($ptime === false) $ptime = time();
        return date($pdisplay,mktime(
                intval(date("H",$ptime)),
                intval(date("i",$ptime)),
                intval(date("s",$ptime)),
                intval(date("n",$ptime)),
                intval(date("j",$ptime)-1),
                intval(date("Y",$ptime))
            )
        );
    }

    static public function is_yesterday($ptime = false)
    {
        if($ptime === false) $ptime = time();
        return strtotime("yesterday");

    }

    static public function get_elapsed_time($ptime = false){
        try{
            if($ptime === false) $ptime = time();
            $seconds = intval(time() - $ptime);
            if($seconds === 0) return "now";
            if($seconds > self::seconds_per_minute){
                $minutes = round($seconds / self::seconds_per_minute,0,PHP_ROUND_HALF_DOWN);
                if($minutes > self::minutes_per_hour){
                    $hours = round($minutes / self::minutes_per_hour,0,PHP_ROUND_HALF_DOWN);
                    if($hours > self::hours_per_day){
                        $days = round($hours / self::hours_per_day,0,PHP_ROUND_HALF_DOWN);
                        if($days > self::days_per_week){
                            $weeks = round($days / self::days_per_week,0,PHP_ROUND_HALF_DOWN);
                            if($weeks > self::weeks_per_month){
                                $months = round($weeks / self::weeks_per_month,0,PHP_ROUND_HALF_DOWN);
                                if($months > self::months_per_year){
                                    $years = round($months / self::months_per_year,0,PHP_ROUND_HALF_DOWN);
                                    if($years > self::years_per_decade){
                                        $decades = round($years / self::years_per_decade,0,PHP_ROUND_HALF_DOWN);
                                        if($decades > decades_per_century){
                                            $centuries = round($decades / self::decades_per_century,0,PHP_ROUND_HALF_DOWN);
                                            if($centuries == 1) return "last century";
                                            if($centuries >   1) return "{$centuries} centuries ago";
                                        } else {
                                            if($decades == 1) return "last decade";
                                            if($decades ==  self::decades_per_century) return "last century";
                                            if($decades <   self::decades_per_century) return "{$decades} decades ago";
                                        }
                                    } else {
                                        if($years == 1) return "last year";
                                        if($years ==  self::years_per_decade) return "last decade";
                                        if($years <   self::years_per_decade) return "{$years} years ago";
                                    }
                                } else {
                                    if($months == 1) return "last month";
                                    if($months ==  self::months_per_year) return "last year";
                                    if($months <   self::months_per_year) return "{$months} months ago";
                                }
                            } else {
                                if($weeks == 1) return "last week";
                                if($weeks ==  self::weeks_per_month) return "last month";
                                if($weeks <   self::weeks_per_month) return "{$weeks} weeks ago";
                            }
                        } else {
                            if($days == 1) return "yesterday";
                            if($days ==  self::days_per_week) return "last week";
                            if($days <   self::days_per_week) return "{$days} days ago";
                        }
                    } else {
                        if($hours == 1) return "last hour ago";
                        if($hours ==  self::hours_per_day) return "yesterday";
                        if($hours <   self::hours_per_day) return "{$hours} hours ago";
                    }
                } else {
                    if($minutes == 1) return "last minute ago";
                    if($minutes ==  self::minutes_per_hour) return "last hour ago";
                    if($minutes <   self::minutes_per_hour) return "{$minutes} minutes ago";
                }
            } else {
                if($seconds == 1 || $seconds < 10) return "now";
                if($seconds ==  self::seconds_per_minute) return "last minute ago";
                if($seconds <   self::seconds_per_minute) return "{$seconds} seconds ago";
            }
        }
        catch (exception $e){
            return false;
        }
    }

}
