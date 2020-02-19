<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_tipousuario.php");
  $oObj=new c_tipousuario($conn);
  
  if($act=="add")
    $titulo="Tipo de Usuario Añadido";
  else 
    $titulo="Actualizar Tipo de Usuario";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("tipousuario_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador));
  
  buildsubmenufooter();
?>