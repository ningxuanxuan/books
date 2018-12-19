<?php
defined('IN_BOOKS') or die('Forbidden access!');

require_once 'lib_category.php';
require_once 'libstat.php';

function GetGroupPlans($gp_id)
{
    global $db;
    $planlist = array();
    
    $start = GetBeginOfMonth(time());
    $end   = GetBeginOfNextMonth(time());
    
    $sql = "SELECT
    a.cat_id,
    a.gp_id,
    b.name,
    SUM(a.`sum`) AS total,
    SUM(c.cost_limit) AS cost_limit
FROM
    stream AS a
LEFT JOIN plan AS c
ON
    a.cat_id = c.cat_id
LEFT JOIN category AS b
ON
    a.cat_id = b.cat_id
WHERE
    a.gp_id = $gp_id and a.is_delete = 0 and a.date >= $start and a.date <= $end
GROUP BY
    a.cat_id, c.cat_id
UNION
SELECT
    a.cat_id,
    c.gp_id,
    b.name,
    a.total,
    c.cost_limit
FROM
    plan AS c
LEFT JOIN(
    SELECT
        cat_id,
        SUM(`sum`) AS total
    FROM
        stream
    WHERE
        is_delete = 0 and date >= $start and date <= $end and gp_id = $gp_id
    GROUP BY
        cat_id
) AS a
ON
    a.cat_id = c.cat_id
LEFT JOIN category AS b
ON
    c.cat_id = b.cat_id
WHERE
    c.gp_id = 1" ;
    
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

function GetSinglePlanSum($gp_id, $cat_id)
{
    global $db;
    $gp_id = intval($gp_id);
    $cat_id = intval($cat_id);
    $sum = 0;
    $sql = "select cost_limit from plan where gp_id = $gp_id and cat_id = $cat_id and is_delete = 0";
    
    if ($result = $db->query($sql))
    {
        if($result->num_rows > 0)
        {
            $sum = intval($result->fetch_assoc()['cost_limit'], 10);
        }
    }
    
    return $sum;
    
}
function UpdateSinglePlan($gp_id, $cat_id, $user_id, $total)
{
    global $db;
    $gp_id = intval($gp_id);
    $cat_id = intval($cat_id);
    $total = doubleval($total);
    $user_id = intval($user_id);
    
    if(!CheckCatPrivilege($cat_id, $gp_id))
    {
        return false;
    }
    
    $plan_id = get_plan_id($gp_id, $cat_id);
    
    if ($plan_id === -1)
    {
        $sql = "INSERT INTO `plan` (`plan_id`, `gp_id`, `user_id`, `cat_id`, `cost_limit`," .
               " `is_delete`) VALUES (NULL, $gp_id, $user_id, $cat_id, $total, 0)";
    }
    else 
    {
        $sql = "UPDATE  plan set cost_limit = $total where plan_id = $plan_id";
    }
    
    return $db->query($sql);
}
/**
 * 
 * @param int $gp_id
 * @param int $cat_id
 * @return int return the plan id when success, -1 when not found
 */
function get_plan_id($gp_id, $cat_id)
{
    global $db;
    $id = -1;
    $cat_id = intval($cat_id);
    $sql = "select plan_id from plan where gp_id = $gp_id and cat_id = $cat_id and is_delete = 0";
    
    if($result = $db->query($sql))
    {
        if($result->num_rows > 0)
        {
            $id = intval($result->fetch_assoc()['plan_id'], 10);
        }
    }
    
    return $id;
}