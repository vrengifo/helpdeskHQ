<?php 
  session_start();
  include_once('includes/main.php');
  include_once('adodb/tohtml.inc.php');
  include_once('class/c_ticket.php'); 
  include_once('class/c_respuesta.php');
  include_once('class/c_respuestaencuesta.php');
  include_once('class/c_parametro.php');

  extract($_REQUEST);

  $cait=new c_ticket($conn);
  $oResp=new c_respuestaencuesta($conn);
  
  $oR=new c_respuesta($conn);
  
  $cait->info($tTic);
  if(($cait->tipest_id=="CE")&&($cait->tic_valorencuesta==""))
  {
  	$oResp->tic_id=$tTic;
  	$oResp->enc_id=$tEnc;
  	for($i=0;$i<$total;$i++)
  	{
  		$preg=$arrPreg[$i];
  		$resp=$arrRes[$i];
  		
  		$oResp->pre_id=$preg;
  		$oResp->res_id=$resp;
  		
  		$oR->info($oR->id2cad($oResp->enc_id,$oResp->pre_id,$oResp->res_id));
  		
  		$oResp->res_valor=$oR->res_peso;
  		
  		$oResp->add();
  	}
  	$oResp->calculaResultado();
  	$cait->msg="Gracias por resolver la encuesta!!!";
  }
  else 
    $cait->msg="No se almacenaran los datos porque encuesta ya fue anteriormente evaluada!!!";
  
  if(strlen($cait->msg)>0)
  {
  	$oPar=new c_parametro($conn);
  	$oPar->info();
   ?>
   <html>
   <script language="javascript">
     function mensaje(msg)
	 {
	   alert(msg);
	   location.href="<?=$oPar->par_homesite?>";
	 }
   </script>
     <body onLoad="mensaje('<?=$cait->msg?>');">
	 </body>
   </html>
   <?
  }
  else
  {
    header($destino);
  }
?>