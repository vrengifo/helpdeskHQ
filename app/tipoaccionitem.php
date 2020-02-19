<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_tipoaccionitem.php");
  $oObj=new c_tipoaccionitem($conn);
  echo($oObj->adminAdmin("tipoaccionitem_del.php","tipoaccionitem.php",$id_aplicacion,$id_subaplicacion,"tipoaccionitem_add.php","tipoaccionitem_upd.php","Administración de Tipos de Acci&oacute;n Item"));
  
  buildsubmenufooter();		
?>