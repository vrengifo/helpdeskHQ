<?php
/*
  Programar metodos
  listo: __construct e info
*/


/**
 * Administrar la tabla parametro
 *
 */
//include_once("class/c_interfaz.php");

class c_parametro //implements c_interfaz 
{
  //atributos base
  var $par_id;
  var $par_fecha;
  var $par_fechahora;
  var $par_fechaformato;
  var $par_fechasql;
  var $par_fechahorasql;
  var $par_sepdecimal;
  var $par_seplista;
  var $par_cuentamail;
  var $par_homesite;
  
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
  	/*
  	$this->par_id=1;
  	$this->par_fecha="Y-m-d";
  	$this->par_fechahora="Y-m-d H:i:s";
  	$this->par_fechaformato="120";
  	$this->par_fechasql="char(10)";
  	$this->par_fechahorasql="char(19)";
  	$this->par_sepdecimal=".";
  	$this->par_seplista=":";
  	*/
  	$this->info();
  	
  	$this->msg="";
  }
  
  function exist($cad)
  { 
    $sql=<<<vic
	    select par_id from parametro
	    where par_fecha='$cad'
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
  
  function info()
  { 
    $sql=<<<vic
	    select *
	    from parametro
	    order by par_id desc
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->par_id=0;
	  $this->par_fecha="";
	  $this->par_fechahora="";
	  $this->par_fechaformato="";
	  $this->par_fechasql="";
	  $this->par_fechahorasql="";
	  $this->par_sepdecimal="";
	  $this->par_seplista=":";
	}
	else 
	{
	  $this->par_id=$rs->fields[0];
	  $this->par_fecha=$rs->fields[1];
	  $this->par_fechahora=$rs->fields[2];
	  $this->par_fechaformato=$rs->fields[3];
	  $this->par_fechasql=$rs->fields[4];
	  $this->par_fechahorasql=$rs->fields[5];
	  $this->par_sepdecimal=$rs->fields[6];
	  $this->par_seplista=$rs->fields[7];
	  $this->par_cuentamail=$rs->fields[8];
	  $this->par_homesite=$rs->fields[9];
	  $res=$id;
	}
	return($res);	
  }
  
  function fechaCorta($campo)
  {
    /*
    $oAux=new c_parametro($this->con);
    $oAux->info();
    $cad=<<<cad
    convert($oAux->par_fechasql,$campo,$oAux->par_fechaformato)
cad;
    */

    $cad=<<<cad
    convert($this->par_fechasql,$campo,$this->par_fechaformato)
cad;

    return($cad);
  }
  
  function fechaLarga($campo)
  {
    /*$oAux=new c_parametro($this->con);
    $oAux->info();
    $cad=<<<cad
    convert($oAux->par_fechahorasql,$campo,$oAux->par_fechaformato)
cad;*/
    $cad=<<<cad
    convert($this->par_fechahorasql,$campo,$this->par_fechaformato)
cad;
    
    return($cad);
  }
  
  function fechaPHP()
  {
    /*$oAux=new c_parametro($this->con);
    $oAux->info();
    $cad=$oAux->par_fecha;*/
    $cad=$this->par_fecha;
    return($cad);
  }
  
  function fechahoraPHP()
  {
    /*$oAux=new c_parametro($this->con);
    $oAux->info();
    $cad=$oAux->par_fechahora;*/
    $cad=$this->par_fechahora;
    return($cad);
  }
  
  function add()
  {
  	$existe=$this->exist($this->par_fecha);
  	if(!$existe)
  	{
  	  $sql=<<<va
  	insert into parametro
  	(par_fecha,par_fechahora,par_fechaformato,par_fechasql,
  	par_fechahorasql,par_sepdecimal,par_seplista)
  	values
  	('$this->par_fecha','$this->par_fechahora','$this->par_fechaformato',
  	'$this->par_fechasql','$this->par_fechahorasql','$this->par_sepdecimal',
  	'$this->par_seplista')
va;
  	  $rs=&$this->con->Execute($sql);
  	  if($rs)
  	  {
  	    $res=$this->exist($this->par_fecha);
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
  	$oAux=new c_parametro($this->con);
  	if(!$oAux->exist($oAux->par_fecha))
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      //verificar nombre
      if($this->par_fecha==$oAux->par_fecha)
      {
        $datoNombre=$oAux->par_fecha;
      }
      else 
      {
        $existe=$oAux->exist($this->par_fecha);
        if(!$existe)
          $datoNombre=$this->par_fecha;
        else 
          $datoNombre=$oAux->par_fecha;  
      }
      $sql=<<<sql
        update parametro set
        par_fecha='$datoNombre',
        par_fechahora='$this->par_fechahora',
        par_fechaformato='$this->par_fechaformato',
        par_fechasql='$this->par_fechasql',
        par_fechahorasql='$this->par_fechahorasql',
        par_sepdecimal='$this->par_sepdecimal',
        par_seplista='$this->par_seplista'
        where
        par_id=$id
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
  	delete from parametro 
  	where par_id=$id
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
  	  $cadOrderby="";
  	else 
  	  $cadOrderby=$orderby;  
  	
  	$sql=<<<cad
		select par_id,par_fecha
		from parametro
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by par_fecha"));
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
  	  $ncampos=7;
	  if($ncampos==count($dato))
	  {
        $this->par_fecha=$dato[0];
	    $this->par_fechahora=$dato[1];
	    $this->par_fechaformato=$dato[2];
	    $this->par_fechasql=$dato[3];
	    $this->par_fechahorasql=$dato[4];
	    $this->par_sepdecimal=$dato[5];
	    $this->par_seplista=$dato[6];
	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=7;
	  if($ncampos==count($dato))
	  {
        $this->par_fecha=$dato[0];
	    $this->par_fechahora=$dato[1];
	    $this->par_fechaformato=$dato[2];
	    $this->par_fechasql=$dato[3];
	    $this->par_fechahorasql=$dato[4];
	    $this->par_sepdecimal=$dato[5];
	    $this->par_seplista=$dato[6];
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
  	  select par_id,
	  par_fecha,par_fechahora,par_fechaformato,par_fechasql,par_fechahorasql,par_sepdecimal,
	  par_seplista,par_id
	  from parametro
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("Elim.","FECHA","FECHA HORA","FECHA FORMATO","FECHA SQL","FECHA HORA SQL","SEPARADOR DECIMAL","SEPARADOR LISTA","MODIFICAR");
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
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Fecha Hora","nombre"=>"tFechahora","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Fecha Formato","nombre"=>"tFechaformato","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Fecha SQL","nombre"=>"tFechasql","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Fecha Hora SQL","nombre"=>"tFechahorasql","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Separador Decimal","nombre"=>"tSeparadordecimal","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Separador Lista","nombre"=>"tSeparadorlista","tipo_campo"=>"text","sql"=>"","valor"=>"")
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
				array("etiqueta"=>"* Fecha","nombre"=>"tFecha","tipo_campo"=>"text","sql"=>"","valor"=>$this->par_fecha),
				array("etiqueta"=>"* Fecha Hora","nombre"=>"tFechahora","tipo_campo"=>"text","sql"=>"","valor"=>$this->par_fechahora),
				array("etiqueta"=>"* Fecha Formato","nombre"=>"tFechaformato","tipo_campo"=>"text","sql"=>"","valor"=>$this->par_fechaformato),
				array("etiqueta"=>"* Fecha SQL","nombre"=>"tFechasql","tipo_campo"=>"text","sql"=>"","valor"=>$this->par_fechasql),
				array("etiqueta"=>"* Fecha Hora SQL","nombre"=>"tFechahorasql","tipo_campo"=>"text","sql"=>"","valor"=>$this->par_fechahorasql),
				array("etiqueta"=>"* Separador Decimal","nombre"=>"tSeparadordecimal","tipo_campo"=>"text","sql"=>"","valor"=>$this->par_sepdecimal),
				array("etiqueta"=>"* Separador Lista","nombre"=>"tSeparadorlista","tipo_campo"=>"text","sql"=>"","valor"=>$this->par_seplista)
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
  	  define('tFecha', 'string', 'Fecha',1,100,document);
  	  define('tFechahora', 'string', 'Fecha Hora',1,100,document);
  	  define('tFechaformato', 'num', 'Fecha Formato',1,10,document);
  	  define('tFechasql', 'string', 'Fecha SQL',1,100,document);
  	  define('tFechahorasql', 'string', 'Fecha Hora SQL',1,100,document);
  	  define('tSeparadordecimal', 'string', 'Separador Decimal',1,1,document);
  	  define('tSeparadorlista', 'string', 'Separador Lista',1,1,document);
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