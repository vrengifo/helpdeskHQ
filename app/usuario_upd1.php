<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  include('class/c_usuario.php'); 

  extract($_REQUEST);

  $cait=new c_usuario($conn);
  
  $cbase=explode("|",$campo_base);
  $t_cbase=count($cbase);

  for($i=0;$i<$t_cbase;$i++)
  {
	$dato[$i]=$$cbase[$i];
  }
  
  $resCarga=$cait->cargar_dato($dato,"u");
  if($resCarga)
  {
  	$idp=$cait->update($id);
  	if($idp=="0")
  	  $cait->msg="Error al actualizar!!!";  	    
  }
  
  //destino
  $cextra=explode("|",$campo_extra);
  $t_cextra=count($cextra);
  for ($i=0;$i<$t_cextra;$i++)
  {
	$c1=$cextra[$i];
	/*
	if($c1=="principal")	
	  $destino="location:".$$c1."?";
	else	
	*/	
	$cad_dest.=$c1."=".$$c1."&";
  }
  $cad_dest=substr($cad_dest,0,(strlen($cad_dest)-1));
  $destino="location:usuario.php?".$cad_dest;
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