<?php

defined('IN_BOOKS') or die("forbidden access!");
define("CATEGORY_TYPE_PAY", 0);
define("CATEGORY_TYPE_EARN", 1);
define("CATEGORY_TYPE_ALL", 2);

function CheckCatPrivilege($cat_id, $gp_id)
{
    if( $cat_id == 0 ) //root cat
    {
        return true;
    }
    
    global $db;
    $sql = "select count(*) from category where gp_id=$gp_id and cat_id=$cat_id and is_delete = 0";
    
    if ( $result = $db->query($sql) )
    {
         if( $row = $result->fetch_row() )
         {
             if( $row[0] == 1)
             {
                return true;
             }
         }
    }
    
    return false;
}

function UpdateCategory( $cat_id, $name, $type, $parent, $desc )
{
    global $db;
    $sql = "update category set name=?, type=?, parent=?, description=? where cat_id=? and is_delete=0";
    
    if( !$stmt = $db->prepare($sql) )
    {
        return false;
    }
    
    if( !$stmt->bind_param('siisi', $name, $type, $parent, $desc, $cat_id) )
    {
        return false;
    }
    
    $result = $stmt->execute();
    
    $stmt->close();
    
    return $result;
}

function InsertCategory( $name, $type, $parent, $desc, $gp_id, $user_id )
{
    global $db;
    
    $sql = "insert into category(name, type, parent, description, gp_id, create_user_id) values(?, ?, ?, ?, ?, ?)";
    
    if( !$stmt = $db->prepare($sql) )
    {
        return false;
    }
    
    if( !$stmt->bind_param('siisii', $name, $type, $parent, $desc, $gp_id, $user_id) )
    {
        return false;
    }
    
    $result = $stmt->execute();
    
    $stmt->close();
    
    return $result;
}

function GetCategories($type, $only_root = false)
{
    global $db;
    
    if($type == CATEGORY_TYPE_PAY || $type == CATEGORY_TYPE_EARN)
    {
        $sql = "select a.cat_id, a.name, a.parent, a.description, a.type, b.cost_limit from category as a left join plan as b on a.cat_id = b.cat_id where a.type = $type and a.gp_id=$_SESSION[gp_id] and is_delete=0";
    }
    elseif($type == CATEGORY_TYPE_ALL)
    {
        $sql = "select a.cat_id, a.name, a.parent, a.description, a.type, b.cost_limit from category as a left join plan as b on a.cat_id = b.cat_id where a.gp_id=$_SESSION[gp_id] and a.is_delete=0";
    }
    else 
    {
        return array();
    }
    
    $categories = array();
    
    if($result = $db->query( $sql ))
    {
        while( $row = $result->fetch_assoc() )
        {
            if( $only_root && $row['parent'] != 0)
            {
                continue;
            }
            $categories [] = $row;
        }
    }
    
    return $categories;
}

function DeleteCategory($cat_id, $gp_id)
{
    $sql = "update category set is_delete=1 where (cat_id = $cat_id or parent=$cat_id) and gp_id = $gp_id";
    
    global $db;
    
    return $db->query($sql);
}