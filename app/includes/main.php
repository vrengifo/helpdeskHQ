<?php
//shouldn't need to edit anything after this, only edit includes/defines.php
  //session_start();
  require_once('includes/defines.php');
  require_once('adodb/adodb.inc.php');
  require_once('includes/functions.php');
  //extract($_SESSION);

  ADOLoadCode(DB_TYPE);
  $conn = &ADONewConnection();
  //$conn->PConnect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD,DB_DATABASE);
  $conn->Connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD,DB_DATABASE);
  global $userid;
  
  
  
?>