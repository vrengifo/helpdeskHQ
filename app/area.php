<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_area.php");
  $oObj=new c_area($conn);
  echo($oObj->adminAdmin("area_del.php","area.php",$id_aplicacion,$id_subaplicacion,"area_add.php","area_upd.php","Administración de Areas / Departamentos"));
  
  buildsubmenufooter();		
?>