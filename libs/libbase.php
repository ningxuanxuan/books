<?php

function addslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}

function real_ip()
{
    $realip = "";
    if (isset($_SERVER))
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            
            foreach ($arr AS $ip)
            {
                $ip = trim($ip);
                
                if ($ip != 'unknown')
                {
                    $realip = $ip;
                    
                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if (isset($_SERVER['REMOTE_ADDR']))
            {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $realip = '0.0.0.0';
            }
        }
    }
    else
    {
        if (getenv('HTTP_X_FORWARDED_FOR'))
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_CLIENT_IP'))
        {
            $realip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $realip = getenv('REMOTE_ADDR');
        }
    }
    
    preg_match("/[\d\.]{7,15}|[\w\:]{3,39}/", $realip, $onlineip); // add for support ipv6
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    
    return $realip;
}

function reg_common_smarty_vars($smarty)
{
    if(!empty($_SESSION['real_name']))
    {
        $smarty->assign('real_name', $_SESSION['real_name']);
        $smarty->assign('last_date', $_SESSION['last_date']);
        $smarty->assign('last_ip', $_SESSION['last_ip']);
    }
    
    global $start_time;
    $smarty->assign('script_start_time', $start_time);
}

function filter_htmlspecialchars($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        if (!is_array($value))
        {
            return htmlspecialchars($value, ENT_COMPAT, "gb2312");
        }
        else
        {
            return array_map('filter_htmlspecialchars', $value);
        }
    }
}