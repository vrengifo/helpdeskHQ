<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_servicio.php");
  $oObj=new c_servicio($conn);
  
  echo($oObj->adminAdd("servicio_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"Añadir Servicio"));
  
  buildsubmenufooter();
?>