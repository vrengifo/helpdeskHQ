<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  extract($_SESSION);
  
  /*
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
  */
  
  include_once("class/c_ticket.php");
  $oObj=new c_ticket($conn);
  $oObj->info($id);
  
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
  
  //echo($oObj->adminAdmin("pregunta_del.php","pregunta.php",$id_aplicacion,$id_subaplicacion,"pregunta_add.php","pregunta_upd.php","Administración de Preguntas",$idp));
  
 
  
  
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
<?
  $cadForma=$oObj->formaActualizarEstado("ticketActualizarEstado1.php","ticketActualizarEstado.php",$id_aplicacion,$id_subaplicacion,"Actualizar Estado",$_REQUEST);
?>
<?=$cadForma?>	

<?php
		buildsubmenufooter();		
		//rs2html($recordSet,"border=3 bgcolor='#effee'");
		
?>
</body>
</html>