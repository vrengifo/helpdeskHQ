<?php 
GLOBAL $gSQLMaxRows,$gSQLBlockRows;
	 
$gSQLMaxRows = 1000; // max no of rows to download
$gSQLBlockRows=20; // max no of rows per table block

function donde_estoy()
{
	global $id_aplicacion;
	global $id_subaplicacion;
	global $conn;
	$query='select mod_nombre,mod_formulario ';
	$query.=' from modulo ';
	$query.=' where mod_id='.$id_aplicacion;
	$rs = &$conn->Execute($query);
	
	if (!$rs||$rs->EOF) die(texterror('Su sesion de Usuario ha expirado. <a href="logout.php">Click para Ingresar al Sistema</a>'));
	
	echo "<a href=".trim($rs->fields[1])."?id_aplicacion=".$id_aplicacion.">".trim($rs->fields[0])."</a>";
	
	if($id_subaplicacion!="")
	{
		$query='select submod_nombre,submod_formulario ';
		$query.=' from submodulo ';
		$query.=' where submod_id='.$id_subaplicacion;
		$rs = &$conn->Execute($query);
		if (!$rs||$rs->EOF) die(texterror('Su sesi�n de Usuario ha expirado. <a href="logout.php">Click para Ingresar al Sistema</a>'));
		echo "&nbsp;&nbsp;--->&nbsp;&nbsp;<a href=".trim($rs->fields[1])."?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion.">".trim($rs->fields[0])."</a>";
	}
}

function build_table(&$rs,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';
					
	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	//else $docnt = true;
	$typearr = array();

	$ncols = $rs->FieldCount();
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC"><td nowrap class="table_hd">Item</td>';
	for ($i=0; $i < $ncols; $i++) {	
		$field = $rs->FetchField($i);
		if ($zheaderarray) $fname = $zheaderarray[$i];
		else $fname = htmlspecialchars($field->name);	
		$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 		//print " $field->name $field->type $typearr[$i] ";
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	print $hdr."\n\n";
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = isset($rs->fields[0]);
	while (!$rs->EOF) {
		
		$s .= "<TR valign=top bgcolor='#ffffff'>\n";
        $s .= "	<TD valign=top nowrap align=right>".($rows+1)."&nbsp;</TD>\n";
		
		for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields); 
			$i < $ncols; 
			$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {
			
			$type = $typearr[$i];
			switch($type) {
			case 'T':
				$s .= "	<TD valign=top nowrap>".$rs->UserTimeStamp($v,"Y-m-d h:i:s") ."&nbsp;</TD>\n";
			break;
			case 'D':
				$s .= "	<TD valign=top nowrap>".$rs->UserDate($v,"Y-m-d") ."&nbsp;</TD>\n";
			break;
			case 'I':
			case 'N':
				$s .= "	<TD valign=top nowrap align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars($v);
				$s .= "	<TD valign=top nowrap>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";
			  
			}
		} // for
		$s .= "</TR>\n\n";
			  
		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
	
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {
	
		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
			print $s . "</TABLE>\n\n";
			$s = $hdr;
		}
	} // while

	print $s."</TABLE>\n\n";

	if ($docnt) print "<H2>".$rows." Rows</H2>";
	
	return $rows;
 }

//pv
/*
	* funcion para mostrar en una tabla datos con checkboxs para eliminar y para modificar
	* retorna el nro de filas
	*parametros:

	* $rs: the recordset
	* $ztabhtml: the table tag attributes (optional)
	* $zheaderarray: contains the replacement strings for the headers (optional)
	* $titulo: titulo de la tabla
	* $icono: 
	* $width: ancho de la tabla
	* $htmlspecialchars=true
	* $check_nombre: nombre de los campos checkboxs
	* $url_modify: url de destino para modificar los datos
	* $total_hidden: nombre para el campo hidden que tiene el nro total de filas
	* $param_modify: parametros que deban pasarse al url_modify para ser concatenados 
*/
function build_table_admin(&$rs,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true,$check_nombre,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '			
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';
					
	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	//else $docnt = true;
	$typearr = array();

	$ncols = $rs->FieldCount();
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) {	
		$field = $rs->FetchField($i);
		if ($zheaderarray) $fname = $zheaderarray[$i];
		else $fname = htmlspecialchars($field->name);	
		$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 		//print " $field->name $field->type $typearr[$i] ";
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	print $hdr."\n\n";
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = isset($rs->fields[0]);
	//pv
	$vic=0;
	while (!$rs->EOF) {
		
		$s .= "<TR valign=top bgcolor='#ffffff'>\n";
		
		for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields); 
			$i < $ncols; 
			$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {
			
			$type = $typearr[$i];
			switch($type) {
			case 'T':
				$s .= "	<TD valign=top nowrap>".$rs->UserTimeStamp($v,"Y-m-d h:i:s") ."&nbsp;</TD>\n";
			break;
			case 'D':
				$s .= "	<TD valign=top nowrap>".$rs->UserDate($v,"Y-m-d") ."&nbsp;</TD>\n";
			break;
			case 'I':
			case 'N':
				//pv
				if(($i==0) || ($i==($ncols-1)))
				{
				  if($i==0)
				  {
					$s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$vic. "]' value='" .trim($v) ."'>&nbsp;</TD>\n";	
				  }
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";	
					}
					else
					{
						$vvalor=trim($v);
						$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';
						$s .= "	<TD valign=top nowrap> <a href='#1' onClick='".$caux."'>Click aqu�</a></TD>\n";
						//$s .= "	<TD valign=top nowrap> <a href='#' onClick='fOpenWindow('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')'>Modificar</a></TD>\n";
					}	
				 }
				}	 
				else
					$s .= "	<TD valign=top nowrap align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars($v);
				//pv
				if(($i==0) || ($i==($ncols-1)))
				{
				  if($i==0)
				  {
					$s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$vic. "]' value='" .trim($v) ."'>&nbsp;</TD>\n";	
				  }
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";	
					}
					else
					{
						$s .= "	<TD valign=top nowrap> <a href='#1' onClick=('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')>Click aqu�</a></TD>\n";
					}
				 }
				}
				else
					$s .= "	<TD valign=top nowrap>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";
			  
			}
		} // for
		$s .= "</TR>\n\n";
			  
		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
		$vic++;
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {
	
		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
			print $s . "</TABLE>\n\n";
			$s = $hdr;
		}
	} // while

	print $s."</TABLE>\n\n";
	print "<input type='hidden' name='$total_hidden' value='$vic'>\n";	
	if ($docnt) print "<H2>".$rows." Rows</H2>";
	
	return $rows;
 }

function build_table_adminCad(&$rs,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true,$check_nombre,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
{
  $s ='';$rows=0;$docnt = false;
  GLOBAL $gSQLMaxRows,$gSQLBlockRows;

  $cad=<<<va
			<TABLE WIDTH="$width" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR>
			  <TD>
				<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
				  <tr>
					<td nowrap>
					  <SPAN class="title" STYLE="cursor:default;">
						<img src="$icono" border=0 align=absmiddle HSPACE=2>
						<font color="#FFFFFF">
						  $titulo&nbsp;
						</font>
					  </SPAN>
					</td>
				  </tr>
				</table>
				<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
				  <TR><TD>
va;
	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	//else $docnt = true;
	$typearr = array();

	$ncols = $rs->FieldCount();
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) {	
		$field = $rs->FetchField($i);
		if ($zheaderarray) $fname = $zheaderarray[$i];
		else $fname = htmlspecialchars($field->name);	
		$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 		//print " $field->name $field->type $typearr[$i] ";
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	$cad.=$hdr."\n\n";
	
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = isset($rs->fields[0]);
	//pv
	$vic=0;
	while (!$rs->EOF) {
		
		$s .= "<TR valign=top bgcolor='#ffffff'>\n";
		
		for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields); 
			$i < $ncols; 
			$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {
			
			$type = $typearr[$i];
			switch($type) {
			case 'T':
				$s .= "	<TD valign=top nowrap>".$rs->UserTimeStamp($v,"Y-m-d h:i:s") ."&nbsp;</TD>\n";
			break;
			case 'D':
				$s .= "	<TD valign=top nowrap>".$rs->UserDate($v,"Y-m-d") ."&nbsp;</TD>\n";
			break;
			case 'I':
			case 'N':
				//pv
				if(($i==0) || ($i==($ncols-1)))
				{
				  if($i==0)
				  {
					$s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$vic. "]' value='" .trim($v) ."'>&nbsp;</TD>\n";	
				  }
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";	
					}
					else
					{
						$vvalor=trim($v);
						$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';
						$s .= "	<TD valign=top nowrap> <a href='#1' onClick='".$caux."'>Click aqu�</a></TD>\n";
						//$s .= "	<TD valign=top nowrap> <a href='#' onClick='fOpenWindow('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')'>Modificar</a></TD>\n";
					}	
				 }
				}	 
				else
					$s .= "	<TD valign=top nowrap align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars($v);
				//pv
				if(($i==0) || ($i==($ncols-1)))
				{
				  if($i==0)
				  {
					$s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$vic. "]' value='" .trim($v) ."'>&nbsp;</TD>\n";	
				  }
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";	
					}
					else
					{
						$s .= "	<TD valign=top nowrap> <a href='#1' onClick=('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')>Click aqu�</a></TD>\n";
					}
				 }
				}
				else
					$s .= "	<TD valign=top nowrap>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";
			  
			}
		} // for
		$s .= "</TR>\n\n";
			  
		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
		$vic++;
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {
	
		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
			$cad.= $s . "</TABLE>\n\n";
			$s = $hdr;
		}
	} // while

	$cad.=$s."</TABLE>\n\n";
	$cad.="<input type='hidden' name='$total_hidden' value='$vic'>\n";	
	if ($docnt) $cad.= "<H2>".$rows." Rows</H2>";
	
	return($cad);
 }
 
