<?php
/**
 * Administrar la tabla horario
 *
 */
include_once("class/c_interfaz.php");

class c_horario implements c_interfaz 
{
  //atributos base
  var $hor_id;
  var $hor_nombre;
  var $hor_inicio;
  var $hor_fin;
  var $hor_descripcion;
  
  var $nroHorasDia;

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
  	$this->hor_id=0;
  	$this->hor_nombre="";
  	$this->hor_inicio="";
  	 	
  	$this->msg="";
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select hor_id from horario
	    where hor_nombre='$this->hor_nombre'
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
	    select hor_id,hor_nombre,hor_inicio,hor_fin,hor_descripcion,timediff(hor_fin,hor_inicio) nroHorasDia 
	    from horario
	    where hor_id=$id
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->hor_id=0;
	  $this->hor_nombre="";
	  $this->hor_inicio="";
	}
	else 
	{
	  $this->hor_id=$rs->fields[0];
	  $this->hor_nombre=$rs->fields[1];
	  $this->hor_inicio=$rs->fields[2];
	  $this->hor_fin=$rs->fields[3];
	  $this->hor_descripcion=$rs->fields[4];
	  $this->nroHorasDia=$rs->fields[5];
	  $res=$id;
	}
	return($res);	
  }
  
  function add()
  {
  	$existe=$this->exist();
  	if(!$existe)
  	{
  		
  	  $sql=<<<va
  	insert into horario
  	(hor_nombre,hor_inicio,hor_fin,hor_descripcion)
  	values
  	('$this->hor_nombre','$this->hor_inicio','$this->hor_fin','$this->hor_descripcion')
va;
  	  $rs=&$this->con->Execute($sql);
  	  if($rs)
  	  {
  	    $res=$this->exist();
      }
      else 
      {
        $res=0;
        $this->msg="Error de ingreso a base de datos";
      }
  	}
  	else
  	{
  	  $res=$existe; 	
  	  $this->msg="Dato ya existe";
	}
  	return($res);
  }
  
  
  function update($id)
  {
  	$oAux=new c_horario($this->con);
  	if($oAux->info($id)=="0")
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      //verificar nombre
      if($this->hor_nombre==$oAux->hor_nombre)
      {
        $datoNombre=$oAux->hor_nombre;
      }
      else 
      {
        $existe=$this->exist();
        if($existe=="0")
          $datoNombre=$this->hor_nombre;
        else 
          $datoNombre=$oAux->hor_nombre;  
      }
      $sql=<<<sql
        update horario set
        hor_nombre='$datoNombre',
        hor_inicio='$this->hor_inicio',
        hor_fin='$this->hor_fin',
        hor_descripcion='$this->hor_descripcion' 
        where 
       	hor_id=$oAux->hor_id
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function del($id)
  {
  	$sql=<<<sql
  	delete from horario 
  	where hor_id=$id
sql;
    $rs=&$this->con->Execute($sql);
    return($id);
  }
  
  function __destruct()
  {
  	
  }
  
  function sqlSelect($orderby)
  {
  	if(strlen($orderby)==0)
  	  $cadOrderby=" order by hor_nombre ";
  	else 
  	  $cadOrderby=$orderby;
  	
  	$sql=<<<cad
		select hor_id,hor_nombre
		from horario
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by hor_nombre"));
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
  	  $ncampos=4;
	  if($ncampos==count($dato))
	  {
        $this->hor_nombre=$dato[0];
	    $this->hor_descripcion=$dato[1];
        $this->hor_inicio=$dato[2];
	    $this->hor_fin=$dato[3];
	    
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=4;
	  if($ncampos==count($dato))
	  {
	    $this->hor_nombre=$dato[0];
	    $this->hor_descripcion=$dato[1];
	    $this->hor_inicio=$dato[2];
	    $this->hor_fin=$dato[3];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
	return($res);
  }

  /**
   * Crea el interfaz de Administración
   *
   * @param string $formaAction
   * @param string $principal
   * @param int $id_aplicacion
   * @param int $id_subaplicacion
   * @param string $destAdd
   * @param string $destUpdate
   * @param string $titulo
   * @return string
   */
  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal;
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  <br>
va;
  	
  	$sql=<<<va
  	  select hor_id,hor_nombre,hor_inicio,hor_fin,hor_id 
	  from horario 
	  order by hor_nombre
va;
	$rs=&$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  $mainheaders=array("Elim.","Horario","Nombre","Hora Inicio","Hora Fin","Modificar");
	  $cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,
			'images/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");

	  $cextra="id_aplicacion|id_subaplicacion|principal";
	}
	
	$cad.=<<<va
	  <input type="hidden" name="cextra" value="$cextra">
	</form>
va;
	return($cad);
  }
  
 
  /**
   * Crea el interfaz para añadir
   *
   * @param string $formaAction
   * @param string $principal
   * @param int $id_aplicacion
   * @param int $id_subaplicacion
   * @param string $titulo
   * @return string
   */
  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$request=NULL)
   
  {
    $campo=array(
				array("etiqueta"=>"* Nombre Horario","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Descripci&oacute;n","nombre"=>"tDescripcion","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Hora Inicio","nombre"=>"tHoraIni","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Hora Fin","nombre"=>"tHoraFin","tipo_campo"=>"text","sql"=>"","valor"=>"")

				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_addCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Add" value="Añadir" onClick="return vValidar();">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
  }
  
  /**
   * Crea el interfaz para actualización
   *
   * @param string $formaAction
   * @param string $principal
   * @param int $id_aplicacion
   * @param int $id_subaplicacion
   * @param string $titulo
   * @param string $id
   * @return string
   */
function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id)  
  {
    $oAux=new c_horario($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* horario","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->hor_nombre),
				array("etiqueta"=>"* Descripci&oacute;n","nombre"=>"tDescripcion","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->hor_inicio),
				array("etiqueta"=>"* Hora Inicio","nombre"=>"tHoraIni","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->hor_inicio),
				array("etiqueta"=>"* Hora Fin","nombre"=>"tHoraFin","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->hor_fin)
				);
				
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"id","valor"=>$id),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_updCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden,$id);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Upd" value="Actualizar" onClick="return vValidar();">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
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
  	  define('tNombre', 'string', 'horario',1,100,document);
  	  define('tHoraIni', 'string', 'horario',1,8,document);
  	  define('tHoraFin', 'string', 'horario',1,8,document);
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