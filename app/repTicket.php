<?php
  session_start();
  include_once('includes/main.php'); 
  include_once('adodb/tohtml.inc.php'); 
  extract($_REQUEST);
  require_once('includes/header.php');
  
  extract($_SESSION);
  buildmenu($sUsername,$sPerfil);
  buildsubmenu($id_aplicacion,$id_subaplicacion,$sPerfil);
    
?>
	  <!-- buscar -->
	  <form name="form1" method="post" action="repTicket.php">
	  
	  <input type="hidden" name="principal" value="<?=$principal?>">
	  <input type="hidden" name="id_aplicacion" value="<?=$id_aplicacion?>">
	  <input type="hidden" name="id_subaplicacion" value="<?=$id_subaplicacion?>">
	  <input type="hidden" name="id" value="<?=$id?>">
	  
	  <TABLE WIDTH="50%" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
	    <TR>
		  <TD>
			<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
			  <tr>
				<td nowrap>
				  <SPAN class="title" STYLE="cursor:default;">
					<img src="images/yearview.gif" border=0 align=absmiddle HSPACE=2>
					<font color="#FFFFFF">
					  Reporte de Tickets / Ranking Soporte
					</font>
				  </SPAN>
				</td>
			  </tr>
			</table>
			<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" CLASS="tableinside">
			  <TR>
				<TD>
				  <TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>
					<TR valign=top bgcolor='#ffffff'>
					  <TD width="25%" valign=top nowrap>Fecha Desde:</TD>
					  <TD width="75%" valign=top nowrap>
					    <input name="bFDesde" type="text" id="bFDesde" value="<?=$bFDesde?>" >
						<a href="javascript:show_calendar('form1.bFDesde');" onmouseover="window.status='Date Picker';return true;"> <img src="images/big_calendar.gif" width=24 height=24 border=0></a>
					  </TD>
					  <TD rowspan="3" valign="middle" align="center">
					    <input name="bProcesar" type="submit" id="bProcesar" value="Procesar">
					  </TD>
					</TR>
					<TR valign=top bgcolor='#ffffff'>
					  <TD valign=top nowrap>Fecha Hasta:</TD>
					  <TD valign=top nowrap>
					    <input name="bFHasta" type="text" id="bFHasta" value="<?=$bFHasta?>" >
						<a href="javascript:show_calendar('form1.bFHasta');" onmouseover="window.status='Date Picker';return true;"> <img src="images/big_calendar.gif" width=24 height=24 border=0></a>
					  </TD>
					</TR>
					<!--
					<TR valign=top bgcolor='#ffffff'>
					  <TD valign=top nowrap>Servicio:</TD>
					  <TD valign=top nowrap>
					    <select name="bServicio" id="bServicio">
						  <?php
						    $sql=<<<mya
                            SELECT ser_id,ser_nombre from servicio 
                            order by ser_id 
mya;
                            $rs=&$conn->Execute($sql);
                            while(!$rs->EOF)
                            {
                              $vId=$rs->fields[0];
                              $vTexto=$rs->fields[1];
                              ?>
                            <option value="<?=$vId?>" <?php if($bServicio==$vId) echo "selected"; ?>><?=$vTexto?></option>  
                              <?
                              $rs->MoveNext();
                            }
						  ?>
						</select>
					  </TD>
					</TR>
					-->
				  </TABLE>
				</TD>
        	  </TR>
			</TABLE>
		  </TD>
		</TR>
	  </TABLE>
	  </form>

<?php
  if(isset($bProcesar))
  { 
  	$filtroDesde="";
  	if(strlen($bFDesde)>0)
  	{
  	  $filtroDesde=<<<mya
 and date(t.tic_fechahorainicio)>='$bFDesde' 
mya;
  	}
  	
  	$filtroHasta="";
  	if(strlen($bFHasta)>0)
  	{
  	  $filtroHasta=<<<mya
 and date(t.tic_fechahorainicio)<='$bFHasta' 
mya;
  	}
  	
  	/*
  	$filtroServicio="";
  	$filtroServicio1="";
  	if((strlen($bServicio)>0)&&($bServicio!=0))
  	{
  	  $filtroServicio=<<<mya
 and t.ser_id='$bServicio' 
mya;
	  $filtroServicio1=<<<mya
 and ser_id='$bServicio' 
mya;
  	}
  	
	$sqlServicio=<<<mya
  select ser_id,ser_nombre 
  from servicio 
  where ser_id>0
  $filtroServicio1 
  order by ser_nombre
mya;
	$rsServicio=&$conn->Execute($sqlServicio);
	$cuentaServicio=0;
    */
?>
<br />
<table class="tab" >
  <tr class="">
    <td>Soporte</td>
	<td># Tickets</td>
	<td>Promedio Evaluaciones</td>
  </tr>
  <?php
    $sqlSop=<<<mya
select t.usu_asignado,u.usu_nombre,avg(t.tic_valorencuesta) as promedio
from ticket t, usuario u
where u.usu_id=t.usu_asignado
  $filtroDesde 
  $filtroHasta 
group by t.usu_asignado,u.usu_nombre
order by promedio desc
mya;
	$rsSop=$conn->Execute($sqlSop);
	while(!$rsSop->EOF)
	{
		$ausuId=$rsSop->fields[0];
		$ausuNombre=$rsSop->fields[1];
		$aPromedio=$rsSop->fields[2];
  ?>
  <tr>
    <td><?=$ausuId?></td>
	<td><?=$ausuNombre?></td>
	<td><?=$aPromedio?></td>
  </tr>
  <?php
	  $rsSop->MoveNext();
	}
  ?>
  </tr>    
</table>
<?
  }
  
  buildsubmenufooter();		
?>