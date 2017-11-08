<?php
define('IN_BOOKS', true);

require_once 'libs/init.php';
require_once 'libs/lib_category.php';

//todo:encode
if( empty($_SESSION['user_id']) || empty($_SESSION['gp_id']) )
{
    $target = "login.php?refer=";
    $refer = empty($_SERVER['HTTPS']) ? "http://" : "https://". $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']   ;
    $target .= urlencode($refer);
    echo "<script type=text/javascript>window.location.href='$target';</script>";
    exit;
}

reg_common_smarty_vars($smarty);

$act = empty( $_REQUEST['act'] ) ? 'list' : $_REQUEST['act'];

if($act == 'list')
{
    $cats = filter_htmlspecialchars(GetCategories(CATEGORY_TYPE_ALL));
    $categories = array('pay'=>array(), 'earn' => array() );
    
    foreach ($cats as $cat)
    {
        if( $cat['type'] == '0' ) //֧��
        {
            if($cat['parent'] == 0) //������
            {
                $categories['pay'][$cat['cat_id']][] = $cat;
            }
            else
            {
                $categories['pay'][$cat['parent']][] = $cat;
            }
            
        }
        else //����
        {
            if($cat['parent'] == 0) //������
            {
                $categories['earn'][$cat['cat_id']][] = $cat;
            }
            else
            {
                $categories['earn'][$cat['parent']][] = $cat;
            }
        }
    }
    
    
    $smarty->assign('title', '�������');
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
            filter_htmlspecialchars($row);
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
    $smarty->assign('title', '�༭����');
    $smarty->assign('act', 'modify');
    $smarty->display('edit_category.html');
    exit();
}
elseif($act == 'do_modify')
{
    if( !isset($_REQUEST['id']) || !isset($_REQUEST['parent'])
        || !isset($_REQUEST['type']))
    {
        echo "<script type=text/javascript>alert('�Ƿ�����');window.history.back(-1);</script>";
        exit();
    }
    $id         = intval($_REQUEST['id']);
    $parent     = intval($_REQUEST['parent']);
    $cat_name   = empty($_REQUEST['name']) ? '' : $_REQUEST['name'];
    $type       = intval($_REQUEST['type']);
    $desc       = empty($_REQUEST['description']) ? '' : $_REQUEST['description'];
    
    if( empty($cat_name) )
    {
        echo "<script type=text/javascript>alert('����������Ϊ��');window.history.back(-1);</script>";
        exit();
    }
    
    if( !CheckCatPrivilege($id, $_SESSION['gp_id']) )
    {
        die("privilege error!");
    }

    if( !UpdateCategory( $id, $cat_name, $type, $parent, $desc) )
    {
        echo "<script type=text/javascript>alert('����ʧ�ܣ�');window.history.back(-1);</script>";
        exit();    
    }
    
    echo "<script type=text/javascript>alert('���³ɹ���');window.location.href='category.php?act=list';</script>";
    exit();
}
elseif($act == 'add')
{
    $categories = filter_htmlspecialchars(GetCategories(CATEGORY_TYPE_PAY, true));
    $smarty->assign('root_categories', $categories);
    $smarty->assign('title', '��������');
    $smarty->assign('act', 'add');
    $smarty->display('edit_category.html');
    exit();
}
elseif($act == 'do_add')
{
    $name = filter_htmlspecialchars( trim($_REQUEST['name']) ) ;
    $type = intval($_REQUEST['type']);
    $parent = intval($_REQUEST['parent']); //check
    $desc   = trim($_REQUEST['description']);
    
    if( empty($name) )
    {
        echo "<script type=text/javascript>alert('����������Ϊ�գ�');window.history.back(-1);</script>";
    }
    
    if( !CheckCatPrivilege($parent, $_SESSION['gp_id']) )
    {
        die("privilege error!");
    }
    
    if( InsertCategory($name, $type, $parent, $desc, $_SESSION['gp_id'], $_SESSION['user_id']))
    {
        echo "<script type=text/javascript>alert('�½����ͳɹ���');window.location.href='category.php?act=add';</script>";
    }
    else
    {
        echo "<script type=text/javascript>alert('�½�����ʧ�ܣ�');window.history.back(-1);</script>";
    }
    
    exit();
}
elseif($act == 'delete')
{
    $cat_id = intval($_REQUEST['id']);
    
    if( !CheckCatPrivilege($cat_id, $_SESSION['gp_id']) )
    {
        die("privilege error!");
    }
    
    if( $result = DeleteCategory($cat_id, $_SESSION['gp_id']) )
    {
        echo "<script type=text/javascript>alert('ɾ�����ͳɹ���');window.location.href='category.php';</script>";
    }   
    else 
    {
        echo "<script type=text/javascript>alert('ɾ������ʧ�ܣ�');window.history.back(-1);</script>";
    }
    
    exit();
}

/**
 * ���ݿͻ��˵������ȡ��������
 */
elseif($act == 'GetTopCate')
{
    $typeid = empty($_REQUEST['type']) ? 0 : intval($_REQUEST['type']);
    $arrTemp = GetCategories($typeid, true);
    $arrCate = array();
    foreach($arrTemp as $value)
    {
            $arrCate[] = array("cat_id" =>$value['cat_id'],
                "cat_name" => iconv("GB2312","UTF-8//IGNORE",
                    $value['name']));
        
    }
    
    $request_method = empty($_REQUEST['method']) ? "" : trim($_REQUEST['method']);
    if ($request_method == "ajax")
    {
        header("content-type: application/json; charset=gb2312");
        $json = json_encode($arrCate);
        echo $json;
    }
    
    exit();
}
