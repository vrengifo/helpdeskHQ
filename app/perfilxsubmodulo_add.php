<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_perfilxsubmodulo.php");
  $oObj=new c_perfilxsubmodulo($sUsername,$conn);
  
  /*echo "<hr>tPerfil $tPerfil<hr>";
  echo "<hr>tModulo $tModulo<hr>";
  echo "<hr>tSubmodulo $tSubmodulo<hr>";*/

  echo($oObj->adminAdd("perfilxsubmodulo_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"Añadir Perfil x Submódulo",$_REQUEST));

  buildsubmenufooter();
?>