<?php
defined('IN_BOOKS') or die("forbidden access!");

function GetBeginOfMonth($timestamp)
{
    $timestr = date("Y-m", $timestamp);
    
    $times = explode('-', $timestr);
    
    if( count($times) != 2 )
    {
        return 0;
    }
    
    $year = intval($times[0]);
    
    $month = intval($times[1]);
    
    return strtotime($year . '-' . $month .'-01');
}

function GetEndOfMonth($timestamp)
{
    return GetBeginOfNextMonth($timestamp);
}

function GetBeginOfNextMonth($timestamp)
{
    $timestr = date("Y-m", $timestamp);
    
    $times = explode('-', $timestr);
    
    if( count($times) != 2 )
    {
        return 0;
    }
    
    $year = intval($times[0]);
    
    $month = intval($times[1]) + 1;
    
    if( $month > 12 )
    {
        $month = 1;
        $year ++;
    }
    
    return strtotime($year . '-' . $month .'-01');
}