<?php 
  session_start();
  include('includes/main.php');
  include('adodb/tohtml.inc.php');
  include('class/c_ticket.php'); 

  extract($_REQUEST);

  $cait=new c_ticket($conn);
  
  $cait->updateActualizarEstado($id,$sUsername,$tEstado,$tComentario,$tPalabraClave);
  
  $cait->msg="Estado actualizado!!!";  	    
  
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