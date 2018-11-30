<?php
defined('IN_BOOKS') or die('Forbidden access!');

function GetGroupPlans($gp_id)
{
    global $db;
    $planlist = array();
    
    $sql = "select a.`plan_id`, a.`gp_id`, a.`user_id`, a.`cat_id`,"
            . " a.`cost_limit`, b.`name` from plan as a left join category as b on a.cat_id=b.cat_id where a.`gp_id` = $gp_id";
    if($result = $db->query($sql))
    {
        //return $result;
        while( $row = $result->fetch_assoc() )
        {       
            $planlist [] = $row;
        }
    }
    else
    {
        echo $db->error;
        
    }
    
    return $planlist;
}