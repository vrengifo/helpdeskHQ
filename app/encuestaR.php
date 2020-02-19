<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  
  include_once("class/c_ticket.php");
  $oObj=new c_ticket($conn);
  $oObj->info($ticId);
  
  include_once("class/c_usuario.php");
  $oUsu=new c_usuario($conn);
  $oUsu->info($oObj->usu_id);
  $nombreCliente=$oUsu->usu_nombre;
  $oUsu->info($oObj->usu_asignado);
  $nombreSoporte=$oUsu->usu_nombre;
  
  include_once("class/c_tipoticket.php");
  $oTT=new c_tipoticket($conn);
  $oTT->info($oObj->tiptic_id);
  $tipoTicket=$oTT->tiptic_nombre;
  
  include_once("class/c_servicio.php");
  $oSer=new c_servicio($conn);
  $oSer->info($oObj->ser_id);
  $servicio=$oSer->ser_nombre;
  
  include_once("class/c_prioridad.php");
  $oPri=new c_prioridad($conn);
  $oPri->info($oObj->pri_id);
  $prioridad=$oPri->pri_nombre;
  
  include_once("class/c_tipoestado.php");
  $oEstado=new c_tipoestado($conn);
  $oEstado->info($oObj->tipest_id);
  $estado=$oEstado->tipest_nombre;  
  
?>
<p align="center"><strong>Ticket Nro. <?=$oObj->tic_id?></strong></p>

<table width="100%" border="1" class="tab">
  <tr>
    <td><span style="font-weight: bold;" >Usuario:</span> <?=$nombreCliente?> </td>
    <td><span style="font-weight: bold;">Tipo Ticket:</span> <?=$nombreCliente?> </td>
    <td><span style="font-weight: bold;">Servicio:</span> <?=$servicio?> </td>
    <td><span style="font-weight: bold;">Prioridad:</span> <?=$prioridad?> </td>
    <td><span style="font-weight: bold;">Fecha Apertura:</span> <?=$oObj->tic_fechahoraapertura?> </td>
  </tr>
  <tr>
    <td><span style="font-weight: bold;">Soporte:</span> <?=$nombreSoporte?> </td>
    <td><span style="font-weight: bold;">Fecha Inicio Atenci&oacute;n:</span> <?=$oObj->tic_fechahorainicio?> </td>
    <td><span style="font-weight: bold;">Fecha Max. Solucion:</span> <?=$oObj->tic_fechahorafin?> </td>
    <td><span style="font-weight: bold;">Estado:</span> <?=$estado?> </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><span style="font-weight: bold;">Resumen:</span></td>
  </tr>
    <tr>
    <td colspan="5" class="detailblank"><?=$oObj->tic_resumen?></td>
  </tr>
    <tr>
    <td colspan="5"><span style="font-weight: bold;">Descripci&oacute;n:</span></td>
  </tr>
    <tr>
    <td colspan="5" class="detailblank"><?=$oObj->tic_descripcion?></td>
  </tr>
</table>
<p align="center">
:: Encuesta ::
</p>
<?
  if(($oObj->tipest_id=="CE")&&($oObj->tic_valorencuesta==""))
  {

  include_once("class/c_encuesta.php");
  $oEncuesta=new c_encuesta($conn,"");
  $encuesta=$oEncuesta->obtenerEncuestaXServicio($oObj->ser_id);
  if(!$encuesta)
  {
?>
  <p align="center"><h2><?=$oEncuesta->msg;?></h2></p>
<?php
  }
  else 
  {
  	include_once("class/c_pregunta.php");
  	include_once("class/c_respuesta.php");
  	$oPregunta=new c_pregunta($conn);
  	$oRespuesta=new c_respuesta($conn);
  	
  	$rsPreg=$oPregunta->listall($encuesta," order by pre_id asc");
  	
?>
<form method="POST" name="forma" action="encuestaR1.php">
  <input type="hidden" name="tTic" value="<?=$oObj->tic_id?>" />
  <input type="hidden" name="tEnc" value="<?=$encuesta?>" />
  <table align="center">
    <tr>
      <td>
      

<?php
//leer preguntas
  $cont=0;
  while(!$rsPreg->EOF)
  {
  	$preguntaId=$rsPreg->fields[1];
?>
  <table>
    <tr>
      <td>
        Pregunta: <?=$rsPreg->fields[2]?>
        <input type="hidden" name="arrPreg[<?=$cont?>]" value="<?=$preguntaId?>" />
      </td>
    </tr>
<?php
	$rsRes=$oRespuesta->listall($encuesta,$preguntaId," order by res_id asc ");
	while(!$rsRes->EOF)
	{
		
?>
    <!-- respuesta -->
    <tr>
      <td>
        <input type="radio" name="arrRes[<?=$cont?>]" value="<?=$rsRes->fields[2]?>" />
        &nbsp;:&nbsp; 
        <?=$rsRes->fields[3]?>
      </td>
    </tr>
    <!-- fin respuesta -->
<?php
	  $rsRes->MoveNext();
	}
?>    
  </table>
<?php
    $cont++;
    $rsPreg->MoveNext();
  }
//fin leer preguntas  
?>
  		<input type="hidden" name="total" value="<?=$cont?>" />
      </td>
    </tr>
    <tr>
      <td align="center">
        <input type="submit" name="bEnviar" value="Procesar" />
      </td>
    </tr>
  </table>
</form>
<?php
  }
  }

?>
<?php
		buildsubmenufooter();		
?>
</body>
</html>