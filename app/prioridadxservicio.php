<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_prioridadxservicio.php");
  $oObj=new c_prioridadxservicio($conn);
  echo($oObj->adminAdmin("prioridadxservicio_del.php","prioridadxservicio.php",$id_aplicacion,$id_subaplicacion,"prioridadxservicio_add.php","prioridadxservicio_upd.php","Administración de Prioridad por Servicio"));
  
  buildsubmenufooter();		
?>