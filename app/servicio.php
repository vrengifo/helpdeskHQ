<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_servicio.php");
  $oObj=new c_servicio($conn);
  echo($oObj->adminAdmin("servicio_del.php","servicio.php",$id_aplicacion,$id_subaplicacion,"servicio_add.php","servicio_upd.php","Administración de Servicio"));
  
  buildsubmenufooter();		
?>