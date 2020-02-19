<?php
/**
 * Administrar la tabla tipoestado
 *
 */
include_once("class/c_interfaz.php");

class c_tipoestado implements c_interfaz 
{
  //atributos base
  var $tipest_id;
  var $tipest_nombre;
  var $tipest_orden;

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
  	$this->tipest_id=0;
  	$this->tipest_nombre="";
  	 	
  	$this->msg="";
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select tipest_id from tipoestado
	    where tipest_nombre='$this->tipest_nombre'
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
	    select tipest_id,tipest_nombre,tipest_orden 
	    from tipoestado
	    where tipest_id='$id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="";
	  $this->tipest_id=0;
	  $this->tipest_nombre="";
	}
	else 
	{
	  $this->tipest_id=$rs->fields[0];
	  $this->tipest_nombre=$rs->fields[1];
	  $this->tipest_orden=$rs->fields[2];
	  $res=$id;
	}
	return($res);	
  }
  
  function add()
  {
  	return("");
  }
  
  
  function update($id)
  {
  	return($id);
  }
  
  function del($id)
  {
    return($id);
  }
  
  function __destruct()
  {
  	
  }
  
  function sqlSelect($orderby)
  {
  	if(strlen($orderby)==0)
  	  $cadOrderby=" order by tipest_orden ";
  	else 
  	  $cadOrderby=$orderby;
  	
  	$sql=<<<cad
		select tipest_id,tipest_nombre
		from tipoestado
		$cadOrderby
cad;
	return($sql);
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by tipest_nombre"));
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
  
  function listall($orderby)
  {
  }
  
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=0;
	  if($ncampos==count($dato))
	  {
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=0;
	  if($ncampos==count($dato))
	  {
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);
  }

}
?>