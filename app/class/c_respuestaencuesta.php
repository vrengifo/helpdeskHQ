<?php
/**
 * Administrar la tabla respuestaencuesta
 *
 */
include_once("class/c_interfaz.php");
include_once("class/c_encuesta.php");
include_once("class/c_servicio.php");
include_once("class/c_pregunta.php");
include_once("class/c_verdaderofalso.php");
include_once("class/c_ticket.php");

class c_respuestaencuesta implements c_interfaz 
{
  //atributos base
  var $tic_id;
  var $enc_id;
  var $pre_id;
  var $res_id;
  var $res_valor;
  
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
  	$this->enc_id=0;
  	$this->pre_id=0;
  	$this->res_id=0;
  	$this->borrar="";

  	$this->msg="";
  	$this->separador=":";
  }
  
  function id2cad($ticket,$encuesta,$pregunta)
  {
    $res=$ticket.$this->separador.$encuesta.$this->separador.$pregunta;
    return($res);
  }
  
  function cad2id($cad)
  {
    list($this->tic_id,$this->enc_id,$this->pre_id)=explode($this->separador,$cad);
    return ($cad);
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select tic_id,enc_id,pre_id 
	    from respuestaencuesta
	    where tic_id='$this->tic_id'
	    and enc_id='$this->enc_id'
	    and pre_id='$this->pre_id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="";
	}
	else 
	{
	  $res=$this->id2cad($rs->fields[0],$rs->fields[1],$rs->fields[2]);
	}
	return($res);
  }
  
  function info($id)
  { 
    $oAux=new c_respuestaencuesta($this->con);
    $oAux->cad2id($id);
    $sql=<<<vic
	    select tic_id,enc_id,pre_id,res_id,res_valor 
	    from respuestaencuesta
	    where tic_id=$oAux->tic_id and enc_id=$oAux->enc_id and pre_id=$oAux->pre_id
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->res_id=0;
	  $this->pre_id=0;
	}
	else 
	{
	  $this->tic_id=$rs->fields[0];
	  $this->enc_id=$rs->fields[1];
	  $this->pre_id=$rs->fields[2];
	  $this->res_id=$rs->fields[3];
	  $this->res_valor=$rs->fields[4];
	  
	  
	  $res=$id;
	}
	return($res);	
  }
  
  function add()
  {
  	$existe=$this->exist();
  	if($existe=="")
  	{
  	  $sql=<<<va
  	insert into respuestaencuesta
  	(tic_id,enc_id,pre_id,res_id,res_valor)
  	values
  	('$this->tic_id','$this->enc_id','$this->pre_id','$this->res_id','$this->res_valor')
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
  	  $cadOrderby=" order by borrar ";
  	else 
  	  $cadOrderby=$orderby;
  	
  	$sql=<<<cad
		select concat(enc_id,':',pre_id,':',res_id) as id,borrar
		from respuestaencuesta
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by borrar"));
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
  
  function listall($encuesta,$pregunta,$orderby)
  {
  	$sql=<<<mya
  	select enc_id,pre_id,res_id,borrar,res_valor  
  	from respuestaencuesta 
  	where 
  	enc_id=$encuesta and pre_id=$pregunta 
  	$orderby
mya;
	$rs=&$this->con->Execute($sql);
	return($rs); 
  }
  
  function cargar_dato($dato,$iou="i")			
  {
  	if($iou=="i")
  	{
  	  $ncampos=2;
	  if($ncampos==count($dato))
	  {
        $this->borrar=$dato[0];
        $this->res_valor=$dato[1];

	    $res=1;
	  }
	  else
	    $res=0;
  	}
  	if($iou=="u")
  	{
  	  $ncampos=2;
	  if($ncampos==count($dato))
	  {
        $this->borrar=$dato[0];
        $this->res_valor=$dato[1];
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
	$oPregunta=new c_pregunta($this->con);
	$oPregunta->info($idPadre);
	
	$oEncuesta=new c_encuesta($this->con,"");
	$oEncuesta->info($oPregunta->enc_id);
  	$campo=array(
				array("etiqueta"=>" Encuesta","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$oEncuesta->enc_nombre),
				array("etiqueta"=>" Pregunta","nombre"=>"clp1","tipo_campo"=>"text","sql"=>"","valor"=>$oPregunta->pre_nombre),
				);

	$campo_hidden=array();
	//construye el html para los campos relacionados
	build_show($this->con,'false',"Pregunta","images/taskwrite.gif","50%",'true',$campo,$campo_hidden,$idPadre);
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
  	  select concat(r.enc_id,':',r.pre_id,':',r.res_id) as id1,
  	  r.borrar,r.res_valor,
  	  concat(r.enc_id,':',r.pre_id,':',r.res_id) as id2 
	  from respuestaencuesta r 
	  where enc_id=$oPregunta->enc_id and pre_id=$oPregunta->pre_id
	  order by r.res_id
va;
	$rs=&$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  $mainheaders=array("Elim.","respuestaencuesta","Peso","Modificar");
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
	$oPregunta=new c_pregunta($this->con);
	$oPregunta->info($request["idp"]);
	
	$oEncuesta=new c_encuesta($this->con,"");
	$oEncuesta->info($oPregunta->enc_id);
  	$campo=array(
				array("etiqueta"=>" Encuesta","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$oEncuesta->enc_nombre),
				array("etiqueta"=>" Pregunta","nombre"=>"clp1","tipo_campo"=>"text","sql"=>"","valor"=>$oPregunta->pre_nombre),
				);

	$campo_hidden=array();
	//construye el html para los campos relacionados
	build_show($this->con,'false',"Pregunta","images/taskwrite.gif","50%",'true',$campo,$campo_hidden,$idPadre);
	echo"<br>";

    $campo=array(
				array("etiqueta"=>"* respuestaencuesta","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Peso","nombre"=>"tPeso","tipo_campo"=>"text","sql"=>"","valor"=>""),
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
    $oAux=new c_respuestaencuesta($this->con);
  	$oAux->info($id);
  	
  	$oPregunta=new c_pregunta($this->con);
  	$idPregunta=$oPregunta->id2cad($oAux->enc_id,$oAux->pre_id);
	$oPregunta->info($idPregunta);
	
	$oEncuesta=new c_encuesta($this->con,"");
	$oEncuesta->info($oPregunta->enc_id);
  	$campo=array(
				array("etiqueta"=>" Encuesta","nombre"=>"clp0","tipo_campo"=>"text","sql"=>"","valor"=>$oEncuesta->enc_nombre),
				array("etiqueta"=>" Pregunta","nombre"=>"clp1","tipo_campo"=>"text","sql"=>"","valor"=>$oPregunta->pre_nombre),
				);

	$campo_hidden=array();
	//construye el html para los campos relacionados
	build_show($this->con,'false',"Pregunta","images/taskwrite.gif","50%",'true',$campo,$campo_hidden,$idPadre);
	echo"<br>";
  	
    $campo=array(
				array("etiqueta"=>"* respuestaencuesta","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->borrar),
				array("etiqueta"=>"* Peso","nombre"=>"tPeso","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->res_valor),
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
	$param_respuestaencuestas=$param_destino;
  	
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
  	  define('tNombre', 'string', 'respuestaencuesta',1,100,document);
  	  define('tPeso', 'int', 'Peso',1,10,document);
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

  function calculaResultado() 
  {
  	$sql=<<<mya
  	select sum(res_valor) 
  	from respuestaencuesta 
  	where tic_id=$this->tic_id
mya;
	$rs=&$this->con->Execute($sql);
	$res=$rs->fields[0];
	
	$oTicket=new c_ticket($this->con);
	$oTicket->updateValorEncuesta($this->tic_id,$res);
	return ($this->tic_id);
  }
  
  function calculaMaximoResultado($ticket,$encuesta) 
  {
  	//insertar data en la tabla respuestaencuesta
  	$sqlPre=<<<mya
  	select distinct pre_id 
  	from pregunta 
  	where enc_id=$encuesta
mya;
	$rsPre=&$this->con->Execute($sqlPre);
	while(!$rsPre->EOF)
	{
		$pregunta=$rsPre->fields[0];
		$sqlRes=<<<mya
	select distinct res_id,res_peso 
	from respuesta
	where enc_id=$encuesta and pre_id=$pregunta
	order by res_peso desc	
mya;
		$rsRes=&$this->con->Execute($sqlRes);
		$respuesta=$rsRes->fields[0];
		$respuestaValor=$rsRes->fields[1];
		
		$this->tic_id=$ticket;
		$this->enc_id=$encuesta;
		$this->pre_id=$pregunta;
		$this->res_id=$respuesta;
		$this->res_valor=$respuestaValor;
		
		$this->add();
		
		$rsPre->MoveNext();
	}
  	
  	$this->tic_id=$ticket;
  	$this->calculaResultado();
	return ($this->tic_id);
  }
  
}
?>