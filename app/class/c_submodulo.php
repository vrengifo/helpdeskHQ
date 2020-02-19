<?php
/**
 * Administrar la tabla submodulo
 *
 */
include_once("class/c_interfaz.php");
include_once("class/c_parametro.php");
include_once("class/c_modulo.php");

class c_submodulo implements c_interfaz 
{
  //atributos base
  var $submod_id;
  var $mod_id;
  var $submod_nombre;
  /**
   * campo submod_formulario
   *
   * @var cadena
   */
  var $submod_formulario;
  var $submod_imagen;
  var $submod_orden;
  
  /**
   * Variable de conexión a Base de Datos
   *
   * @var unknown_type
   */
  var $con;
  
  var $msg;
  
  var $separador;
  
  /**
   * Constructor
   *
   * @param conexionBD $conDB
   */
  function __construct(&$conDB)
  {
  	$this->con=$conDB;
  	$this->submod_id=0;
  	$this->mod_id=0;
  	$this->submod_nombre="";
  	$this->submod_formulario="";
  	$this->submod_imagen="";
  	$this->submod_orden=0;
  	
  	$oPar=new c_parametro($conDB);
  	$oPar->info();
  	
  	$this->msg="";
  	
  	$this->separador=$oPar->par_seplista;
  }
  
  function id2cad($modId,$submodId)
  {
    $cad=$modId.$this->separador.$submodId;
    return($cad);
  }
  
  function cad2id($cad)
  {
    list($this->mod_id,$this->submod_id)=explode($this->separador,$cad);
  }
  
  /**
   * Función para armar cadena de id con campos combinados
   *
   * @param string $prefijo1
   * @param string $prefijo2
   * @return string
   */
  function cadIdQuery($prefijo1,$prefijo2)
  {
    $cad="(concat(".$prefijo1."mod_id,'".$this->separador."',".$prefijo2."submod_id))";
    return($cad);
  }
  
