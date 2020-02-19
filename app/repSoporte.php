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
	  <form name="form1" method="post" action="repSoporte.php">
	  
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
					  Reporte de Soporte
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
					<TR valign=top bgcolor='#ffffff'>
					  <TD valign=top nowrap>Estado:</TD>
					  <TD valign=top nowrap>
					    <select name="bEstado" id="bEstado">
						  <?php
						    $sql=<<<mya
                            SELECT tipest_id,tipest_nombre from tipoestado 
                            order by tipest_id 
mya;
                            $rs=&$conn->Execute($sql);
                            while(!$rs->EOF)
                            {
                              $vId=$rs->fields[0];
                              $vTexto=$rs->fields[1];
                              ?>
                            <option value="<?=$vId?>" <?php if($bEstado==$vId) echo "selected"; ?>><?=$vTexto?></option>  
                              <?
                              $rs->MoveNext();
                            }
						  ?>
						</select>
					  </TD>
					</TR>
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
 and date(tic_fechahorainicio)>='$bFDesde' 
mya;
  	}
  	
  	$filtroHasta="";
  	if(strlen($bFHasta)>0)
  	{
  	  $filtroHasta=<<<mya
 and date(tic_fechahorainicio)<='$bFHasta' 
mya;
  	}
  	
  	$filtroEstado="";
  	if(strlen($bEstado)>0)
  	{
  	  $filtroEstado=<<<mya
 and tipest_id='$bEstado' 
mya;
  	}
  	
	$sqlEstado=<<<mya
  select tipest_id,tipest_nombre 
  from tipoestado 
  where tipest_id<>'' 
  $filtroEstado 
  order by tipest_nombre
mya;
	$rsEstado=&$conn->Execute($sqlEstado);
	$cuentaEstado=0;
    
?>
<br />
<table class="tab" >
  <tr class="">
    <td>Soporte</td>
	<td># Tickets</td>
	<?php
	$auxXMLCat="";
	while(!$rsEstado->EOF)
	{
		$arrEstado[$cuentaEstado]=$rsEstado->fields[0];
		$auxXMLCat.="<category label='".$rsEstado->fields[1]."' />";
	?>
	<td><?=$rsEstado->fields[1]?></td>
	<?php
	  $cuentaEstado++;
	  $rsEstado->MoveNext();
	}
	?>
  </tr>
  <?php
    $sqlSop=<<<mya
  select usu_asignado,count(tic_id) 
  from ticket 
  where usu_asignado<>'' 
  $filtroDesde 
  $filtroHasta 
  $filtroEstado 
  group by usu_asignado
  order by usu_asignado
mya;
	$rsSop=$conn->Execute($sqlSop);
	$auxXMLSerie="";
	while(!$rsSop->EOF)
	{
		$aSop=$rsSop->fields[0];
		$aTotSop=$rsSop->fields[1];
		
		$auxXMLSerie.="<dataset seriesName='".$aSop."'  showValues='1'>";
  ?>
  <tr>
    <td><?=$aSop?></td>
	<td><?=$aTotSop?></td>
	<?php
	  for($i=0;$i<$cuentaEstado;$i++)
	  {
	  	$auxTE=$arrEstado[$i];
	  	$sqlSE=<<<mya
	select count(tic_id) 
	from ticket
	where
	usu_asignado='$aSop' 
	and tipest_id='$auxTE' 
mya;
		$rsSE=$conn->Execute($sqlSE);
		
		$auxXMLSerie.="<set value='".$rsSE->fields[0]."' />";
	?>
	<td><?=$rsSE->fields[0]?></td>
	<?php
	  }
	?>
  </tr>
  <?php
	  $rsSop->MoveNext();
	  $auxXMLSerie.="</dataset>";
	}
  ?>
  </tr>    
</table>
<!-- Imagen -->
<SCRIPT LANGUAGE="Javascript" SRC="FusionCharts/FusionCharts.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
  function FC_Rendered(DOMId)
  {
	if (DOMId=="chart1Id")
	{
		window.alert("Look Ma! I am Column3D and I've finished loading and rendering.");
		return;
	}
  }
</SCRIPT>
		<div id="chartdiv">
			FusionCharts chartdiv
		</div>
		<script type="text/javascript">
		var myChart = new FusionCharts("FusionCharts/StackedColumn3D.swf", "myChartId", "900", "300", "0", "0");
<?php

  $cadXML=<<<mya
<chart palette='1' caption='Reporte de Soporte' shownames='1' showvalues='0'  numberPrefix='' showSum='1' decimals='0' overlapColumns='0'><categories>$auxXMLCat</categories>$auxXMLSerie</chart>
mya;

?>
		myChart.setDataXML("<?=$cadXML?>");
		myChart.render("chartdiv");
   </script>
<!-- Fin Imagen -->
<?

  }  
  buildsubmenufooter();		
?>