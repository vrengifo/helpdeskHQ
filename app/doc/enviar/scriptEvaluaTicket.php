<?php
  include_once('includes/main.php'); 
  include_once("class/c_ticket.php");
  include_once("class/c_tipoticket.php");
  include_once("class/c_servicio.php");
  include_once("class/c_prioridad.php");
  include_once("class/c_encuesta.php");
  include_once("class/c_pregunta.php");
  include_once("class/c_respuesta.php");
  include_once("class/c_respuestaencuesta.php");
  
  $conn->debug=true;
  
  $sql=<<<mya
select distinct tic_id from ticket
where tipest_id='CE' and tic_valorencuesta is null
and tic_fechahoramaxencuesta<now()
mya;
  $rs=&$conn->Execute($sql);
  
  $oObj=new c_ticket($conn);
  $oEncuesta=new c_encuesta($conn,"sistema");
  $oRespEnc=new c_respuestaencuesta($conn);
  
  while(!$rs->EOF)
  {
  	$ticId=$rs->fields[0];
    $oObj->info($ticId);
    
    $encuesta=$oEncuesta->obtenerEncuestaXServicio($oObj->ser_id);
    
    $oRespEnc->calculaMaximoResultado($ticId,$encuesta);

    $rs->MoveNext();
  }


?>