function build_table_adminCadArray(&$arreglo,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true,$check_nombre,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
{
  $s ='';$rows=0;$docnt = false;
  GLOBAL $gSQLMaxRows,$gSQLBlockRows;

  $cad=<<<va
			<TABLE WIDTH="$width" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR>
			  <TD>
				<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
				  <tr>
					<td nowrap>
					  <SPAN class="title" STYLE="cursor:default;">
						<img src="$icono" border=0 align=absmiddle HSPACE=2>
						<font color="#FFFFFF">
						  $titulo&nbsp;
						</font>
					  </SPAN>
					</td>
				  </tr>
				</table>
				<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
				  <TR><TD>
va;
	
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	$typearr = array();

	$ncols = count($zheaderarray);
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) 
	{	
		if ($zheaderarray) 
		  $fname = $zheaderarray[$i];
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	$cad.=$hdr."\n\n";
	
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = count($arreglo[0]);
	//pv
	for($i=0;$i<count($arreglo);$i++) 
	{
		
		$s .= "<TR valign=top bgcolor='#ffffff'>\n";
		
		for ($j=0;$j<count($arreglo[$i]);$j++) 
		{
		  //pv
		  $v=$arreglo[$i][$j];
		  if(($j==0) || ($j==($ncols-1)))
		  {
		    if($j==0)
		    {
			  $s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$i. "]' value='" .trim($v) ."'>&nbsp;</TD>\n";	
			}
			
			if($j==($ncols-1))
			{
			  if(!$ventana)//para mostrar en otra ventana o no
			  {
				$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";	
			  }
			  else
			  {
				$vvalor=trim($v);
				$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';
				$s .= "	<TD valign=top nowrap> <a href='#1' onClick='".$caux."'>Click aqu�</a></TD>\n";
			  }
			}
		  }
		  else
		    $s .= "	<TD valign=top nowrap align=left>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			
		} // for
		$s .= "</TR>\n\n";
	}
	$cad.=$s."</TABLE>\n\n";
	$cad.="<input type='hidden' name='$total_hidden' value='$i'>\n";	
	if ($docnt) $cad.= "<H2>".$rows." Rows</H2>";
	
	return($cad);
 }

/**
  * FUNCION PARA CONSTRUIR LA INTERFAZ DE GENERAPERIODOLIQUIDACION CON LAS AGENCIAS EN LAS QUE YA EXISTE EL PERIODO CHEQUEADAS
  *
  * @param unknown_type $arreglo
  * @param unknown_type $ztabhtml
  * @param unknown_type $zheaderarray
  * @param unknown_type $titulo
  * @param unknown_type $icono
  * @param unknown_type $width
  * @param unknown_type $htmlspecialchars
  * @param unknown_type $check_nombre
  * @param unknown_type $url_modify
  * @param unknown_type $param_modify
  * @param unknown_type $total_hidden
  * @param unknown_type $ventana
  * @param unknown_type $title
  * @param unknown_type $par1
  * @param unknown_type $par2
  * @return unknown
  */
 function build_table_adminCadArrayGPL(&$arreglo,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true,$check_nombre,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
{
	global $conn;
  $s ='';$rows=0;$docnt = false;
  GLOBAL $gSQLMaxRows,$gSQLBlockRows;

  $cad=<<<va
			<TABLE WIDTH="$width" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR>
			  <TD>
				<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
				  <tr>
					<td nowrap>
					  <SPAN class="title" STYLE="cursor:default;">
						<img src="$icono" border=0 align=absmiddle HSPACE=2>
						<font color="#FFFFFF">
						  $titulo&nbsp;
						</font>
					  </SPAN>
					</td>
				  </tr>
				</table>
				<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
				  <TR><TD>
va;
	
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	$typearr = array();

	$ncols = count($zheaderarray);
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) 
	{	
		if ($zheaderarray) 
		  $fname = $zheaderarray[$i];
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	$cad.=$hdr."\n\n";
	
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = count($arreglo[0]);
	//pv
	for($i=0;$i<count($arreglo)-1;$i++) 
	{
		
		$s .= "<TR valign=top bgcolor='#ffffff'>\n";
		
		for ($j=0;$j<count($arreglo[$i])-1;$j++) 
		{
		  //pv
		  $v=$arreglo[$i][$j];
		  if(($j==0) || ($j==($ncols-1)))
		  {
		    if($j==0)
		    {
		    	///para chequear las agencias que ya tienen este periodo
		    	$anio=substr($arreglo[$i][2],0,4);
		    	$mes=substr($arreglo[$i][2],4,2);
		    	$anio=$anio-1900;

		$sql=<<<cad
		SELECT age_id 
		from periodopago
		where age_id='$v' and substring(convert(varchar,perliq_hasta),1,5)='$anio$mes'
cad;
	//echo "<hr>$sql<hr>";
	$rs = &$conn->Execute($sql);
	$check_agencia="";
	 if ($rs->EOF) 
	  $check_agencia.="";
	else
	$check_agencia.="checked";
  	
			  $s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$i. "]' value='" .trim($v) ."' ".$check_agencia.">&nbsp;</TD>\n";	
			}
			
			if($j==($ncols-1))
			{
			  if(!$ventana)//para mostrar en otra ventana o no
			  {
				$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";	
			  }
			  else
			  {
				$vvalor=trim($v);
				$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';
				$s .= "	<TD valign=top nowrap> <a href='#1' onClick='".$caux."'>Click aqu�</a></TD>\n";
			  }
			}
		  }
		  else
		    $s .= "	<TD valign=top nowrap align=left>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			
		} // for
		$s .= "</TR>\n\n";
	}
	$cad.=$s."</TABLE>\n\n";
	$cad.="<input type='hidden' name='$total_hidden' value='$i'>\n";	
	if ($docnt) $cad.= "<H2>".$rows." Rows</H2>";
	
	return($cad);
 }
  
 
 /**
  * FUNCION PARA CONSTRUIR UN DETALLE Y EN EL CAMPO FINAL COLOCAR UNA ACTUALIZACION DE UN CHECKBOX POR MEDIO DE UN HREF
  *
  * @param unknown_type $arreglo
  * @param unknown_type $ztabhtml
  * @param unknown_type $zheaderarray
  * @param unknown_type $titulo
  * @param unknown_type $icono
  * @param unknown_type $width
  * @param unknown_type $htmlspecialchars
  * @param unknown_type $check_nombre
  * @param unknown_type $url_modify
  * @param unknown_type $param_modify
  * @param unknown_type $total_hidden
  * @param unknown_type $ventana
  * @param unknown_type $title
  * @param unknown_type $par1
  * @param unknown_type $par2
  * @return unknown
  */
 function build_table_adminCadArray_ACTUALIZARTXT(&$arreglo,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true,$check_nombre,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
{
  $s ='';$rows=0;$docnt = false;
  GLOBAL $gSQLMaxRows,$gSQLBlockRows;

  $cad=<<<va
			<TABLE WIDTH="$width" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR>
			  <TD>
				<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
				  <tr>
					<td nowrap>
					  <SPAN class="title" STYLE="cursor:default;">
						<img src="$icono" border=0 align=absmiddle HSPACE=2>
						<font color="#FFFFFF">
						  $titulo&nbsp;
						</font>
					  </SPAN>
					</td>
				  </tr>
				</table>
				<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
				  <TR><TD>
va;
	
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	$typearr = array();

	$ncols = count($zheaderarray);
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) 
	{	
		if ($zheaderarray) 
		  $fname = $zheaderarray[$i];
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	$cad.=$hdr."\n\n";
	
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = count($arreglo[0]);
	//pv
	for($i=0;$i<count($arreglo);$i++) 
	{
		
		$s .= "<TR valign=top bgcolor='#ffffff'>\n";
		
		for ($j=0;$j<count($arreglo[$i]);$j++) 
		{
		  //pv
		  $v=$arreglo[$i][$j];
		  if(($j==0) || ($j==($ncols-1)))
		  {
		    if($j==0)
		    {
			  $s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$i. "]' value='" .trim($v) ."'>&nbsp;</TD>\n";	
			}
			
			if($j==($ncols-1))
			{
			 /* if(!$ventana)//para mostrar en otra ventana o no
			  {
				$s .= "	<TD valign=top nowrap> <a href='" .$url_modify."'>Click aqu�</a></TD>\n";	
			  }
			  else
			  {
				$vvalor=trim($v);
				$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';*/
				$s .= "	<TD valign=top nowrap> <input type='checkbox' name='act[" .$i. "]' value='" .trim($v) ."' checked>&nbsp;</TD>\n";	
			  //}
			}
		  }
		  else
		    $s .= "	<TD valign=top nowrap align=left>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			
		} // for
		$s .= "</TR>\n\n";
	}
	$cad.=$s."</TABLE>\n\n";
	$cad.="<input type='hidden' name='$total_hidden' value='$i'>\n";	
	if ($docnt) $cad.= "<H2>".$rows." Rows</H2>";
	
	return($cad);
 }
 
//FUNCION PARA CONSTRUIR TABLA CON COLUMNA DE ELIMINAR Y MODIFICAR OPCIONALES
function build_table_adminCadArrayOpcional(&$arreglo,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true,$check_nombre,$url_modify,$param_modify,$total_hidden,$ColDel=True,$ColMod=True,$ventana=0,$title="",$par1=0,$par2=0)
{
  $s ='';$rows=0;$docnt = false;
  GLOBAL $gSQLMaxRows,$gSQLBlockRows;

  $cad=<<<va
			<TABLE WIDTH="$width" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR>
			  <TD>
				<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
				  <tr>
					<td nowrap>
					  <SPAN class="title" STYLE="cursor:default;">
						<img src="$icono" border=0 align=absmiddle HSPACE=2>
						<font color="#FFFFFF">
						  $titulo&nbsp;
						</font>
					  </SPAN>
					</td>
				  </tr>
				</table>
				<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
				  <TR><TD>
va;
	
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	$typearr = array();

	$ncols = count($zheaderarray);
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) 
	{	
		if ($zheaderarray) 
		  $fname = $zheaderarray[$i];
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	$cad.=$hdr."\n\n";
	
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = count($arreglo[0]);
	//pv
	for($i=0;$i<count($arreglo);$i++) 
	{
		
		$s .= "<TR valign=top bgcolor='#ffffff'>\n";
		
		for ($j=0;$j<count($arreglo[$i]);$j++) 
		{
		  //pv
		  $v=$arreglo[$i][$j];
		  if(($j==0 && $ColDel ) || ($j==($ncols-1) && $ColMod))
		  {
		  	
		  	if($j==0)
		    {
			  $s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$i. "]' value='" .trim($v) ."'>&nbsp;</TD>\n";	
			}
			
		  	if($j==0)
		    {
			  $s .=  "	<TD valign=top nowrap align=left>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			}
			
			if($j==($ncols-1))
			{
			  if(!$ventana)//para mostrar en otra ventana o no
			  {
				$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";	
			  }
			  else
			  {
				$vvalor=trim($v);
				$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';
				$s .= "	<TD valign=top nowrap> <a href='#1' onClick='".$caux."'>Click aqu�</a></TD>\n";
			  }
			}

		  }
		  else
		    $s .= "	<TD valign=top nowrap align=left>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			
		} // for
		$s .= "</TR>\n\n";
	}
	$cad.=$s."</TABLE>\n\n";
	$cad.="<input type='hidden' name='$total_hidden' value='$i'>\n";	
	if ($docnt) $cad.= "<H2>".$rows." Rows</H2>";
	
	return($cad);
 }

 //FUNCION PARA CONSTRUIR TABLA CON CHECKBOX SELECCIONADOS

function build_table_adminCadArray_checked(&$arreglo,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true,$check_nombre,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
{
  $s ='';$rows=0;$docnt = false;
  GLOBAL $gSQLMaxRows,$gSQLBlockRows;

  $cad=<<<va
			<TABLE WIDTH="$width" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR>
			  <TD>
				<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
				  <tr>
					<td nowrap>
					  <SPAN class="title" STYLE="cursor:default;">
						<img src="$icono" border=0 align=absmiddle HSPACE=2>
						<font color="#FFFFFF">
						  $titulo&nbsp;
						</font>
					  </SPAN>
					</td>
				  </tr>
				</table>
				<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside">
				  <TR><TD>
va;
	
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	$typearr = array();

	$ncols = count($zheaderarray);
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) 
	{	
		if ($zheaderarray) 
		  $fname = $zheaderarray[$i];
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	$cad.=$hdr."\n\n";
	
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = count($arreglo[0]);
	//pv
	for($i=0;$i<count($arreglo);$i++) 
	{
		
		$s .= "<TR valign=top bgcolor='#ffffff'>\n";
		
		for ($j=0;$j<count($arreglo[$i]);$j++) 
		{
		  //pv
		  $v=$arreglo[$i][$j];
		  if(($j==0) || ($j==($ncols-1)))
		  {
		    if($j==0)
		    {
			  $s .= "	<TD valign=top nowrap> <input type='checkbox' name='" .$check_nombre ."[" .$i. "]' value='" .trim($v) ."' checked>&nbsp;</TD>\n";	
			}
			
			if($j==($ncols-1))
			{
			  if(!$ventana)//para mostrar en otra ventana o no
			  {
				$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";	
			  }
			  else
			  {
				$vvalor=trim($v);
				$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';
				$s .= "	<TD valign=top nowrap> <a href='#1' onClick='".$caux."'>Click aqu�</a></TD>\n";
			  }
			}
		  }
		  else
		    $s .= "	<TD valign=top nowrap align=left>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			
		} // for
		$s .= "</TR>\n\n";
	}
	$cad.=$s."</TABLE>\n\n";
	$cad.="<input type='hidden' name='$total_hidden' value='$i'>\n";	
	if ($docnt) $cad.= "<H2>".$rows." Rows</H2>";
	
	return($cad);
 }


//
function build_table_sindel(&$rs,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';

	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	//else $docnt = true;
	$typearr = array();

	$ncols = $rs->FieldCount();
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) {
		$field = $rs->FetchField($i);
		if ($zheaderarray) $fname = $zheaderarray[$i];
		else $fname = htmlspecialchars($field->name);
		$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 		//print " $field->name $field->type $typearr[$i] ";

		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	print $hdr."\n\n";
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = isset($rs->fields[0]);
	//pv
	$vic=0;
	while (!$rs->EOF) {

		$s .= "<TR valign=top bgcolor='#ffffff'>\n";

		for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
			$i < $ncols;
			$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {

			$type = $typearr[$i];
			switch($type) {
			case 'T':
				$s .= "	<TD valign=top nowrap>".$rs->UserTimeStamp($v,"Y-m-d h:i:s") ."&nbsp;</TD>\n";
			break;
			case 'D':
				$s .= "	<TD valign=top nowrap>".$rs->UserDate($v,"Y-m-d") ."&nbsp;</TD>\n";
			break;
			case 'I':
			case 'N':
				//pv
				if($i==($ncols-1))
				{
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";
					}
					else
					{
						$vvalor=trim($v);
						$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';
						$s .= "	<TD valign=top nowrap> <a href='#1' onClick='".$caux."'>Click aqu�</a></TD>\n";
						//$s .= "	<TD valign=top nowrap> <a href='#' onClick='fOpenWindow('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')'>Modificar</a></TD>\n";
					}
				 }
				}
				else
					$s .= "	<TD valign=top nowrap align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";

			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars($v);
				//pv
				if($i==($ncols-1))
				{
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";
					}
					else
					{
						$s .= "	<TD valign=top nowrap> <a href='#1' onClick=('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')>Click aqu�</a></TD>\n";
					}
				 }
				}
				else
					$s .= "	<TD valign=top nowrap>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";

			}
		} // for
		$s .= "</TR>\n\n";

		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
		$vic++;
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {

		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
			print $s . "</TABLE>\n\n";
			$s = $hdr;
		}
	} // while

	print $s."</TABLE>\n\n";
	print "<input type='hidden' name='$total_hidden' value='$vic'>\n";
	if ($docnt) print "<H2>".$rows." Rows</H2>";

	return $rows;
 }

function build_table_sindelCad(&$rs,$ztabhtml=false,$zheaderarray=false,$titulo,$icono,$width,$htmlspecialchars=true,$url_modify,$param_modify,$total_hidden,$ventana=0,$title="",$par1=0,$par2=0)
{
  $s ='';$rows=0;$docnt = false;
  GLOBAL $gSQLMaxRows,$gSQLBlockRows;

  $cad=<<<va
			<TABLE WIDTH="$width" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			  <TR>
			    <TD>
				  <table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
				    <tr>
				      <td nowrap>
				        <SPAN class="title" STYLE="cursor:default;">
				          <img src="$icono" border=0 align=absmiddle HSPACE=2>
				          <font color="#FFFFFF">
				            $titulo&nbsp;
				          </font>
				        </SPAN>
					  </td>
					</tr>
				  </table>
				  <TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>
va;

	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	//else $docnt = true;
	$typearr = array();

	$ncols = $rs->FieldCount();
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	$hdr .=	'<TR BGCOLOR="#CCCCCC">';
	for ($i=0; $i < $ncols; $i++) {
		$field = $rs->FetchField($i);
		if ($zheaderarray) $fname = $zheaderarray[$i];
		else $fname = htmlspecialchars($field->name);
		$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 		//print " $field->name $field->type $typearr[$i] ";

		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<td nowrap class='table_hd'>$fname</td>";
	}

	$cad.= $hdr."\n\n";
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = isset($rs->fields[0]);
	//pv
	$vic=0;
	while (!$rs->EOF) {

		$s .= "<TR valign=top bgcolor='#ffffff'>\n";

		for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
			$i < $ncols;
			$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {

			$type = $typearr[$i];
			switch($type) {
			case 'T':
				$s .= "	<TD valign=top nowrap>".$rs->UserTimeStamp($v,"Y-m-d h:i:s") ."&nbsp;</TD>\n";
			break;
			case 'D':
				$s .= "	<TD valign=top nowrap>".$rs->UserDate($v,"Y-m-d") ."&nbsp;</TD>\n";
			break;
			case 'I':
			case 'N':
				//pv
				if($i==($ncols-1))
				{
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";
					}
					else
					{
						$vvalor=trim($v);
						$caux='fOpenWindow("'.$url_modify.$param_modify.'&id='.$vvalor.'","'.$title.'","'.$par1.'","'.$par2.'")';
						$s .= "	<TD valign=top nowrap> <a href='#1' onClick='".$caux."'>Click aqu�</a></TD>\n";
						//$s .= "	<TD valign=top nowrap> <a href='#' onClick='fOpenWindow('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')'>Modificar</a></TD>\n";
					}
				 }
				}
				else
					$s .= "	<TD valign=top nowrap align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";

			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars($v);
				//pv
				if($i==($ncols-1))
				{
				  if($i==($ncols-1))
				  {
					if(!$ventana)//para mostrar en otra ventana o no
					{
						$s .= "	<TD valign=top nowrap> <a href='" .$url_modify.$param_modify."&id=" .trim($v)."'>Click aqu�</a></TD>\n";
					}
					else
					{
						$s .= "	<TD valign=top nowrap> <a href='#1' onClick=fOpenWindow('" .$url_modify.$param_modify."&id=" .trim($v)."','".$title."','".$par1."','".$par2."')>Click aqu�</a></TD>\n";
					}
				 }
				}
				else
					$s .= "	<TD valign=top nowrap>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";

			}
		} // for
		$s .= "</TR>\n\n";

		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
		$vic++;
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {

		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
			$cad.=$s . "</TABLE>\n\n";
			$s = $hdr;
		}
	} // while

	$cad.=$s."</TABLE>\n\n";
	$cad.= "<input type='hidden' name='$total_hidden' value='$vic'>\n";
	if ($docnt) print "<H2>".$rows." Rows</H2>";

	return($cad);
 } 
 
 
