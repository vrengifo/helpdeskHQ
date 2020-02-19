<?php
/**
 * Administrar la tabla VERDADEROFALSO
 *
 */
include_once("class/c_interfaz.php");
include_once("adodb/tohtml.inc.php");

class c_verdaderofalso
{
  //atributos base
  var $VERFAL_id;
  var $VERFAL_nombre;
  
  /**
   * Variable de conexión a Base de Datos
   *
   * @var unknown_type
   */
  var $con;
  
  var $msg;
  
  /**
   * Constructor
   *
   * @param conexionBD $conDB
   */
  function __construct(&$conDB)
  {
  	$this->con=$conDB;
  	$this->VERFAL_id="";
  	$this->VERFAL_nombre="";
  	
  	$this->msg="";
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select VERFAL_ID from VERDADEROFALSO
	    where VERFAL_NOMBRE='$this->VERFAL_nombre'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	}
	else 
	{
	  $res=$rs->fields[0];	
	}
	return($res);	
  }
  
  function info($id)
  { 
    $sql=<<<vic
	    select VERFAL_ID,VERFAL_NOMBRE
	    from VERDADEROFALSO
	    where VERFAL_ID=$id
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->VERFAL_id="";
	  $this->VERFAL_nombre="";
	}
	else 
	{
	  $this->VERFAL_id=$rs->fields[0];
	  $this->VERFAL_nombre=$rs->fields[1];
	  $res=$id;
	}
	return($res);	
  }
  
  function sqlSelect($orderby)
  {
  	if(strlen($orderby)==0)
  	  $cadOrderby=" order by VERFAL_ID ";
  	else 
  	  $cadOrderby=$orderby;  
  	
  	$sql=<<<cad
		select VERFAL_ID,VERFAL_NOMBRE
		from VERDADEROFALSO
		$cadOrderby
cad;
	return($sql);
  }
  
  
  function sqlSelectverdaderofalso($id)
  {
  	$sql=<<<cad
		select VERFAL_ID,VERFAL_NOMBRE
		from VERDADEROFALSO
		where VERFAL_ID='$id'
cad;
	return($sql);
  }
  function sqlSelectSiNo()
  {
  	$sql=<<<cad
		select VERFAL_VALOR,VERFAL_SINO
		from VERDADEROFALSO
		
cad;
	return($sql);
  }
  
  function sqlSelectSiNoVF()
  {
  	$sql=<<<cad
		select VERFAL_ID,VERFAL_SINO
		from VERDADEROFALSO
		
cad;
	return($sql);
  }
  
  function listall($orderby)
  {
  }
  
  /**
   * Enter description here...
   *
   * @param unknown_type $nombreCampo
   * @param unknown_type $valorCampo
   * @param cadena $estilo
   */
  function htmlSelect($nombreCampo,$valorCampo,$estilo)
  {
  	$cadEstilo=strlen($estilo)>0?$estilo:"";
  	
  	$rs=&$this->con->Execute($this->sqlSelectSiNo());
  	$cadOpcion="";
  	while (!$rs->EOF) 
  	{
  	  $valor=$rs->fields[0];
  	  $texto=$rs->fields[1];
  		
  	  $cadSelected="";
  	  if($valor==$valorCampo)
  	    $cadSelected=" selected";
  	  $datoOpcion='<option value="'.$valor.'" '.$cadSelected.' >'.$texto.'</option>';	
  	  $cadOpcion.=$datoOpcion;
  	  
  	  $rs->MoveNext();
  	}
  	
  	$cad=<<<cad
  	<select name="$nombreCampo" $cadEstilo >
  	  <option value="">Selecciona Dato</option>
  	  $cadOpcion
  	</select>
cad;
    return($cad);
  }

  /**
   * Construye código javascript para validación de formularios
   *
   * @return string
   */
  function validaJs()
  {
  	//<script language="JavaScript" src="js/validation.js"></script>
  	$cad=<<<va
  	
  	<script language="javascript">
	function valida()
	{
  	  define('tNombre', 'string', 'Módulo',1,100,document);
  	  define('tFormulario', 'string', 'Página',1,100,document);
  	  define('tOrden', 'num', 'Orden',1,3,document);
  	}
  	
  	function vValidar()
  	{
  	  var res;
  	  res=validate();
  	  return(res);
  	}
  	
  	function vValidarB(forma,urldestino)
  	{
  	  var res;
  	  res=validate();
  	  if(res) 
  	  {
  	    cambiar_action(forma,urldestino);
  	    forma.submit();
  	  }  	  
  	}
  	
  	</script>
va;
	return($cad);
  }  
  
}


?>