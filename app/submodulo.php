<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
 
  include_once("class/c_submodulo.php");
  $oObj=new c_submodulo($conn);
  echo($oObj->adminAdmin("submodulo_del.php","submodulo.php",$id_aplicacion,$id_subaplicacion,"submodulo_add.php","submodulo_upd.php","Administración de Submódulos"));
  
  buildsubmenufooter();		
?>