/*
	* funcion para mostrar en una tabla datos para anadir datos
*/
function build_add($con,$ztabhtml=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$campo,$campo_hidden)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '			
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';					
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	
	$ncols=2;//solo dos columnas, la una para la etiqueta y la otra el campo
	$nfils=count($campo);
	$campo_form='';
	$campo_base='';
	$campo_extra='';
	for ($i=0; $i < $nfils; $i++) 
	{	
		$hdr .=	'<TR BGCOLOR="#CCCCCC">';	
		//etiqueta
		$eti=$campo[$i]["etiqueta"];
		$hdr .= "<td nowrap class='table_hd'>$eti</td>";
		//campo
		$c_nombre=$campo[$i]["nombre"];
		$c_tipo=$campo[$i]["tipo_campo"];
		$c_sql=$campo[$i]["sql"];
		$c_valor=$campo[$i]["valor"];
		$campito="";		
		switch($c_tipo)
		{
			case "text":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "nada":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="0" >';
						break;
			case "date":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//link para fecha
						$resto='<a href="javascript:show_calendar(';
						$resto.="'form1.".$c_nombre."');"; 
						$resto.='" onmouseover="window.status=';
						$resto.="'Date Picker';return true;";
						$resto.='"> <img src="images/big_calendar.gif" width=24 height=24 border=0>'; 
                      	$resto.='</a>';
						$campito.=$resto;
						break;						
			case "hidden":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//recuperar los datos q se van a mostrar del sql
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  if (!$rs->EOF) 
						  {
							$codigo=$rs->fields[0];
							$descripcion=$rs->fields[1];
							$resto.=$codigo.' - '.$descripcion;		
						  }
						}  						
						$campito.=$resto;						
						break;						
			case "area":
						$campito='<textarea name="'.$c_nombre.'" >'.$c_valor.'</textarea>';
						break;						
			case "password":
						$campito='<input type="password" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "select":
						$campito="<select name='".$c_nombre."' >";
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.='<option value="'.$valor.'">'.$texto.'</option>';	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto.'</select>';
						break;			
		}// fin switch
		$hdr .= "<td nowrap class='table_hd'>$campito</td></tr> \n\n";
		//$campo_form.=$c_nombre."|";
		$campo_base.=$c_nombre."|";
	}

	print $hdr."\n\n </TABLE>\n\n";//tabla de mis datos

	print "</TABLE>\n\n";
	print "</TABLE>\n\n";
	
	//campos hidden
	$nfils=count($campo_hidden);
	for ($i=0; $i < $nfils; $i++) 
	{	
		$c_nombre=$campo_hidden[$i]["nombre"];
		$c_valor=$campo_hidden[$i]["valor"];
		$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
		print $campito."\n";
		$campo_extra.=$c_nombre."|";		
	}
	
	//nombre de todos los campos usados en la forma, van concatenados y pasan como variable hidden al destino
	//el nombre usado es campo_form y el separador es |
	$campo_base=substr($campo_base,0,(strlen($campo_base)-1));
	print '<input type="hidden" name="campo_base" value="'.$campo_base.'" >';
	$campo_extra=substr($campo_extra,0,(strlen($campo_extra)-1));
	print '<input type="hidden" name="campo_extra" value="'.$campo_extra.'" >';		
}

