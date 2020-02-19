<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_tipoaccionitem.php");
  $oObj=new c_tipoaccionitem($conn);
  
  echo($oObj->adminAdd("tipoaccionitem_add1.php",$principal,$id_aplicacion,$id_subaplicacion,"Añadir Tipo Acci&oacute;n de Item"));
  
  buildsubmenufooter();
?>