<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  include('class/c_servicio.php'); 

  extract($_REQUEST);		

  $cait=new c_servicio($conn);
  
  for($i=0;$i<$total;$i++)
  {
    if(isset($chc[$i]))
    {
  	  $id=$chc[$i];
	  $cait->del($id);
    }
  }
  
  //destino
  /*
  $cextra=explode("|",$campo_extra);
  $t_cextra=count($cextra);
  for ($i=0;$i<$t_cextra;$i++)
  {
	$c1=$cextra[$i];
	
	//if($c1=="principal")  $destino="location:".$$c1."?";
	//else	
	
	$cad_dest.=$c1."=".$$c1."&";
  }
  $cad_dest=substr($cad_dest,0,(strlen($cad_dest)-1));
  */
  
  $param="principal=".$principal."&id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  
  $destino="location:servicio.php?".$param;
  $destinoE=$principal."?".$param;
  
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