function build_addCad($con,$ztabhtml=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$campo,$campo_hidden)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	$cad=<<<va
			<TABLE WIDTH="$width" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR>
			  <TD>
				<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
				  <tr>
					<td nowrap>
					  <SPAN class="title" STYLE="cursor:default;">
					    <img src="$icono" border=0 align=absmiddle HSPACE=2>
					    <font color="#FFFFFF">$titulo&nbsp;</font>
					  </SPAN>
					</td>
				  </tr>
				</table>
				<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>
va;
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	
	$ncols=2;//solo dos columnas, la una para la etiqueta y la otra el campo
	$nfils=count($campo);
	$campo_form='';
	$campo_base='';
	$campo_extra='';
	
	
	for ($i=0; $i < $nfils; $i++) 
	{	
		$hdr .=	'<TR BGCOLOR="#CCCCCC">';	
		//etiqueta
		$eti=$campo[$i]["etiqueta"];
		$hdr .= "<td nowrap class='table_hd'>$eti</td>";
		//campo
		$c_nombre=$campo[$i]["nombre"];
		$c_tipo=$campo[$i]["tipo_campo"];
		$c_sql=$campo[$i]["sql"];
		$c_valor=$campo[$i]["valor"];
		$c_js=$campo[$i]["js"];
		$campito="";		
		switch($c_tipo)
		{
			case "text":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" '.$c_js.' >';
						break;
			case "file":
						$campito='<input type="file" name="'.$c_nombre.'" value="'.$c_valor.'" '.$c_js.' >';
						break;			
						
//		para el formulario de FACTORES,	para el nombre e ingresar datos numericos
			case "text_nombre":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" '.$c_js.' size="40" >';
						break;
			case "text_num":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" '.$c_js.' size="10" maxlength="10" >';
						break;
			case "nada":
						$campito=$c_valor;
						break;
			case "date":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" '.$c_js.' >';
						//link para fecha
						$resto='<a href="javascript:show_calendar(';
						$resto.="'form1.".$c_nombre."');"; 
						$resto.='" onmouseover="window.status=';
						$resto.="'Date Picker';return true;";
						$resto.='"> <img src="images/big_calendar.gif" width=24 height=24 border=0>'; 
                      	$resto.='</a>';
						$campito.=$resto;
						break;						
			case "date+boton":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" '.$c_js.' >';
						//link para fecha
						$resto='<a href="javascript:show_calendar(';
						$resto.="'form1.".$c_nombre."');"; 
						$resto.='" onmouseover="window.status=';
						$resto.="'Date Picker';return true;";
						$resto.='"> <img src="images/big_calendar.gif" width=24 height=24 border=0>'; 
                      	$resto.='</a>';
                      	//Bot�n de Refrescar
                      	$resto.='&nbsp<input type="button" name="bRefrescar" value="Refrescar" onclick="submit();">';
						$campito.=$resto;
						break;			
			case "hidden":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//recuperar los datos q se van a mostrar del sql
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  if (!$rs->EOF) 
						  {
							$codigo=$rs->fields[0];
							$descripcion=$rs->fields[1];
							$resto.=$codigo.' - '.$descripcion;		
						  }
						}  						
						$campito.=$resto;						
						break;						
			case "area":
						$campito='<textarea name="'.$c_nombre.'" '.$c_js.' cols="40" rows="5">'.$c_valor.'</textarea>';
						break;						
			case "password":
						$campito='<input type="password" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "select":
						$campito='<select name="'.$c_nombre.'" '.$c_js.' >';
						$resto='';
						
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							
							

							$seleccionado="";
							if($c_valor==$valor)
							  $seleccionado=" selected ";
							    
							
							$resto.='<option value="'.$valor.'" '.$seleccionado.'>'.$texto.'</option>';	

							$rs->MoveNext();
						  }
						}
						$campito.=$resto.'</select>';
						break;


//						para crear los campos select con el primer elemento vacio
			case "selectAux_variables":
						$campito='<select name="'.$c_nombre.'" '.$c_js.' >';
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  $sql_VD=explode("|",$c_sql);
						  $rs = &$con->Execute($sql_VD[0]);
						$resto.='<option value="" selected>- - - -</option>';
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor="V.".$rs->fields[0];
							$texto=$rs->fields[1];
							
							$seleccionado="";
							if($c_valor==$valor)
							  $seleccionado=" selected ";
							    
							$resto.='<option value="'.$valor.'" '.$seleccionado.'>'.$texto.'</option>';	
							$rs->MoveNext();
						  }
						  
						$rs = &$con->Execute($sql_VD[1]);
