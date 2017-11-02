<?php
/* Smarty version 3.1.30, created on 2017-11-02 15:25:59
  from "D:\code\project\books\templates\default\header.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59fb2af764d9e4_73400398',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7de49f9e50afb564f9b4e8388d0f81d957f54495' => 
    array (
      0 => 'D:\\code\\project\\books\\templates\\default\\header.html',
      1 => 1509632535,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59fb2af764d9e4_73400398 (Smarty_Internal_Template $_smarty_tpl) {
?>
<header>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">宝贝计划财务管理系统</a>
        <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
          
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">DashBoard</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="dashboard.php">DashBoard</a>
              <a class="dropdown-item" href="#">Another action</a>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </li>
            <li class="nav-item active">
              <a class="nav-link" href="category.php">收支分类</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="cash.php">记账</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">预算计划</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">卡片管理</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">债务管理</a>
            </li>
          </ul>
          <form class="form-inline mt-2 mt-md-0">
            <a class="nav-link" href="#"><?php echo $_smarty_tpl->tpl_vars['username']->value;?>
</a>
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
          </form>
        </div>
      </nav>
</header><?php }
}
