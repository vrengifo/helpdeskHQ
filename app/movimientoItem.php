<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_logmovimientoitem.php");
  $oObj=new c_logmovimientoitem($conn,$sUsername);
  echo($oObj->adminAdmin("#","movimientoItem.php",$id_aplicacion,$id_subaplicacion,"movimientoItem_add.php","#","Movimientos"));
  
  buildsubmenufooter();		
?>