//						$resto.='<option value="" > </option>';
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor="D.".$rs->fields[0];
							$texto=$rs->fields[1];
							
							//echo "<hr>$c_valor : $valor<hr>";

							$seleccionado="";
							if($c_valor==$valor)
							  $seleccionado=" selected ";
							    
							
							$resto.='<option value="'.$valor.'" '.$seleccionado.'>'.$texto.'</option>';	
							$rs->MoveNext();
						  }
						  
						$rs = &$con->Execute($sql_VD[2]);
//						$resto.='<option value="" > </option>';
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor="Q.".$rs->fields[0];
							$texto=$rs->fields[1];
							
							//echo "<hr>$c_valor : $valor<hr>";

							$seleccionado="";
							if($c_valor==$valor)
							  $seleccionado=" selected ";
							    
							
							$resto.='<option value="'.$valor.'" '.$seleccionado.'>'.$texto.'</option>';	
							$rs->MoveNext();
						  }
						}
						$campito.=$resto.'</select>';
						break;
				case "selectAux":
						$campito='<select name="'.$c_nombre.'" '.$c_js.' >';
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
//						  
						  $rs = &$con->Execute($c_sql);
						$resto.='<option value="" selected>- - - -</option>';
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							
							//echo "<hr>$c_valor : $valor<hr>";

							$seleccionado="";
							if($c_valor==$valor)
							  $seleccionado=" selected ";
							    
							
							$resto.='<option value="'.$valor.'" '.$seleccionado.'>'.$texto.'</option>';	
							$rs->MoveNext();
						  }
						}
						$campito.=$resto.'</select>';
						break;
						
			case "selectAll":
						$campito='<select name="'.$c_nombre.'" '.$c_js.' >';
						
						$cadSelected=" selected";
						
						if($c_valor=="*")
						  $cadSelectedAll=$cadSelected;
						else 
						  $cadSelectedAll="";  
						$resto='<option value="*" '.$cadSelectedAll.'>Todos</option>';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=trim($rs->fields[0]);
							$texto=$rs->fields[1];
							
							if($valor==$c_valor)
							  $cadSelectedData=$cadSelected;
							else 
							  $cadSelectedData="";  
							
							$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	
							$rs->MoveNext();
						  }
						}
						$campito.=$resto.'</select>';
						break;
			case "selectAllVacio":
						$campito='<select name="'.$c_nombre.'" '.$c_js.' >';
						
						$cadSelected=" selected";
						
						if($c_valor=="")
						  $cadSelectedAll=$cadSelected;
						else 
						  $cadSelectedAll="";  
						$resto='<option value="" '.$cadSelectedAll.'>Todos</option>';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=trim($rs->fields[0]);
							$texto=$rs->fields[1];
							
							if($valor==$c_valor)
							  $cadSelectedData=$cadSelected;
							else 
							  $cadSelectedData="";  
							
							$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	
							$rs->MoveNext();
						  }
						}
						$campito.=$resto.'</select>';
						break;						
			case "selectMeses":
						$campito='<select name="'.$c_nombre.'" '.$c_js.' >';
						$resto='';
						
						$cadSelectedData="";
						if ($c_valor=="01")
							$cadSelectedData=" selected";
						

						$valor="01";
						$texto="ENERO";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						$cadSelectedData="";
						if ($c_valor=="02")
							$cadSelectedData=" selected";

						
						$valor="02";
						$texto="FEBRERO";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						$cadSelectedData="";
						if ($c_valor=="03")
							$cadSelectedData=" selected";
			
						$valor="03";
						$texto="MARZO";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						$cadSelectedData="";
						if ($c_valor=="04")
							$cadSelectedData=" selected";

						$valor="04";
						$texto="ABRIL";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						$cadSelectedData="";
						if ($c_valor=="05")
							$cadSelectedData=" selected";

						$valor="05";
						$texto="MAYO";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						
						$cadSelectedData="";
						if ($c_valor=="06")
							$cadSelectedData=" selected";

						$valor="06";
						$texto="JUNIO";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						$cadSelectedData="";
						if ($c_valor=="07")
							$cadSelectedData=" selected";

						$valor="07";
						$texto="JULIO";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						
						$cadSelectedData="";
						if ($c_valor=="08")
							$cadSelectedData=" selected";
						
						$valor="08";
						$texto="AGOSTO";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						$cadSelectedData="";
						if ($c_valor=="09")
							$cadSelectedData=" selected";

						$valor="09";
						$texto="SEPTIEMBRE";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						$cadSelectedData="";
						if ($c_valor=="10")
							$cadSelectedData=" selected";

						$valor="10";
						$texto="OCTUBRE";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						$cadSelectedData="";
						if ($c_valor=="11")
							$cadSelectedData=" selected";

						$valor="11";
						$texto="NOVIEMBRE";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	
						
						$cadSelectedData="";
						if ($c_valor=="12")
							$cadSelectedData=" selected";

						$valor="12";
						$texto="DICIEMBRE";	
						$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	

						
						  						
						$campito.=$resto.'</select>';
						break;

			case "selectAnio":
						$campito='<select name="'.$c_nombre.'" '.$c_js.' >';
						$resto='';

						$aa=date(Y);
						///5 a�os atras
						for($i=5;$i>0;$i--){
							
							$cadSelectedData="";
							if ($c_valor==$aa-$i)
								$cadSelectedData=" selected";
					
							$valor=$aa-$i;
							$texto=$aa-$i;	
							$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	
						}
						
						//a�o presente y uno despues
						for($j=0;$j<2;$j++){
							
							$cadSelectedData="";
							if ($c_valor==$aa+$j)
								$cadSelectedData=" selected";
					
							$valor=$aa+$j;
							$texto=$aa+$j;	
							$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	
						}
						
						$campito.=$resto.'</select>';
						break;
			case "selectAll_Texto":
						$campito='<select name="'.$c_nombre.'" '.$c_js.' >';
						
						$cadSelected=" selected";
						
						if($c_valor=="*")
						  $cadSelectedAll=$cadSelected;
						else 
						  $cadSelectedAll="";  
						$resto='<option value="*" '.$cadSelectedAll.'>-</option>';
						if(strlen($c_sql)>0)
						{						  
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=trim($rs->fields[0]);
							$texto=$rs->fields[1];
							
							if($valor==$c_valor)
							  $cadSelectedData=$cadSelected;
							else 
							  $cadSelectedData="";  
							
							$resto.='<option value="'.$valor.'" '.$cadSelectedData.'>'.$texto.'</option>';	
							$rs->MoveNext();
						  }
						}
						$campito.=$resto.'</select>';
						
						$patron="^[A-Z].";
						if(!(eregi($patron,$c_valor)))
						  $auxValor=$c_valor;
						else 
						  $auxValor="";
												    
						//$auxValor=$c_valor;
						$campito.='&nbsp; <input type="text" name="'.$c_nombre.'_T" value="'.$auxValor.'" >';
						break;
			case "checkbox":
						$campito='';
						$resto='';
						
						if(strlen($c_sql)>0)
						{						  
						  $rs = &$con->Execute($c_sql);
						  //recuperar datos del recordset
						  $cont=0;	  
						  while (!$rs->EOF) 
						  {
							$valor=trim($rs->fields[0]);
							$texto=$rs->fields[1];
							
							$seleccionado="";
							
							if(strlen($c_valor)>0)
							{
							  $c_valor=str_replace("'","",$c_valor);
							  $resPos=strpos(trim($c_valor),$valor);
							  if($resPos===false)
							  {
							    $seleccionado="";
							  }
							  else 
							  {
							    $seleccionado=" checked ";
							  }
							}
							
							$resto.='<input type="checkbox" name="'.$c_nombre.'['.$cont++.']" value="'.$valor.'" '.$seleccionado.'>'.$texto.'<br>';	

							$rs->MoveNext();
						  }
						  $resto.='<input type="hidden" name="'.$c_nombre.'Hidden" value="'.$cont.'" >';
						}
						$campito.=$resto;
						
						break;

		}// fin switch
		$hdr .= "<td nowrap class='table_hd'>$campito</td></tr> \n\n";
		//$campo_form.=$c_nombre."|";
		if($c_tipo!="nada")
		  $campo_base.=$c_nombre."|";
	}

	$cad.=$hdr."\n\n </TABLE>\n\n";//tabla de mis datos

	$cad.= "</TABLE>\n\n";
	$cad.= "</TABLE>\n\n";
	
	//campos hidden
	$nfils=count($campo_hidden);
	for ($i=0; $i < $nfils; $i++) 
	{	
		$c_nombre=$campo_hidden[$i]["nombre"];
		$c_valor=$campo_hidden[$i]["valor"];
		$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
		$cad.= $campito."\n";
		$campo_extra.=$c_nombre."|";		
	}
	
	//nombre de todos los campos usados en la forma, van concatenados y pasan como variable hidden al destino
	//el nombre usado es campo_form y el separador es |
	$campo_base=substr($campo_base,0,(strlen($campo_base)-1));
	$cad.= '<input type="hidden" name="campo_base" value="'.$campo_base.'" >';
	$campo_extra=substr($campo_extra,0,(strlen($campo_extra)-1));
	$cad.= '<input type="hidden" name="campo_extra" value="'.$campo_extra.'" >';		
	return($cad);
}


