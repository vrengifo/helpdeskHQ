<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_analistaxservicio.php");
  $oObj=new c_analistaxservicio($conn,$sUsername);
  
  echo($oObj->adminAdd("analistaxservicio_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"A�adir Analista por Servicio"));
  
  buildsubmenufooter();
?>