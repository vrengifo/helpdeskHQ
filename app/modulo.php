<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_modulo.php");
  $oObj=new c_modulo($conn);
  echo($oObj->adminAdmin("modulo_del.php","modulo.php",$id_aplicacion,$id_subaplicacion,"modulo_add.php","modulo_upd.php","Administración de Módulos"));
  
  buildsubmenufooter();
?>