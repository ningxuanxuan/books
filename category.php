<?php
define('IN_BOOKS', true);

require_once 'libs/init.php';

//todo:encode
if( empty($_SESSION['user_id']) || empty($_SESSION['gp_id']) )
{
    $target = "login.php?refer=";
    $target .= empty($_SERVER['HTTPS']) ? "http://" : "https://". $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']   ;
    echo "<script type=text/javascript>window.location.href='$target';</script>";
    exit;
}

reg_common_smarty_vars($smarty);

$act = empty( $_REQUEST['act'] ) ? 'list' : $_REQUEST['act'];

if($act == 'list')
{
    $user_id = $_SESSION['user_id'];
    $group_id = $_SESSION['gp_id'];
    
    
    $sql = "select * from category where gp_id = $_SESSION[gp_id] "
        . " and is_delete=0";
    
    $result = $db->query( $sql );
    
    $categories = array();
    
    if( $result = $db->query( $sql ) )
    {
        while($row = $result->fetch_assoc())
        {
            if( $row['type'] == '0' ) //支出
            {
                if($row['parent'] == 0) //根分类
                {
                    $categories['pay'][$row['cat_id']][] = $row;
                }
                else
                {
                    $categories['pay'][$row['parent']][] = $row;
                }
                
            }
            else //收入
            {
                if($row['parent'] == 0) //根分类
                {
                    $categories['earn'][$row['cat_id']][] = $row;
                }
                else
                {
                    $categories['earn'][$row['parent']][] = $row;
                }
            }
            $categories[] = $row;
        }
    }
    
    $smarty->assign('title', '分类管理');
    $smarty->assign('categories', $categories);
    $smarty->display('category.html');
    
    exit();
}

elseif($act == 'modify')
{
    if(empty($_REQUEST['id']))
    {
        exit();
    }
    
    $id = intval($_REQUEST['id']);
    
    $sql = "select * from category where cat_id=$id and gp_id=$_SESSION[gp_id]"
        . " and is_delete=0";
    
    if($result = $db->query($sql))
    {
        if( $row = $result->fetch_assoc() )
        {
            //display
            $smarty->assign('info', $row);

        }
        else 
        {
            exit();
        }
    }
    else 
    {
        exit();
    }
    
    $sql = "select cat_id, name, type from category where gp_id = $_SESSION[gp_id]"
        ." and parent=0 and is_delete=0";
    
    $root_categories = array();
    
    if( $result = $db->query($sql) )
    {
        while($row = $result->fetch_array())
        {
            $root_categories[] = $row;
        }
    }
    $smarty->assign('root_categories', $root_categories);
    $smarty->assign('title', '编辑分类');
    $smarty->assign('act', 'modify');
    $smarty->display('edit_category.html');
    exit();
}
elseif($act == 'do_modify')
{
    echo "modify!";
    exit();
}
elseif($act == 'add')
{
    echo "add";
    exit();
}
elseif($act == 'do_add')
{
    echo "do_add";
    exit();
}
elseif($act == 'delete')
{
    echo "delete";
    exit();
}

/**
 * 根据客户端的请求获取顶级分类
 */
elseif($act == 'GetTopCate')
{
    $typeid = empty($_REQUEST['type']) ? 0 : intval($_REQUEST['type']);
    $arrTemp = getCategory($typeid);
    $arrCate = array();
    foreach($arrTemp as $value)
    {
        if ($value['parent'] == "0")
        {
            $arrCate[] = array("cat_id" =>$value['cat_id'],
                "cat_name" => iconv("GB2312","UTF-8//IGNORE",
                    $value['name']));
        }
        
    }
    
    $request_method = empty($_REQUEST['method']) ? "" : trim($_REQUEST['method']);
    if ($request_method == "ajax")
    {
        header("content-type: application/json; charset=gb2312");
        $json = json_encode($arrCate);
        echo $json;
    }
}

/**
 *return a formated array
 */

function getCategory($type=0)
{
    $ext_type = $type === 0 ? 0 : 1;
    global $db;
    $sql = 'select * from category where gp_id='.$_SESSION['gp_id'].' AND is_delete=0 AND type='.$ext_type;
    $res = array();
    
    if( $result = $db->query($sql) )
    {
        while($row = $result->fetch_assoc())
        {
            $res [] = $row;
        }
    }
    
    $ser_res = $res;
    $arr = array();
    foreach($ser_res as $res1)
    {
        if($res1['parent'] != 0)
        {
            continue;
        }
        $arr[]=$res1;
        foreach($ser_res as $key=>$res2)
        {
            if($res2['parent'] === $res1['cat_id'] && $res2['parent'] != 0)
            {
                $arr[] = $res2;
                unset($ser_res[$key]);  //放过子串；
                
            }
        }
        
        
    }
    
    return $arr;
}
