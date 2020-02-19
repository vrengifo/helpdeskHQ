<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  include('class/c_respuesta.php'); 

  extract($_REQUEST);		

  $cait=new c_respuesta($conn);
  
  for($i=0;$i<$total;$i++)
  {
    if(isset($chc[$i]))
    {
  	  $id=$chc[$i];
	  $cait->del($id);
    }
  }
  
  $param="principal=".$principal."&id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&idp=".$idp;
  
  $destino="location:respuesta.php?".$param;
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