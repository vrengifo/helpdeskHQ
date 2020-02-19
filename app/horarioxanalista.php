<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_horarioxanalista.php");
  $oObj=new c_horarioxanalista($conn,$sUsername);
  echo($oObj->adminAdmin("horarioxanalista_del.php","horarioxanalista.php",$id_aplicacion,$id_subaplicacion,"horarioxanalista_add.php","horarioxanalista_upd.php","Administración de Horarios - Analista"));
  
  buildsubmenufooter();		
?>