/*
	* funcion para mostrar en una tabla datos para anadir datos
	* el campo id toma el valor del id del dato q se va a actualizar, usado para recuperar los datos
*/
function build_upd($con,$ztabhtml=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$campo,$campo_hidden,$id)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '			
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';					
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	
	$ncols=2;//solo dos columnas, la una para la etiqueta y la otra el campo
	$nfils=count($campo);
	$campo_form='';
	$campo_base='';
	$campo_extra='';
	for ($i=0; $i < $nfils; $i++) 
	{	
		$hdr .=	'<TR BGCOLOR="#CCCCCC">';	
		//etiqueta
		$eti=$campo[$i]["etiqueta"];
		$hdr .= "<td nowrap class='table_hd'>$eti</td>";
		//campo
		$c_nombre=$campo[$i]["nombre"];
		$c_tipo=$campo[$i]["tipo_campo"];
		$c_sql=$campo[$i]["sql"];
		$c_valor=$campo[$i]["valor"];
		$campito="";		
		switch($c_tipo)
		{
			case "text":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
						case "date":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//link para fecha
						$resto='<a href="javascript:show_calendar(';
						$resto.="'form1.".$c_nombre."');"; 
						$resto.='" onmouseover="window.status=';
						$resto.="'Date Picker';return true;";
						$resto.='"> <img src="images/big_calendar.gif" width=24 height=24 border=0>'; 
                      	$resto.='</a>';
						$campito.=$resto;
						break;
			case "hidden":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//recuperar los datos q se van a mostrar del sql
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  if (!$rs->EOF) 
						  {
							$codigo=$rs->fields[0];
							$descripcion=$rs->fields[1];
							$resto.=$codigo.' - '.$descripcion;		
						  }
						}  						
						$campito.=$resto;						
						break;
			case "nada":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//recuperar los datos q se van a mostrar del sql
						$resto=$c_valor;						  						
						$campito.=$resto;						
						break;												
			case "area":
						$campito='<textarea name="'.$c_nombre.'" >'.$c_valor.'</textarea>';
						break;						
			case "password":
						$campito='<input type="password" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "radio":
						$resto="";
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.="<input type='radio' name='".$c_nombre."' value='".$valor."' ";
							if($valor==$c_valor)
								$resto.=" checked ";
							$resto.=">".$texto."<br>";	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto;
						break;
			case "select":
						$campito="<select name='".$c_nombre."' >";
						$resto="";
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.="<option value='".$valor."'";
							if($valor==$c_valor)
								$resto.=" selected ";
							$resto.=">".$texto."</option>";	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto."</select>";
						break;			
		}// fin switch
		$hdr .= "<td nowrap class='table_hd'>$campito</td></tr> \n\n";
		//$campo_form.=$c_nombre."|";
		$campo_base.=$c_nombre."|";
	}

	print $hdr."\n\n </TABLE>\n\n";//tabla de mis datos

	print "</TABLE>\n\n";
	print "</TABLE>\n\n";
	
	//campos hidden
	$nfils=count($campo_hidden);
	for ($i=0; $i < $nfils; $i++) 
	{	
		$c_nombre=$campo_hidden[$i]["nombre"];
		$c_valor=$campo_hidden[$i]["valor"];
		$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
		print $campito."\n";
		$campo_extra.=$c_nombre."|";		
	}
	
	//nombre de todos los campos usados en la forma, van concatenados y pasan como variable hidden al destino
	//el nombre usado es campo_form y el separador es |
	$campo_base=substr($campo_base,0,(strlen($campo_base)-1));
	print '<input type="hidden" name="campo_base" value="'.$campo_base.'" >';
	$campo_extra=substr($campo_extra,0,(strlen($campo_extra)-1));
	print '<input type="hidden" name="campo_extra" value="'.$campo_extra.'" >';		
}

function build_updCad($con,$ztabhtml=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$campo,$campo_hidden,$id)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	$cad=<<<va
			<TABLE WIDTH="$width" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="$icono" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								$titulo&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>
va;

	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	
	$ncols=2;//solo dos columnas, la una para la etiqueta y la otra el campo
	$nfils=count($campo);
	$campo_form='';
	$campo_base='';
	$campo_extra='';
	for ($i=0; $i < $nfils; $i++) 
	{	
		$hdr .=	'<TR BGCOLOR="#CCCCCC">';	
		//etiqueta
		$eti=$campo[$i]["etiqueta"];
		$hdr .= "<td nowrap class='table_hd'>$eti</td>";
		//campo
		$c_nombre=$campo[$i]["nombre"];
		$c_tipo=$campo[$i]["tipo_campo"];
		$c_sql=$campo[$i]["sql"];
		$c_valor=$campo[$i]["valor"];
		$c_js=$campo[$i]["js"];
		$campito="";		
		switch($c_tipo)
		{
			case "text":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "file":
						$campito='<input type="file" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						$resto=<<<mya
<a href="$c_valor">$c_valor</a>
mya;
						$campito.=$resto;
						break;			
			case "date":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//link para fecha
						$resto='<a href="javascript:show_calendar(';
						$resto.="'form1.".$c_nombre."');"; 
						$resto.='" onmouseover="window.status=';
						$resto.="'Date Picker';return true;";
						$resto.='"> <img src="images/big_calendar.gif" width=24 height=24 border=0>'; 
                      	$resto.='</a>';
						$campito.=$resto;
						break;
//		para el formulario de FACTORES,	para el nombre e ingresar datos numericos
			case "text_nombre":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" '.$c_js.' size="40" >';
						break;
			case "text_num":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" '.$c_js.' size="6" maxlength="6" >';
						break;
			case "hidden":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//recuperar los datos q se van a mostrar del sql
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  if (!$rs->EOF) 
						  {
							$codigo=$rs->fields[0];
							$descripcion=$rs->fields[1];
							$resto.=$codigo.' - '.$descripcion;		
						  }
						}  						
						$campito.=$resto;						
						break;
			case "nada":
						//$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						$campito=$c_valor;
						break;												
			case "area":
						$campito='<textarea name="'.$c_nombre.'" cols="40" rows="5" >'.$c_valor.'</textarea>';
						break;						
			case "password":
						$campito='<input type="password" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "radio":
						$resto="";
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.="<input type='radio' name='".$c_nombre."' value='".$valor."' ";
							if($valor==$c_valor)
								$resto.=" checked ";
							$resto.=">".$texto."<br>";	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto;
						break;
			case "select":
						$campito="<select name='".$c_nombre."' ".$c_js." >";
						$resto="";
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.="<option value='".$valor."'";
							if($valor==$c_valor)
								$resto.=" selected ";
							$resto.=">".$texto."</option>";	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto."</select>";
						break;			
			case "selectAll":
						$campito="<select name='".$c_nombre."' ".$c_js." >";
						
						$cadSelected=" selected";
						
						if($c_valor=="*")
						  $cadSelectedAll=$cadSelected;
						else 
						  $cadSelectedAll="";  
						$resto='<option value="*" '.$cadSelectedAll.'>Todos</option>';
						
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.="<option value='".$valor."'";
							if($valor==$c_valor)
								$resto.=" selected ";
							$resto.=">".$texto."</option>";	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto."</select>";
						break;			
		}// fin switch
		$hdr .= "<td nowrap class='table_hd'>$campito</td></tr> \n\n";
		//$campo_form.=$c_nombre."|";
		if($c_tipo!="nada")
		  $campo_base.=$c_nombre."|";
	}

	$cad.= $hdr."\n\n </TABLE>\n\n";//tabla de mis datos

	$cad.= "</TABLE>\n\n";
	$cad.= "</TABLE>\n\n";
	
	//campos hidden
	$nfils=count($campo_hidden);
	for ($i=0; $i < $nfils; $i++) 
	{	
		$c_nombre=$campo_hidden[$i]["nombre"];
		$c_valor=$campo_hidden[$i]["valor"];
		$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
		$cad.= $campito."\n";
		$campo_extra.=$c_nombre."|";		
	}
	
	//nombre de todos los campos usados en la forma, van concatenados y pasan como variable hidden al destino
	//el nombre usado es campo_form y el separador es |
	$campo_base=substr($campo_base,0,(strlen($campo_base)-1));
	$cad.= '<input type="hidden" name="campo_base" value="'.$campo_base.'" >';
	$campo_extra=substr($campo_extra,0,(strlen($campo_extra)-1));
	$cad.= '<input type="hidden" name="campo_extra" value="'.$campo_extra.'" >';
	return($cad);
}

