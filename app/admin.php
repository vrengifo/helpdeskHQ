<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  
  extract($_REQUEST);
  extract($_SESSION);
  require_once('includes/header.php');
  
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,0,$sPerfil);
  buildsubmenufooter();
?>