  function existName($modId,$Nombre)
  { 
    $oAux=new c_submodulo($this->con);
    $cadId=$oAux->cadIdQuery("","");
    $oAux->cad2id($cad);
    
    $sql=<<<vic
	    select $cadId from submodulo
	    where mod_id='$modId' and submod_nombre='$Nombre'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	}
	else 
	{
	  $res=$rs->fields[0];
	}
	return($res);
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select submod_id from submodulo
	    where mod_id='$this->mod_id' and submod_nombre='$this->submod_nombre'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	}
	else 
	{
	  $res=$this->id2cad($this->mod_id,$rs->fields[0]);
	}
	return($res);
  }
  
  function info($id)
  { 
    $oAux=new c_submodulo($this->con);
    $oAux->cad2id($id);
    
    $sql=<<<vic
	    select mod_id,submod_id,submod_nombre,submod_formulario,submod_imagen,submod_orden 
	    from submodulo 
	    where mod_id='$oAux->mod_id' and submod_id='$oAux->submod_id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->mod_id=0;
	  $this->submod_id=0;
	  $this->submod_nombre="";
	  $this->submod_formulario="";
	  $this->submod_imagen="";
	  $this->submod_orden=0;
	}
	else 
	{
	  $this->mod_id=$rs->fields[0];
	  $this->submod_id=$rs->fields[1];
	  $this->submod_nombre=$rs->fields[2];
	  $this->submod_formulario=$rs->fields[3];
	  $this->submod_imagen=$rs->fields[4];
	  $this->submod_orden=$rs->fields[5];
	  $res=$id;
	}
	return($res);	
  }
  
  function add()
  {
    $existe=$this->exist();
  	if($existe=="0")
  	{
  	  $sql=<<<va
  	insert into submodulo
  	(mod_id,submod_nombre,submod_formulario,submod_imagen,submod_orden)
  	values
  	('$this->mod_id','$this->submod_nombre','$this->submod_formulario','$this->submod_imagen','$this->submod_orden')
va;
  	  $rs=&$this->con->Execute($sql);
  	  if($rs)
  	  {
  	    $oAux=new c_submodulo($this->con);
  	    
  	    $res=$this->exist();
      }
      else 
      {
        $res="0";
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
  	$oAux=new c_submodulo($this->con);
  	if($oAux->info($id)=="0")
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      //verificar nombre
      if($this->submod_nombre==$oAux->submod_nombre)
      {
        $datoNombre=$oAux->submod_nombre;
      }
      else 
      {
        $existe=$oAux->existName($modId,$this->submod_nombre);
        if($existe=="0")
          $datoNombre=$this->submod_nombre;
        else 
          $datoNombre=$oAux->submod_nombre;  
      }
      $sql=<<<sql
        update submodulo set 
        mod_id='$this->mod_id',
        submod_nombre='$datoNombre',
        submod_formulario='$this->submod_formulario',
        submod_imagen='$this->submod_imagen',
        submod_orden='$this->submod_orden'
        where 
        mod_id=$oAux->mod_id and submod_id=$oAux->submod_id
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function del($id)
  {
  	$oAux=new c_submodulo($this->con);
  	$oAux->cad2id($id);
    $sql=<<<sql
  	delete from submodulo 
  	where mod_id=$oAux->mod_id and submod_id=$oAux->submod_id
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
  	  $cadOrderby="order by submod_orden ";
  	else 
  	  $cadOrderby=$orderby;  
  	
  	$sql=<<<cad
		select mod_id,submod_nombre
		from submodulo
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by submod_nombre"));
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
  
  function sqlSubmodule($mod)
  {
    if(strlen($mod)==0)
    {
      $sql="";
    }
    else 
    {  
    $sql=<<<cad
    select submod_id,submod_nombre 
    from submodulo
    where mod_id=$mod
cad;
    }
    return($sql);  
  }
  
  function listSubmodule($mod)
  {
    $sql=<<<cad
    select submod_id,submod_nombre 
    from submodulo
    where mod_id=$mod
cad;
    $rs=&$this->con->Execute($sql);
    return($rs);  
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
  	  $ncampos=5;
	  if($ncampos==count($dato))
	  {
        $this->mod_id=$dato[0];
	    $this->submod_nombre=$dato[1];
	    $this->submod_formulario=$dato[2];
	    $this->submod_imagen=$dato[3];
	    $this->submod_orden=$dato[4];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=5;
	  if($ncampos==count($dato))
	  {
	    $this->mod_id=$dato[0];
	    $this->submod_nombre=$dato[1];
	    $this->submod_formulario=$dato[2];
	    $this->submod_imagen=$dato[3];
	    $this->submod_orden=$dato[4];
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
  	$cadId=$this->cadIdQuery("s.","s.");
  	$sql=<<<va
  	  select $cadId as a1,
	  m.mod_nombre,s.submod_nombre,s.submod_formulario,s.submod_imagen,s.submod_orden,
	  $cadId as a2
	  from submodulo s, modulo m
	  where m.mod_id=s.mod_id  
	  order by m.mod_nombre,s.submod_orden,s.submod_nombre
va;

  	//echo"<hr>$sql<hr>";
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Elim.","Módulo","Submódulo","Formulario","Imagen","Orden","Modificar");
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
    $oMod=new c_modulo($this->con);
    
    $campo=array(
				array("etiqueta"=>"* Módulo","nombre"=>"tModulo","tipo_campo"=>"select","sql"=>$oMod->sqlSelect(""),"valor"=>""),
				array("etiqueta"=>"* Submódulo","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
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
    $oMod=new c_modulo($this->con);
    
    $oAux=new c_submodulo($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Módulo","nombre"=>"tModulo","tipo_campo"=>"select","sql"=>$oMod->sqlSelect(""),"valor"=>$oAux->mod_id),
				array("etiqueta"=>"* Submódulo","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->submod_nombre),
				array("etiqueta"=>"* Página","nombre"=>"tFormulario","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->submod_formulario),
				array("etiqueta"=>"  Imagen","nombre"=>"tImagen","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->submod_imagen),
				array("etiqueta"=>"* Orden","nombre"=>"tOrden","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->submod_orden)
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
  	  define('tNombre', 'string', 'Submódulo',1,100,document);
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