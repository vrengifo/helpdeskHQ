<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_prioridad.php");
  $oObj=new c_prioridad($conn);
  echo($oObj->adminAdmin("prioridad_del.php","prioridad.php",$id_aplicacion,$id_subaplicacion,"prioridad_add.php","prioridad_upd.php","Administración de Prioridad"));
  
  buildsubmenufooter();		
?>