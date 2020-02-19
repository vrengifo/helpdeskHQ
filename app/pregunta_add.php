<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  /*
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  */
  
  include_once("class/c_pregunta.php");
  $oObj=new c_pregunta($conn);
  
  echo($oObj->adminAdd("pregunta_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"Añadir Pregunta",$_REQUEST));
  
  buildsubmenufooter();
?>