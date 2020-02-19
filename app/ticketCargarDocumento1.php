<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  include_once('class/c_ticket.php'); 

  extract($_REQUEST);
  extract($_SESSION);
  
  $nombreArchivo=$_FILES['tFile']['name'];
  $directorio="ticketDoc/";
  
  $cait=new c_ticket($conn);
  $resNombreArchivo=$cait->updateCargarDocumento($id,$sUsername,$nombreArchivo,$tDescripcion,$directorio);
  
  include_once("class/c_snapshot.php");
  $abpath=$pathUpload.$directorio;
  
  if(strlen($_FILES['tFile']['name'])>0)
  {  
    $cImg = new c_snapshot();
	$cImg->ImageField = $_FILES['tFile'];
	$cad="";
  	$namenewfile=$resNombreArchivo;
  	$upfile=$abpath.$namenewfile;//win
	@$cImg->copiaArchivo($upfile);
  }
  
  $cait->msg="Archivo Cargado Satisfactoriamente!!!";
  
  if(strlen($cait->msg)>0)
  {
   ?>
   <html>
   <script language="javascript">
     function mensaje(msg)
	 {
	   alert(msg);
	 }
   </script>
     <body onLoad="mensaje('<?=$cait->msg?>');window.opener.location.reload();window.close();">
	 </body>
   </html>
   <?
  }
  else
  {
    //echo "$destino";
    header($destino);
  }
?>