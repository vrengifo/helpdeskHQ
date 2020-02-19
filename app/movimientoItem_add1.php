<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  include('class/c_logmovimientoitem.php'); 

  extract($_REQUEST);
  extract($_SESSION);
  
  $cait=new c_logmovimientoitem($conn,$sUsername);
  
  switch ($accion) 
  {
  	case "1":
  		$cait->asignaItem($tItem,$tUsuario,$tDescripcion);
  		break;
  	case "2":
  		$cait->mantenimientoItem($tItem,$sUsername,$tDescripcion);
  		break;
  	case "3":
  		$cait->bajaItem($tItem,$sUsername,$tDescripcion);
  		break;
  	case "4":
  		$cait->reasignaItem($tItem,$tUsuario,$tDescripcion);
  		break;
  	case "5":
  		$cait->desasignaItem($tItem,$sUsername,$tDescripcion);
  		break;
  }
   
  //destino
  $cextra=explode("|",$campo_extra);
  $t_cextra=count($cextra);
  for ($i=0;$i<$t_cextra;$i++)
  {
	$c1=$cextra[$i];
	$cad_dest.=$c1."=".$$c1."&";
  }
  $cad_dest=substr($cad_dest,0,(strlen($cad_dest)-1));
  $destino="location:movimientoItem.php?".$cad_dest."&id=".$idp."&act=add";
  $destinoE=$principal."?".$cad_dest."&id=".$idp;
  
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
     <body onLoad="mensaje('<?=$cait->msg?>');self.location='<?=$destinoE?>';">
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