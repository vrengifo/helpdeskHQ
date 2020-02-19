<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_item.php");
  $oObj=new c_item($conn);
  echo($oObj->adminAdmin("item_del.php","item.php",$id_aplicacion,$id_subaplicacion,"item_add.php","item_upd.php","Administración de Items"));
  
  buildsubmenufooter();		
?>