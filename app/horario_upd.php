<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_horario.php");
  $oObj=new c_horario($conn);
  
  if($act=="add")
    $titulo="Horario A�adido";
  else 
    $titulo="Actualizar Horario";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("horario_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador));
  
  buildsubmenufooter();
?>