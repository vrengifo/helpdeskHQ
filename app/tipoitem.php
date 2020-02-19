<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_tipoitem.php");
  $oObj=new c_tipoitem($conn);
  echo($oObj->adminAdmin("tipoitem_del.php","tipoitem.php",$id_aplicacion,$id_subaplicacion,"tipoitem_add.php","tipoitem_upd.php","Administración de Tipos de Item"));
  
  buildsubmenufooter();		
?>