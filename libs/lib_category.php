<?php
function CheckCatPrivilege($cat_id, $gp_id)
{
    global $db;
    $sql = "select count(*) from category where gp_id=$gp_id and cat_id=$cat_id";
    
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
    $sql = "update category set name=?, type=?, parent=?, description=? where cat_id=?";
    
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