<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  /*
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  */
  
  include_once("class/c_respuesta.php");
  $oObj=new c_respuesta($conn);
  echo($oObj->adminAdmin("respuesta_del.php","respuesta.php",$id_aplicacion,$id_subaplicacion,"respuesta_add.php","respuesta_upd.php","Administración de Respuestas",$idp));
  
  buildsubmenufooter();		
?>