function build_show($con,$ztabhtml=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$campo,$campo_hidden,$id)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '			
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';					
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	
	$ncols=2;//solo dos columnas, la una para la etiqueta y la otra el campo
	$nfils=count($campo);
	$campo_form='';
	$campo_base='';
	$campo_extra='';
	for ($i=0; $i < $nfils; $i++) 
	{	
		$hdr .=	'<TR BGCOLOR="#CCCCCC">';	
		//etiqueta
		$eti=$campo[$i]["etiqueta"];
		$hdr .= "<td nowrap class='table_hd'>$eti</td>";
		//campo
		$c_nombre=$campo[$i]["nombre"];
		$c_tipo=$campo[$i]["tipo_campo"];
		$c_sql=$campo[$i]["sql"];
		$c_valor=$campo[$i]["valor"];
		$campito="";		
		switch($c_tipo)
		{
			case "text":
						$campito=$c_valor;
						break;
			case "area":
						$campito=$c_valor;
						break;						
			case "password":
						$campito=$c_valor;
						break;
			case "select":						
						$resto="";
						if(strlen($c_sql)>0)
						{						  
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset
						  $band=0;
						  while ((!$rs->EOF) && (!$band))
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							if($valor==$c_valor)
							{
								$campito.=$texto;
								$band=1;
							}	
							$rs->MoveNext();	
						  }
						}  						
						break;			
		}// fin switch
		$hdr .= "<td nowrap class='table_hd'>$campito</td></tr> \n\n";
		$campo_base.=$c_nombre."|";
	}

	print $hdr."\n\n </TABLE>\n\n";//tabla de mis datos

	print "</TABLE>\n\n";
	print "</TABLE>\n\n";
	
	//campos hidden
	$nfils=count($campo_hidden);
	for ($i=0; $i < $nfils; $i++) 
	{	
		$c_nombre=$campo_hidden[$i]["nombre"];
		$c_valor=$campo_hidden[$i]["valor"];
		$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
		print $campito."\n";
		$campo_extra.=$c_nombre."|";		
	}
	
	//nombre de todos los campos usados en la forma, van concatenados y pasan como variable hidden al destino
	//el nombre usado es campo_form y el separador es |
	$campo_base=substr($campo_base,0,(strlen($campo_base)-1));
	print '<input type="hidden" name="campo_base" value="'.$campo_base.'" >';
	$campo_extra=substr($campo_extra,0,(strlen($campo_extra)-1));
	print '<input type="hidden" name="campo_extra" value="'.$campo_extra.'" >';		
}

//fin pv

/*
	* funcion para mostrar en una tabla datos para anadir datos
*/
function build_filter($con,$ztabhtml=false,$titulo,$icono,$width,$htmlspecialchars=true
		,$campo,$campo_hidden)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	echo '			
			<TABLE WIDTH="'.$width.'" CELLSPACING=0 CELLPADDING=0 CLASS="homebox">
			<TR><TD>
					<table width="100%" border=0 cellspacing=0 cellpadding=0 CLASS="titletable">
						<tr>
							<td nowrap><SPAN class="title" STYLE="cursor:default;">
								<img src="'.$icono.'" border=0 align=absmiddle HSPACE=2><font color="#FFFFFF">
								'.$titulo.'&nbsp;</font></SPAN>
							</td>
						</TR>
					</TABLE>
					<TABLE WIDTH="100%" CELLSPACING=0 CELLPADDING=0 CLASS="tableinside"><TR><TD>';					
	$hdr = "<TABLE WIDTH='100%' border=0 CELLPADDING=2 CELLSPACING=1 BGCOLOR='#CCCCCC'>\n\n";
	
	$ncols=2;//solo dos columnas, la una para la etiqueta y la otra el campo
	$nfils=count($campo);
	$campo_form='';
	$campo_base='';
	$campo_extra='';
	for ($i=0; $i < $nfils; $i++) 
	{	
		$hdr .=	'<TR BGCOLOR="#CCCCCC">';	
		//etiqueta
		$eti=$campo[$i]["etiqueta"];
		$hdr .= "<td nowrap class='table_hd'>$eti</td>";
		//campo
		$c_nombre=$campo[$i]["nombre"];
		$c_tipo=$campo[$i]["tipo_campo"];
		$c_sql=$campo[$i]["sql"];
		$c_valor=$campo[$i]["valor"];
		$campito="";		
		switch($c_tipo)
		{
			case "text":
						$campito='<input type="text" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "hidden":
						$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						//recuperar los datos q se van a mostrar del sql
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  if (!$rs->EOF) 
						  {
							$codigo=$rs->fields[0];
							$descripcion=$rs->fields[1];
							$resto.=$codigo.' - '.$descripcion;		
						  }
						}  						
						$campito.=$resto;						
						break;						
			case "area":
						$campito='<textarea name="'.$c_nombre.'">'.$c_valor.'</textarea>';
						break;						
			case "password":
						$campito='<input type="password" name="'.$c_nombre.'" value="'.$c_valor.'" >';
						break;
			case "select":
						$campito="<select name='".$c_nombre."' ><option value=''></option>";
						$resto='';
						if(strlen($c_sql)>0)
						{						  
						  //$sql="select ait_id,ait_type,ait_num_assem,ait_id from mai_ota_aircraft_type order by ait_type";
						  $rs = &$con->Execute($c_sql);
						
						  //recuperar datos del recordset						  
						  while (!$rs->EOF) 
						  {
							$valor=$rs->fields[0];
							$texto=$rs->fields[1];
							$resto.='<option value="'.$texto.'">'.$texto.'</option>';	
							$rs->MoveNext();	
						  }
						}  						
						$campito.=$resto.'</select>';
						break;			
		}// fin switch
		$hdr .= "<td nowrap class='table_hd'>$campito</td></tr> \n\n";
		//$campo_form.=$c_nombre."|";
		$campo_base.=$c_nombre."|";
	}

	print $hdr."\n\n </TABLE>\n\n";//tabla de mis datos

	print "</TABLE>\n\n";
	print "</TABLE>\n\n";
	
	//campos hidden
	$nfils=count($campo_hidden);
	for ($i=0; $i < $nfils; $i++) 
	{	
		$c_nombre=$campo_hidden[$i]["nombre"];
		$c_valor=$campo_hidden[$i]["valor"];
		$campito='<input type="hidden" name="'.$c_nombre.'" value="'.$c_valor.'" >';
		print $campito."\n";
		$campo_extra.=$c_nombre."|";		
	}
	
	//nombre de todos los campos usados en la forma, van concatenados y pasan como variable hidden al destino
	//el nombre usado es campo_form y el separador es |
	$campo_base=substr($campo_base,0,(strlen($campo_base)-1));
	print '<input type="hidden" name="campo_base" value="'.$campo_base.'" >';
	$campo_extra=substr($campo_extra,0,(strlen($campo_extra)-1));
	print '<input type="hidden" name="campo_extra" value="'.$campo_extra.'" >';		
}

function rs2html(&$rs,$ztabhtml=false,$zheaderarray=false,$htmlspecialchars=true)
{
$s ='';$rows=0;$docnt = false;
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

	if (!$rs) {
		printf(ADODB_BAD_RS,'rs2html');
		return false;
	}
	
	if (! $ztabhtml) $ztabhtml = "BORDER='1' WIDTH='98%'";
	//else $docnt = true;
	$typearr = array();
	$ncols = $rs->FieldCount();
	$hdr = "<TABLE CLASS='tableinside' COLS=$ncols $ztabhtml >\n\n";
	for ($i=0; $i < $ncols; $i++) {	
		$field = $rs->FetchField($i);
		if ($zheaderarray) $fname = $zheaderarray[$i];
		else $fname = htmlspecialchars($field->name);	
		$typearr[$i] = $rs->MetaType($field->type,$field->max_length);
 		//print " $field->name $field->type $typearr[$i] ";
			
		if (strlen($fname)==0) $fname = '&nbsp;';
		$hdr .= "<TH>$fname</TH>";
	}

	print $hdr."\n\n";
	// smart algorithm - handles ADODB_FETCH_MODE's correctly!
	$numoffset = isset($rs->fields[0]);

	while (!$rs->EOF) {
		
		$s .= "<TR valign=top>\n";
		
		for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields); 
			$i < $ncols; 
			$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {
			
			$type = $typearr[$i];
			switch($type) {
			case 'T':
				$s .= "	<TD>".$rs->UserTimeStamp($v,"Y-m-d h:i:s") ."&nbsp;</TD>\n";
			break;
			case 'D':
				$s .= "	<TD>".$rs->UserDate($v,"Y-m-d") ."&nbsp;</TD>\n";
			break;
			case 'I':
			case 'N':
				$s .= "	<TD align=right>".stripslashes((trim($v))) ."&nbsp;</TD>\n";
			   	
			break;
			default:
				if ($htmlspecialchars) $v = htmlspecialchars($v);
				$s .= "	<TD>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";
			  
			}
		} // for
		$s .= "</TR>\n\n";
			  
		$rows += 1;
		if ($rows >= $gSQLMaxRows) {
			$rows = "<p>Truncated at $gSQLMaxRows</p>";
			break;
		} // switch

		$rs->MoveNext();
	
	// additional EOF check to prevent a widow header
		if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {
	
		//if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
			print $s . "</TABLE>\n\n";
			$s = $hdr;
		}
	} // while

	print $s."</TABLE>\n\n";

	if ($docnt) print "<H2>".$rows." Rows</H2>";
	
	return $rows;
 }
 
// pass in 2 dimensional array
function arr2html(&$arr,$ztabhtml='',$zheaderarray='')
{
	if (!$ztabhtml) $ztabhtml = 'BORDER=1';
	
	$s = "<TABLE $ztabhtml>";//';print_r($arr);

	if ($zheaderarray) {
		$s .= '<TR>';
		for ($i=0; $i<sizeof($zheaderarray); $i++) {
			$s .= "	<TH>{$zheaderarray[$i]}</TH>\n";
		}
		$s .= "\n</TR>";
	}
	
	for ($i=0; $isizeof($arr); $i++) {
		$s .= '<TR>';
		$a = &$arr[$i];
		if (is_array($a)) 
			for ($j=0; $jsizeof($a); $j++) {
				$val = $a[$j];
				if (empty($val)) $val = '&nbsp;';
				$s .= "	<TD>$val</TD>\n";
			}
		else if ($a) {
			$s .=  '	<TD>'.$a."/TD>\n";
		} else $s .= "	<TD>&nbsp;</TD>\n";
		$s .= "\n</TR>\n";
	}
	$s .= '</TABLE>';
	print $s;
}

