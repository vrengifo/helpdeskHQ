<?php 
  session_start();
  include_once('includes/main.php');
  include_once('adodb/tohtml.inc.php');
  include_once('class/c_respuesta.php'); 
  include_once('class/c_pregunta.php'); 

  extract($_REQUEST);		
  $cait=new c_respuesta($conn);
  $oPregunta=new c_pregunta($conn);
  
  $oPregunta->cad2id($idp);
  
  $cbase=explode("|",$campo_base);
  $t_cbase=count($cbase);

  for($i=0;$i<$t_cbase;$i++)
  {
	$dato[$i]=$$cbase[$i];
  }
  
  $resCarga=$cait->cargar_dato($dato);
  if($resCarga)
  {
  	$cait->enc_id=$oPregunta->enc_id;
  	$cait->pre_id=$oPregunta->pre_id;
  	$idAdded=$cait->add();
  	if($idAdded=="")
  	  $cait->msg="Faltan datos!!!";  	    
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
  $destino="location:respuesta_upd.php?".$cad_dest."&id=".$idAdded."&act=add&idp=".$idp;
  $destinoE=$principal."?".$cad_dest."&idp=".$idp;
  
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