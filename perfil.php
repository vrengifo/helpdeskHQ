<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_perfil.php");
  $oObj=new c_perfil($conn);
  echo($oObj->adminAdmin("perfil_del.php","perfil.php",$id_aplicacion,$id_subaplicacion,"perfil_add.php","perfil_upd.php","Administración de Perfiles"));
  
  buildsubmenufooter();		
?>