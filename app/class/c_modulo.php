<?php
/**
 * Administrar la tabla modulo
 *
 */
include_once("class/c_interfaz.php");
include_once("adodb/tohtml.inc.php");

class c_modulo implements c_interfaz 
{
  //atributos base
  var $mod_id;
  var $mod_nombre;
  /**
   * campo mod_formulario
   *
   * @var cadena
   */
  var $mod_formulario;
  var $mod_imagen;
  var $mod_orden;
  
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
  	$this->mod_id=0;
  	$this->mod_nombre="";
  	$this->mod_formulario="";
  	$this->mod_imagen="";
  	
  	$this->msg="";
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select mod_id from modulo
	    where mod_nombre='$this->mod_nombre'
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
	    select mod_id,mod_nombre,mod_formulario,mod_imagen,mod_orden 
	    from modulo
	    where mod_id=$id
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->mod_id=0;
	  $this->mod_nombre="";
	  $this->mod_formulario="";
	  $this->mod_imagen="";
	  $this->mod_orden=0;
	}
	else 
	{
	  $this->mod_id=$rs->fields[0];
	  $this->mod_nombre=$rs->fields[1];
	  $this->mod_formulario=$rs->fields[2];
	  $this->mod_imagen=$rs->fields[3];
	  $this->mod_orden=$rs->fields[4];
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
  	insert into modulo
  	(mod_nombre,mod_formulario,mod_imagen,mod_orden)
  	values
  	('$this->mod_nombre','$this->mod_formulario','$this->mod_imagen','$this->mod_orden')
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
  	$oAux=new c_modulo($this->con);
  	if(!$oAux->info($id))
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      //verificar nombre
      if($this->mod_nombre==$oAux->mod_nombre)
      {
        $datoNombre=$oAux->mod_nombre;
      }
      else 
      {
        $existe=$this->exist();
        if(!$existe)
          $datoNombre=$this->mod_nombre;
        else 
          $datoNombre=$oAux->mod_nombre;  
      }
      $sql=<<<sql
        update modulo set
        mod_nombre='$datoNombre',
        mod_formulario='$this->mod_formulario',
        mod_imagen='$this->mod_imagen',
        mod_orden='$this->mod_orden' 
        where 
        mod_id=$id
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
  	delete from modulo 
  	where mod_id=$id
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
  	  $cadOrderby=" order by mod_nombre ";
  	else 
  	  $cadOrderby=$orderby;  
  	$sql=<<<cad
		select mod_id,mod_nombre
		from modulo
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by mod_orden,mod_nombre"));
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
  
  

//html
  /**
   * Carga los datos a la clase desde un arreglo
   *
   * @param array $dato
   * @param string $iou ingresooupdate
   * @return int
   */
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=4;
	  if($ncampos==count($dato))
	  {
        $this->mod_nombre=$dato[0];
	    $this->mod_formulario=$dato[1];
	    $this->mod_imagen=$dato[2];
	    $this->mod_orden=$dato[3];
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
	    $this->mod_nombre=$dato[0];
	    $this->mod_formulario=$dato[1];
	    $this->mod_imagen=$dato[2];
	    $this->mod_orden=$dato[3];
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
  	  select mod_id,
	  mod_nombre,mod_formulario,mod_imagen,mod_orden,
	  mod_id 
	  from modulo 
	  order by mod_orden,mod_nombre
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Elim.","Módulo","Formulario","Imagen","Orden","Modificar");
	  $cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,
			'images/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");
	  //variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
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
  function adminAdd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo)
  {
    $campo=array(
				array("etiqueta"=>"* Módulo","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Página","nombre"=>"tFormulario","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"  Imagen","nombre"=>"tImagen","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Orden","nombre"=>"tOrden","tipo_campo"=>"text","sql"=>"","valor"=>"")
				
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
  		  <!--<input type="button" name="AddB" value="Añadir" onClick="return vValidarB(document.form1,'$formaAction');">-->
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
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
    $oAux=new c_modulo($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Módulo","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->mod_nombre),
				array("etiqueta"=>"* Página","nombre"=>"tFormulario","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->mod_formulario),
				array("etiqueta"=>"  Imagen","nombre"=>"tImagen","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->mod_imagen),
				array("etiqueta"=>"* Orden","nombre"=>"tOrden","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->mod_orden)
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
	// onClick="validate();return returnVal;"
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