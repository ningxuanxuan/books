<?php 

defined('IN_BOOKS') or die();

function GetCashStream($start_time, $end_time, $group, $startpos = 0, $limit = 100)
{
    global $db;
    
    $sql = "select a.in_id as id, a.date, a.sum, a.intro, a.type, b.name as cat_name, c.username from " .
    " stream as a left join category as b on a.cat_id = b.cat_id " .
    "left join user as c on c.user_id = a.record_person_id  " .
    "where a.date >= $start_time and a.date <= $end_time and a.gp_id = $group " .
    "and a.is_delete=0 limit $startpos, $limit";
    
    $sql_count = "select count(*) from stream as a left join category as b on a.cat_id = b.cat_id " .
        "left join user as c on c.user_id = a.record_person_id  " .
        "where a.date >= $start_time and a.date <= $end_time and a.gp_id = $group " .
        "and a.is_delete=0";
    
    $result = array('stream' => array());
    
    if( $qresult = $db->query($sql_count) )
    {
        if( $row =  $qresult->fetch_row() )
        {
            $result['count'] = $row[0];
        }
        else 
        {
            $result['count'] = 0;
        }
    }
    
    if( $qresult = $db->query($sql) )
    {
        while( $row = $qresult->fetch_assoc() )
        {
            $row['date'] = date("Y-m-d", $row['date']);
            $result ['stream'][] = $row;
        }
    }
    else
    {
        echo $db->error;
    }
    
    return $result;
    
}