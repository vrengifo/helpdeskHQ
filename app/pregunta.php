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
  
  include_once("class/c_pregunta.php");
  $oObj=new c_pregunta($conn);
  echo($oObj->adminAdmin("pregunta_del.php","pregunta.php",$id_aplicacion,$id_subaplicacion,"pregunta_add.php","pregunta_upd.php","Administración de Preguntas",$idp));
  
  buildsubmenufooter();		
?>