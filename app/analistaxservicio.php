<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_analistaxservicio.php");
  $oObj=new c_analistaxservicio($conn,$sUsername);
  echo($oObj->adminAdmin("analistaxservicio_del.php","analistaxservicio.php",$id_aplicacion,$id_subaplicacion,"analistaxservicio_add.php","analistaxservicio_upd.php","Administración de Analistas por Servicio"));
  
  buildsubmenufooter();		
?>