<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_tipousuario.php");
  $oObj=new c_tipousuario($conn);
  echo($oObj->adminAdmin("tipousuario_del.php","tipousuario.php",$id_aplicacion,$id_subaplicacion,"tipousuario_add.php","tipousuario_upd.php","Administración de Tipos de Usuario"));
  
  buildsubmenufooter();		
?>