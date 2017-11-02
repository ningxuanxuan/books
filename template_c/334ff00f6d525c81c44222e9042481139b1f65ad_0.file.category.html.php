<?php
/* Smarty version 3.1.30, created on 2017-11-02 16:21:23
  from "D:\code\project\books\templates\default\category.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59fb37f3637f57_77830235',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '334ff00f6d525c81c44222e9042481139b1f65ad' => 
    array (
      0 => 'D:\\code\\project\\books\\templates\\default\\category.html',
      1 => 1509636072,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.html' => 1,
    'file:bottom.html' => 1,
  ),
),false)) {
function content_59fb37f3637f57_77830235 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="gbk" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['theme_root']->value;?>
/css/dashboard.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['theme_root']->value;?>
/css/common.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <?php echo '<script'; ?>
 src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"><?php echo '</script'; ?>
>
    
    <title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
</head>
<body>
<?php $_smarty_tpl->_subTemplateRender("file:header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="container-fluid">
<div class="row">       
    <main role="main" class="ml-sm-auto col-sm-10">
    <h1>支出分类</h1>
    <table class="table">
    <thead>
    <tr>
      <th scope="col">分类名</th>
      <th scope="col">说明</th>
      <th scope="col">上级分类</th>
      <th scope="col">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['categories']->value, 'cate');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['cate']->value) {
?>
    <tr>
      <td><?php echo $_smarty_tpl->tpl_vars['cate']->value['name'];?>
</th>
      <td><?php echo $_smarty_tpl->tpl_vars['cate']->value['description'];?>
</td>
      <td><?php echo $_smarty_tpl->tpl_vars['cate']->value['parent'];?>
</td>
      <td>@mdo</td>
    </tr>
    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

  </tbody>
</table>      
    </main>
    </div>
    </div>
    
    <?php $_smarty_tpl->_subTemplateRender("file:bottom.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>

</html><?php }
}
