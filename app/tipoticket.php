<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_tipoticket.php");
  $oObj=new c_tipoticket($conn);
  echo($oObj->adminAdmin("tipoticket_del.php","tipoticket.php",$id_aplicacion,$id_subaplicacion,"tipoticket_add.php","tipoticket_upd.php","Administración de Tipos de Ticket"));
  
  buildsubmenufooter();		
?>