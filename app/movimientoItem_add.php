<?php
  session_start(); 
  include('includes/main.php'); 
  include('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  include_once("class/c_logmovimientoitem.php");
  $oObj=new c_logmovimientoitem($conn,$sUsername);
  
  $tituloForma="";
  switch ($accion)
  {
  	case "1":
  		$tituloForma="Asignaci&oacute;n";
  		break;
  	case "2":
  		$tituloForma="Mantenimiento";
  		break;
  	case "3":
  		$tituloForma="Dada de Baja";
  		break;
  	case "4":
  		$tituloForma="Reasignaci&oacute;n";
  		break;
  	case "5":
  		$tituloForma="Desasignaci&oacute;n";
  		break;
  }
  $arr=array();
  $arr["accion"]=$accion;
  
  echo($oObj->adminAdd("movimientoItem_add1.php",$principal,$id_aplicacion,$id_subaplicacion,$tituloForma,$arr));
  
  buildsubmenufooter();
?>