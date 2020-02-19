<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_encuesta.php");
  $oObj=new c_encuesta($conn,$sUsername);
  echo($oObj->adminAdmin("encuesta_del.php","encuesta.php",$id_aplicacion,$id_subaplicacion,"encuesta_add.php","encuesta_upd.php","Administración de Encuestas"));
  
  buildsubmenufooter();		
?>