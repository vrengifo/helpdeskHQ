<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
    
  include_once("class/c_perfilxsubmodulo.php");
  $oObj=new c_perfilxsubmodulo($sUsername,$conn);
  echo($oObj->adminAdmin("perfilxsubmodulo_del.php","perfilxsubmodulo.php",$id_aplicacion,$id_subaplicacion,"perfilxsubmodulo_add.php","#","Administración de Perfiles x Submódulos"));
  
  buildsubmenufooter();		
?>