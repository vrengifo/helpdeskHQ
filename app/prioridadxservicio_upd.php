<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_prioridadxservicio.php");
  $oObj=new c_prioridadxservicio($conn);
  
  if($act=="add")
    $titulo="Prioridad por Servicio Añadida";
  else 
    $titulo="Actualizar Prioridad por Servicio";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("prioridadxservicio_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador));
  
  buildsubmenufooter();
?>