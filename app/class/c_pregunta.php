<?php
/**
 * Administrar la tabla pregunta
 *
 */
include_once("class/c_interfaz.php");
include_once("class/c_encuesta.php");
include_once("class/c_servicio.php");
include_once("class/c_verdaderofalso.php");

class c_pregunta implements c_interfaz 
{
  //atributos base
  var $pre_id;
  var $enc_id;
  var $pre_nombre;
  
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
  	$this->pre_id=0;
  	$this->enc_id=0;
  	$this->pre_nombre="";

  	$this->msg="";
  	$this->separador=":";
  }
  
  function id2cad($encuesta,$pregunta)
  {
    $res=$encuesta.$this->separador.$pregunta;
    return($res);
  }
  
  function cad2id($cad)
  {
    list($this->enc_id,$this->pre_id)=explode($this->separador,$cad);
    return ($cad);	
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select enc_id,pre_id from pregunta
	    where pre_nombre='$this->pre_nombre' and enc_id='$this->enc_id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="";
	}
	else 
	{
	  $res=$this->id2cad($rs->fields[0],$rs->fields[1]);	
	}
	return($res);	
  }
  
  function info($id)
  { 
    $oAux=new c_pregunta($this->con);
    $oAux->cad2id($id);
    $sql=<<<vic
	    select enc_id,pre_id,pre_nombre 
	    from pregunta
	    where pre_id=$oAux->pre_id and enc_id=$oAux->enc_id
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->pre_id=0;
	  $this->enc_id=0;
	  $this->pre_nombre="";
	}
	else 
	{
	  $this->enc_id=$rs->fields[0];
	  $this->pre_id=$rs->fields[1];
	  $this->pre_nombre=$rs->fields[2];
	  
	  $res=$id;
	}
	return($res);	
  }
  
  function siguientePreId($encId)
  {
  	$sql=<<<mya
  	select max(pre_id) 
  	from pregunta 
  	where enc_id=$encId 
mya;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=1;
	}
	else 
	{
	  $res=$rs->fields[0]+1;
	}
	return($res); 
  }
  
  function add()
  {
  	$existe=$this->exist();
  	if($existe=="")
  	{
  	  //siguiente pre_id
  	  $this->pre_id=$this->siguientePreId($this->enc_id);
  	  
  	  $sql=<<<va
  	insert into pregunta
  	(enc_id,pre_id,pre_nombre)
  	values
  	('$this->enc_id','$this->pre_id','$this->pre_nombre')
va;
  	  $rs=&$this->con->Execute($sql);
  	  if($rs)
  	  {
  	    $res=$this->exist();
      }
      else 
      {
        $res="";
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
  	$oAux=new c_pregunta($this->con);
  	if($oAux->info($id)=="")
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      //verificar nombre
      if($this->pre_nombre==$oAux->pre_nombre)
      {
        $datoNombre=$oAux->pre_nombre;
      }
      else 
      {
        $existe=$this->exist();
        if($existe=="")
          $datoNombre=$this->pre_nombre;
        else 
          $datoNombre=$oAux->pre_nombre;  
      }
      $sql=<<<sql
        update pregunta set
        pre_nombre='$datoNombre'
        where 
       	pre_id=$oAux->pre_id and enc_id=$oAux->enc_id
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function del($id)
  {
  	$oAux=new c_pregunta($this->con);
  	$oAux->cad2id($id);
  	
  	$sql=<<<sql
  	delete from pregunta 
  	where pre_id=$oAux->pre_id and enc_id=$oAux->enc_id 
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
  	  $cadOrderby=" order by pre_nombre ";
  	else 
  	  $cadOrderby=$orderby;
  	
  	$sql=<<<cad
		select concat(enc_id,':',pre_id) as id,pre_nombre
		from pregunta
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by pre_nombre"));
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
  
  function listall($encId,$orderby)
  {
  	$sql=<<<mya
  	select enc_id,pre_id,pre_nombre 
  	from pregunta 
  	where 
  	enc_id=$encId 
  	$orderby
mya;
	$rs=&$this->con->Execute($sql);
	return($rs); 
  }
  
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=1;
	  if($ncampos==count($dato))
	  {
        $this->pre_nombre=$dato[0];

	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=1;
	  if($ncampos==count($dato))
	  {
        $this->pre_nombre=$dato[0];
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
  function adminAdmin($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$idPadre="0")
  {
	//mostrar la info de la encuesta
	$oEncuesta=new c_encuesta($this->con,"");
	$oEncuesta->info($idPadre);
  	$campo=array(
				array("etiqueta"=>" Encuesta","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$oEncuesta->enc_nombre),
				);

	$campo_hidden=array();
	//construye el html para los campos relacionados
	build_show($this->con,'false',"Encuesta","images/taskwrite.gif","50%",'true',$campo,$campo_hidden,$idPadre);
	echo"<br>";

  	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&idp=".$idPadre;
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">
	  <input type="hidden" name="idp" value="$idPadre">
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  <br>
va;
  	
  	$sql=<<<va
  	  select concat(p.enc_id,':',p.pre_id) as id1,p.pre_nombre,concat(p.enc_id,':',p.pre_id) as id2 
	  from pregunta p 
	  where p.enc_id='$idPadre' 
	  order by p.pre_id
va;
	$rs=&$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  $mainheaders=array("Elim.","Pregunta","Modificar");
	  $cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,
			'images/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");

	  $cextra="id_aplicacion|id_subaplicacion|principal|idp";
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
  	//mostrar la info de la encuesta
	$oEncuesta=new c_encuesta($this->con,"");
	$oEncuesta->info($request["idp"]);
  	$campo=array(
				array("etiqueta"=>" Encuesta","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$oEncuesta->enc_nombre),
				);

	$campo_hidden=array();
	//construye el html para los campos relacionados
	build_show($this->con,'false',"Encuesta","images/taskwrite.gif","50%",'true',$campo,$campo_hidden,$idPadre);
	echo"<br>";

    $campo=array(
				array("etiqueta"=>"* Pregunta","nombre"=>"tNombre","tipo_campo"=>"area","sql"=>"","valor"=>""),
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
					array("nombre"=>"principal","valor"=>$principal),
					array("nombre"=>"idp","valor"=>$request["idp"])
				);
	
	$cadForm=build_addCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&idp=".$request["idp"];
  	
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
function adminUpd($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$id,$request=null)  
  {
    $oAux=new c_pregunta($this->con);
  	$oAux->info($id);
  	
  	//mostrar la info de la encuesta
	$oEncuesta=new c_encuesta($this->con,"");
	$oEncuesta->info($oAux->enc_id);
  	$campo=array(
				array("etiqueta"=>" Encuesta","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$oEncuesta->enc_nombre),
				);

	$campo_hidden=array();
	//construye el html para los campos relacionados
	build_show($this->con,'false',"Encuesta","images/taskwrite.gif","50%",'true',$campo,$campo_hidden,$idPadre);
	echo"<br>";
  	
    $campo=array(
				array("etiqueta"=>"* Pregunta","nombre"=>"tNombre","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->pre_nombre),
				);
				
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"id","valor"=>$id),
			  		array("nombre"=>"idp","valor"=>$request["idp"]),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_updCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden,$id);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&idp=".$request["idp"];
	$param_Preguntas="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&idp=".$id;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Upd" value="Actualizar" onClick="return vValidar();">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
  		  <!-- resto de botones -->
  		  <input type="button" name="bRespuesta" value="Respuestas" onClick="opener.fOpenWindow('respuesta.php$param_Preguntas','Respuesta','800','550')">
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
  	  define('tNombre', 'string', 'pregunta',1,1000,document);
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