function buildmenu($username,$perfil)
{
  global $conn;
  //global $print;
  
  $usuNombre=$username;

	if (!isset($print))
	{
		$query=<<<sql
        select distinct m.mod_id,m.mod_nombre,m.mod_formulario,m.mod_imagen 
        from perfilxsubmodulo pxsub, modulo m 
        where pxsub.per_id=$perfil 
         and m.mod_id=pxsub.mod_id 
         order by m.mod_orden,m.mod_id 
sql;
		//) // ojo con esto

	    $rs = &$conn->Execute($query);
	    if (!$rs||$rs->EOF) die(texterror('Su sesi�n de Usuario ha expirado. <a href="logout.php">Click para Ingresar al Sistema</a>'));
	
		echo '<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=1 BGCOLOR="#075685">
			  	<TR>
    				<TD ROWSPAN=2 BGCOLOR="#FFFFFF" WIDTH="10%" ALIGN=center VALIGN=middle><img src="images/logo.gif" border=0></TD>
    				<TD BGCOLOR="#075685"><TABLE BORDER=0 WIDTH="100%" CELLPADDING=1 CELLSPACING=0>
		        <TR>
        			<TD></TD>
			        <TD><SPAN CLASS="LoginName">';
		echo $usuNombre; 
		echo '&nbsp;&nbsp;&nbsp;&nbsp;�ltima Actualizaci�n a :&nbsp;'.date("Y-m-d H:i:s").'</SPAN></TD>
	          		<TD><select name="Start" onChange="i=this.selectedIndex;v=this.options[i].value;if(v)location.href=v;" CLASS="topSelector">
			              <option value="">Ir a ... 
        			      <OPTION VALUE="">----</OPTION>';

	  	while (!$rs->EOF) {
		   		  echo '<option value="'.trim($rs->fields[2]).'?id_aplicacion='.trim($rs->fields[0]).'">'.trim($rs->fields[1]).'</OPTION>';
				  echo "\n";
			  	  $rs->MoveNext();
		}
		echo '</select></td>';
		echo '	
	        	 	<form name="logoutform" method=post action="logout.php">
	            		<TD VALIGN=middle>
	            			<SPAN CLASS="ButtonTop" onclick="fOpenWindow(\'doc/ayuda.html\',\'Ayuda\',1000,600)" onmouseover="overborder(this.style,\'#90A8C8\')" onmouseout="moutborder(this.style,\'#075685\')">
	            				<img src="images/ayuda.png">Ayuda
	            			</SPAN>
	            			&nbsp; &nbsp; 
        	      			<SPAN CLASS="ButtonTop" onclick="document.forms[\'logoutform\'].submit()" onmouseover="overborder(this.style,\'#90A8C8\')" onmouseout="moutborder(this.style,\'#075685\')">
        	      				<img src="images/salir.png">Salir
        	      			</SPAN> 
						&nbsp; &nbsp; 
						</TD>
	          		</form>
        			</TR>
      			</TABLE></TD>
			  </TR>
			  <TR>
	    		<TD BGCOLOR="#90A8C8" HEIGHT="30" VALIGN=top>';
	 	$rs->MoveFirst();
		while (!$rs->EOF) {	  
			  echo '<nobr>
			  			<SPAN class=menu onClick="location.href='."'".trim($rs->fields[2])."?id_aplicacion=".trim($rs->fields[0])."'".'" onMouseOver="over(this.style);showstatus('."'".trim($rs->fields[1])."'".');" onMouseOut="mout(this.style);hidestatus();">
							<img src="images/'.trim($rs->fields[3]).'" border=0 align=absmiddle >&nbsp;'.$rs->fields[1].'
						</SPAN>
					</nobr>';
		  	  $rs->MoveNext();
		}
		echo '
				</TD>
			  </TR>
			</TABLE>
	 	   </TD>
		  </TR>
		</TABLE>';
	}
	else
	{
		echo '<a href="javascript:print()"><img src="images/print.gif" border=0 alt="Print Now"></a>';
	}
}


function buildsubmenu($id_aplicacion,$default_sub_aplicacion=0,$perfil=0){

	global $conn;
	global $print;
	if (!isset($print)){
		
		$query=<<<cad
	   select distinct pxs.mod_id,pxs.submod_id,s.submod_orden
	   from perfilxsubmodulo pxs,submodulo s 
	   where pxs.mod_id=$id_aplicacion and pxs.per_id=$perfil 
	   and s.mod_id=pxs.mod_id and s.submod_id=pxs.submod_id
	   order by s.submod_orden
cad;
	    $rs = &$conn->Execute($query);
	    if (!$rs||$rs->EOF) die(texterror('No existen subm�dulos.'));

		$flag=1;$flag1=1;$contador=0;
		include_once("class/c_submodulo.php");
		$oSubmodulo=new c_submodulo($conn);
		
		
  		while (!$rs->EOF) 
  		{
  		  $oSubmodulo->info($oSubmodulo->id2cad($rs->fields[0],$rs->fields[1]));
			if ($contador%10==0){
				echo '<TABLE CELLPADDING=0 CELLSPACING=0 >
					  	<TR>';
			}
			
			echo '
			    <TD VALIGN="bottom" WIDTH="1%">
					<TABLE CLASS="';
			if ($oSubmodulo->submod_id==$default_sub_aplicacion)
			{
					echo 'tabselected';
			}
			else
			{
				
			    /*if($default_sub_aplicacion==0&&$flag==1)
				{
					echo 'tabselected';
					$flag=0;
				}
				else
				{
					echo 'tab';
				}*/
				
				echo 'tab';
			}		
			echo	'" CELLPADDING=3 CELLSPACING=0>
        				<TR>
	          				<TD nowrap><A HREF="'.trim($oSubmodulo->submod_formulario).'?id_aplicacion='.trim($oSubmodulo->mod_id).'&id_subaplicacion='.trim($oSubmodulo->submod_id).'" CLASS="';
			if (trim($oSubmodulo->submod_id)==$default_sub_aplicacion){
					echo 'tabtxtselected';
			}
			else{
				if($default_sub_aplicacion==0&&$flag1==1){
					echo 'tabtxtselected';
					$flag1=0;
				}
				else{
					echo 'tabtxt';
				}
			}
			echo '" onmouseover="status='."'".trim($oSubmodulo->submod_nombre)."'".';return true;" onmouseout="status='."''".';">
					<IMG SRC="images/'.trim($oSubmodulo->submod_imagen).'" BORDER=0 HSPACE=2 ALIGN="absmiddle">';
			echo trim($oSubmodulo->submod_nombre);
		    echo '     		</A></TD>
	    	    		</TR>
	      			</TABLE>
				</TD>';


			if ($contador%9==0&&$contador!=0){
				echo '
			    <TD VALIGN="bottom" WIDTH="100%">
					
				</td>';

				echo '</tr>
					  	</Table>';
			}
			$contador=$contador+1;
  		    $rs->MoveNext();

		}//fin while submodulo
		$tamanio=100-$contador;
		echo '<TD VALIGN="bottom" WIDTH="'.$tamanio.'%">
				<TABLE CLASS="empty" CELLPADDING=3 CELLSPACING=0 WIDTH="100%">
					<TR>
						<TD nowrap><IMG SRC="images/spacer.gif" WIDTH="12" HEIGHT="20"><SPAN CLASS="tabtxt">&nbsp;</SPAN>
					</TD></tr></table><td>';
		echo '  </TR>
			  </TABLE>';
		//$self_page=$_SERVER["REQUEST_URI"];
		$self_page="http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		//echo $self_page;
		//global $HTTP_REFERER;
		//$self_page=$HTTP_REFERER;
		$self_page=str_replace("?","~",$self_page);
		$self_page=str_replace("&","|",$self_page);
		$self_page=str_replace("%20","�",$self_page);
		$script_print="printpage.php?page="."$self_page";
		echo '<SCRIPT LANGUAGE="JavaScript">function printWindow() { window.open("'.$script_print.'","Print","width=580,height=400,resizable=1,scrollbars=1,toolbar=0,menubar=0,location=0");}</SCRIPT>';
		echo '<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="3" CLASS="workarea">
				  <TR>
				    <TD>
			<!--                        
                        <a href="#" onclick="printWindow()">Vista de Impresi�n</a><a href="printpage.php">otro Print Preview</a>
                        -->
			';
		donde_estoy();
		echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		//echo '<a href="'.$script_print.'" onclick="printWindow()">Vista de Impresi�n</a>
		echo '
						<HR NOSHADE SIZE=1 COLOR=#000000 CLASS=HRule>		
				       <table width=100%>
    				    <tr> 
				          <td >';
	}
}

function buildsubmenufooter(){

		echo'	</td>
        				</tr>
				      </table>
      
			    	</TD>
				  </TR>
			  </TABLE>
			</body>
		  </html>  
			  ';
		
		global $conn;
		$conn->Close();
			  
}
