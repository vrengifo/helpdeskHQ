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
    
?>
	  <!-- buscar -->
	  <form name="form1" method="post" action="consultaItemUsuario.php">
	  
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
					  Items Asignados por Usuario
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
					  <TD valign=top nowrap>Usuario:</TD>
					  <TD valign=top nowrap>
					    <input name="bUsuario" type="text" id="bUsuario" value="<?=$bUsuario?>" >
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
 and date(ixu.usu_faudit)>='$bFDesde' 
mya;
  	}
  	
  	$filtroHasta="";
  	if(strlen($bFHasta)>0)
  	{
  	  $filtroHasta=<<<mya
 and date(ixu.usu_faudit)<='$bFHasta' 
mya;
  	}
  	
  	$filtroUsuario="";
  	if(strlen($bUsuario)>0)
  	{
  	  $filtroUsuario=<<<mya
  and ixu.usu_id like '$bUsuario%' 
mya;
  	}
  	
?>
<br />
<table class="tab" >
  <tr class="">
    <td>Usuario Id</td>
    <td>Fecha Asignaci&oacute;n</td>
	<td>Usuario</td>
	<td>Tipo Item</td>
	<td>Item</td>
	<td>PN</td>
	<td>SN</td>
  </tr>
  <?php
    $sqlSop=<<<mya
select ixu.usu_id,ixu.usu_faudit,
u.usu_nombre,
ti.tipite_nombre,i.ite_nombre,i.ite_pn,i.ite_sn
from itemxusuario ixu, usuario u, item i, tipoitem ti
where u.usu_id=ixu.usu_id and i.ite_id=ixu.ite_id
and ti.tipite_id=i.tipite_id
  $filtroDesde 
  $filtroHasta 
  $filtroUsuario 
order by ixu.usu_faudit,ixu.usu_id 
mya;
	$rsSop=$conn->Execute($sqlSop);
	while(!$rsSop->EOF)
	{
		$auxUsuId=$rsSop->fields[0];
		$auxFA=$rsSop->fields[1];
		$auxUsu=$rsSop->fields[2];
		$auxTItem=$rsSop->fields[3];
		$auxItem=$rsSop->fields[4];
		$auxPN=$rsSop->fields[5];
		$auxSN=$rsSop->fields[6];
		
  ?>
  <tr>
    <td><?=$auxUsuId?></td>
	<td><?=$auxFA?></td>
	<td><?=$auxUsu?></td>
	<td><?=$auxTItem?></td>
	<td><?=$auxItem?></td>
	<td><?=$auxPN?></td>
	<td><?=$auxSN?></td>
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