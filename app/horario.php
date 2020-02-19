<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_horario.php");
  $oObj=new c_horario($conn);
  echo($oObj->adminAdmin("horario_del.php","horario.php",$id_aplicacion,$id_subaplicacion,"horario_add.php","horario_upd.php","Administración de Horarios"));
  
  buildsubmenufooter();		
?>