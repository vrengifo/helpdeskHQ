<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  /*
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  */
  
  include_once("class/c_pregunta.php");
  $oObj=new c_pregunta($conn);
  
  if($act=="add")
    $titulo="Pregunta Añadida";
  else 
    $titulo="Actualizar Pregunta";
  $identificador=$id;
  //echo "<hr>$identificador<hr>";
  
  echo($oObj->adminUpd("pregunta_upd1.php",$principal,$id_aplicacion,$id_subaplicacion,$titulo,$identificador,$_REQUEST));
  
  buildsubmenufooter();
?>