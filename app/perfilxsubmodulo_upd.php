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
  
  if($act=="add")
    $titulo="Perfil x Subm�dulo A�adido";
  else 
    $titulo="Actualizar Perfil x Subm�dulo";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("perfilxsubmodulo_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador));
  
  buildsubmenufooter();
?>