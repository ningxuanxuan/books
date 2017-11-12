<?php 

defined('IN_BOOKS') or die();

function CheckCashStreamPrivilege($id, $gp_id)
{
    global $db;
    
    $sql = "select count(*) from stream where in_id = ? and gp_id = ? and is_delete=0";
    
    if( !$stmt = $db->prepare($sql) )
    {
        echo "Database Error:" . $db->error;
        exit(0);
    }
    
    if( !$stmt->bind_param('ii', $id, $gp_id) )
    {
        echo "Database Error:" . $stmt->error;
        exit(0);
    }
    
    if( !$stmt->execute() )
    {
        echo "Database Error:" . $stmt->error;
        exit(0);
    }
    
    $result = $stmt->get_result();
    $stmt->close();
    
    if($result && $result->fetch_row()[0] == 1)
    {
        return true;
    }
    
    return false;
}

function GetCashStreamRecord($id)
{
    global $db;
    
    $id = intval($id);
    
    $sql = "select a.in_id as id, a.date, a.sum, a.intro, a.dire_type as type, a.type as pay_method, b.name as cat_name, c.username from " .
        " stream as a left join category as b on a.cat_id = b.cat_id " .
        "left join user as c on c.user_id = a.record_person_id  " .
        "where a.in_id = $id and a.is_delete = 0";
    
    if( $qresult = $db->query($sql) )
    {
        if( $row = $qresult->fetch_assoc() )
        {
            $row['date'] = date("Y-m-d", $row['date']);
            $row['intro'] = filter_htmlspecialchars($row['intro']);
            $result = $row;
        }
    }
    
    return $result;
    
}
function GetCashStream($start_time, $end_time, $group, $startpos = 0, $limit = 100)
{
    global $db;
    
    $sql = "select a.in_id as id, a.date, a.sum, a.intro, a.dire_type as type, b.name as cat_name, c.real_name from " .
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
            $row['intro'] = filter_htmlspecialchars($row['intro']);
            
            $result ['stream'][] = $row;
        }
    }
    else
    {
        echo $db->error;
    }
    
    return $result;
    
}

/**
 * 
 * @param int $date
 * @param int $type
 * @param int $cat_id
 * @param int $sum
 * @param int $pay_method
 * @param string $intro
 * @param int $gp_id
 * @param int $user_id
 * 
 * @return bool
 */
function InsertCashStream($date, $type, $cat_id, $sum, $pay_method, &$intro, $gp_id, $user_id)
{
    $sql = "insert into stream (date, `sum`, intro, record_person_id, cat_id, dire_type, type, gp_id) ".
        "values( ?, ?, ?, ?, ?, ?, ?, ?)";
    
    global $db;
    
    if ( !$stmt = $db->prepare( $sql ) )
    {
        echo "Database Error:" . $db->error;
        exit(0);
    }
    
    if( !$stmt->bind_param('idsiiiii', $date, $sum, $intro, $user_id, $cat_id, $type, $pay_method, $gp_id) )
    {
        echo "Database Error:" . $stmt->error;
        exit(0);
    }
    
    $result = $stmt->execute();
    
    $stmt->close();
    
    return $result;
}

function UpdateCashStream($id, $date, $type, $cat_id, $sum, $pay_method, &$intro, $gp_id)
{
    $sql = "update stream set date = ?, sum = ?, intro = ?, cat_id = ?, dire_type = ?, type = ? ".
        "where in_id=? and gp_id=? and is_delete=0";
    
    global $db;
    
    if ( !$stmt = $db->prepare( $sql ) )
    {
        echo "Database Error:" . $db->error;
        exit(0);
    }
    
    if( !$stmt->bind_param('idsiiiii', $date, $sum, $intro, $cat_id, $type, $pay_method, $id, $gp_id) )
    {
        echo "Database Error:" . $stmt->error;
        exit(0);
    }
    
    $result = $stmt->execute();
    
    $stmt->close();
    
    return $result;
}

function DeleteCashStream($id)
{
    $sql = "update stream set is_delete = 1 where in_id = ? and is_delete=0";
    
    global $db;
    
    if ( !$stmt = $db->prepare( $sql ) )
    {
        echo "Database Error:" . $db->error;
        exit(0);
    }
    
    if( !$stmt->bind_param('i', $id) )
    {
        echo "Database Error:" . $stmt->error;
        exit(0);
    }
    
    $result = $stmt->execute();
    
    $stmt->close();
    
    return $result;
}