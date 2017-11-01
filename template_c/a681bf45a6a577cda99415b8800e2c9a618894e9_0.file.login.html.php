<?php
/* Smarty version 3.1.30, created on 2017-11-01 15:49:07
  from "D:\code\project\books\templates\default\login.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59f9dee3d11fe6_39107048',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a681bf45a6a577cda99415b8800e2c9a618894e9' => 
    array (
      0 => 'D:\\code\\project\\books\\templates\\default\\login.html',
      1 => 1509547466,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59f9dee3d11fe6_39107048 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['theme_root']->value;?>
/css/login.css" />
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
<title>登录</title>
</head>
	
<body>
<div class="container">
	<form method="post" action="login.php" class="form-signin">

    <h3 class="form-signin-heading">登录</h3>

		<label for="username" class="sr-only">用户名</label>
		<input id="username" name="username" type="username"  class="form-control" placeholder="用户名" required autofocus/>


		<label for="password" class="sr-only">密码：</label>
		<input  id="password" name="password" type="password"  class="form-control" placeholder="密码" required/>
	
		<input type="hidden" name="act" value="check_login" />
		<input type="hidden" name="refer" value="<?php echo $_smarty_tpl->tpl_vars['refer']->value;?>
" />
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

	</form>
	</div>

<?php echo '<script'; ?>
 type="text/javascript">
$(":input:first").focus();
<?php echo '</script'; ?>
>

<title>登录</title>
</body>
</html>
<?php }
}
