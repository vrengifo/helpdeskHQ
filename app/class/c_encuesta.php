<?php
/**
 * Administrar la tabla encuesta
 *
 */
include_once("class/c_interfaz.php");
include_once("class/c_servicio.php");
include_once("class/c_verdaderofalso.php");

class c_encuesta implements c_interfaz 
{
  //atributos base
  var $enc_id;
  var $ser_id;
  var $enc_nombre;
  var $enc_descripcion;
  var $enc_activa;
  var $usu_audit;
  var $usu_faudit;

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
  function __construct(&$conDB,$usuario)
  {
  	$this->con=$conDB;
  	$this->enc_id=0;
  	$this->enc_nombre="";
  	
  	$this->enc_activa="1";
  	
  	$this->usu_audit=$usuario;
  	$this->usu_faudit=date("Y-m-d H:i:s");
  	 	
  	$this->msg="";
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select enc_id from encuesta
	    where enc_nombre='$this->enc_nombre'
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
	    select enc_id,enc_nombre,enc_descripcion,enc_activa,
	    ser_id,usu_audit,usu_faudit  
	    from encuesta
	    where enc_id=$id
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res=0;
	  $this->enc_id=0;
	  $this->enc_nombre="";
	}
	else 
	{
	  $this->enc_id=$rs->fields[0];
	  $this->enc_nombre=$rs->fields[1];
	  $this->enc_descripcion=$rs->fields[2];
	  $this->enc_activa=$rs->fields[3];
	  $this->ser_id=$rs->fields[4];
	  $this->usu_audit=$rs->fields[5];
	  $this->usu_faudit=$rs->fields[6];
	  
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
  	insert into encuesta
  	(enc_nombre,enc_descripcion,enc_activa,
  	ser_id,usu_audit,usu_faudit)
  	values
  	('$this->enc_nombre','$this->enc_descripcion','$this->enc_activa',
  	'$this->ser_id','$this->usu_audit','$this->usu_faudit')
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
  	$oAux=new c_encuesta($this->con,$this->usu_audit);
  	if($oAux->info($id)=="0")
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      //verificar nombre
      if($this->enc_nombre==$oAux->enc_nombre)
      {
        $datoNombre=$oAux->enc_nombre;
      }
      else 
      {
        $existe=$this->exist();
        if($existe=="0")
          $datoNombre=$this->enc_nombre;
        else 
          $datoNombre=$oAux->enc_nombre;  
      }
      $sql=<<<sql
        update encuesta set
        enc_nombre='$datoNombre',
        enc_descripcion='$this->enc_descripcion',
        enc_activa='$this->enc_activa',
        usu_audit='$this->usu_audit',
        usu_faudit='$this->usu_faudit' 
        where 
       	enc_id=$oAux->enc_id
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
  	delete from encuesta 
  	where enc_id=$id
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
  	  $cadOrderby=" order by enc_nombre ";
  	else 
  	  $cadOrderby=$orderby;
  	
  	$sql=<<<cad
		select enc_id,enc_nombre
		from encuesta
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by enc_nombre"));
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
        $this->enc_nombre=$dato[0];
	  	$this->enc_descripcion=$dato[1];
        $this->ser_id=$dato[2];
        $this->enc_activa=$dato[3];

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
	    $this->enc_nombre=$dato[0];
	  	$this->enc_descripcion=$dato[1];
        $this->ser_id=$dato[2];
        $this->enc_activa=$dato[3];
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
  	  select e.enc_id,e.enc_nombre,e.enc_descripcion,vf.verfal_sino,e.enc_id 
	  from encuesta e, verdaderofalso vf  
	  where vf.verfal_id=e.enc_activa 
	  order by e.enc_activa,e.enc_nombre
va;
	$rs=&$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  $mainheaders=array("Elim.","Encuesta","Descripcion","Activa","Modificar");
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
  	$oServicio=new c_servicio($this->con);
  	$sqlServicio=$oServicio->sqlSelect("");
  	$oVerfal=new c_verdaderofalso($this->con);
  	$sqlVerfal=$oVerfal->sqlSelectSiNo();
  	
    $campo=array(
				array("etiqueta"=>"* Encuesta","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Servicio","nombre"=>"tServicio","tipo_campo"=>"select","sql"=>$sqlServicio,"valor"=>""),
				array("etiqueta"=>"* Activa","nombre"=>"tActiva","tipo_campo"=>"select","sql"=>$sqlVerfal,"valor"=>""),
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
    $oAux=new c_encuesta($this->con,$this->usu_audit);
  	$oAux->info($id);
	$oServicio=new c_servicio($this->con);
  	$sqlServicio=$oServicio->sqlSelect("");
  	$oVerfal=new c_verdaderofalso($this->con);
  	$sqlVerfal=$oVerfal->sqlSelectSiNo();
  	
    $campo=array(
				array("etiqueta"=>"* Encuesta","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->enc_nombre),
				array("etiqueta"=>"* Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>$oAux->enc_descripcion),
				array("etiqueta"=>"* Servicio","nombre"=>"tServicio","tipo_campo"=>"select","sql"=>$sqlServicio,"valor"=>$oAux->ser_id),
				array("etiqueta"=>"* Activa","nombre"=>"tActiva","tipo_campo"=>"select","sql"=>$sqlVerfal,"valor"=>$oAux->enc_activa),
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
	$param_Preguntas=$param_destino."&idp=".$id;
  	
	$cadValidaForma=$this->validaJs();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Upd" value="Actualizar" onClick="return vValidar();">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">
  		  <!-- resto de botones -->
  		  <input type="button" name="bPregunta" value="Preguntas" onClick="fOpenWindow('pregunta.php$param_Preguntas','Preguntas','800','550')">
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
  	  define('tNombre', 'string', 'encuesta',1,100,document);
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
  
  function obtenerEncuestaXServicio($servicio)
  {
  	$this->msg="";
  	$sql=<<<mya
  	select enc_id 
  	from encuesta 
  	where ser_id=$servicio
mya;
	$rs=&$this->con->Execute($sql);
	if(!$rs->EOF)
	{
	  $res=$rs->fields[0];	
	}
	else 
	{
	  $sqlAll=<<<mya
  	select enc_id 
  	from encuesta 
  	where ser_id='0'
mya;
	  $rsAll=&$this->con->Execute($sqlAll);
	  if($rsAll->EOF)
	  {
	    $res=0;	
	    $this->msg="No existe encuesta para el servicio, crearla!!!";
	  }
	  else 
	    $res=$rsAll->fields[0];
	}
	return($res);
  }
}
?>