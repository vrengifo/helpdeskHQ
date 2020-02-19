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
    $titulo="Perfil x Submódulo Añadido";
  else 
    $titulo="Actualizar Perfil x Submódulo";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("perfilxsubmodulo_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador));
  
  buildsubmenufooter();
?>