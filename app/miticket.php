<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  
  $arr=array();
  $arr["sUsername"]=$sUsername;
  
  
  include_once("class/c_ticket.php");
  $oObj=new c_ticket($conn);
  echo($oObj->adminAdminMiTicket("#","miticket.php",$id_aplicacion,$id_subaplicacion,"miticket_add.php","ticketVer.php","Mis Tickets",$arr));
  
  buildsubmenufooter();		
?>