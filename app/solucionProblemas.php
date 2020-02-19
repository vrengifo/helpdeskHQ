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
	  <form name="form1" method="post" action="solucionProblemas.php">
	  
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
					  B&uacute;squeda:
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
					  <TD width="25%" valign=top nowrap>Ticket Nro:</TD>
					  <TD width="75%" valign=top nowrap>
					    <input name="bTicket" type="text" id="bTicket" value="<?=$bTicket?>" size="60">
					  </TD>
					  <TD rowspan="3" valign="middle" align="center">
					    <input name="bProcesar" type="submit" id="bProcesar" value="Procesar">
					  </TD>
					</TR>
					<TR valign=top bgcolor='#ffffff'>
					  <TD valign=top nowrap>Con palabras como:</TD>
					  <TD valign=top nowrap>
					    <input name="bPalabra" type="text" id="bPalabra" value="<?=$bbPalabra?>" size="60">
					  </TD>
					</TR>
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

  	include_once("class/c_ticket.php");
  	$oObj=new c_ticket($conn);
  	echo($oObj->adminAdminSolucionProblemas("#","solucionProblemas.php",$id_aplicacion,$id_subaplicacion,"#","ticketVer.php","Ver Soluci&oacute;n",$_REQUEST));
  }
  buildsubmenufooter();		
?>