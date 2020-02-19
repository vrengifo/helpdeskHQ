<?php
/**
 * Administrar la tabla ticket
 *
 */
include_once("class/c_interfaz.php");
include_once("adodb/tohtml.inc.php");
include_once("class/c_tipoticket.php");
//include_once("class/c_tipoestado.php");
include_once("class/c_prioridad.php");
include_once("class/c_servicio.php");
include_once("class/c_prioridadxservicio.php");
include_once("class/c_horario.php");
include_once("class/c_horarioxanalista.php");
include_once("class/c_logticket.php");
include_once("class/c_documentoxticket.php");
include_once("class/c_parametro.php");

class c_ticket implements c_interfaz 
{
  //atributos base
  var $tic_id;
  var $usu_id;  
  var $tiptic_id;
  var $pri_id;
  var $ser_id;
  var $tic_resumen;
  var $tic_descripcion;
  
  var $tic_fechahoraapertura;
  var $tic_fechahoraultmodificacion;
  var $tic_fechasolucion;
  var $tic_fechacierre;
  var $tic_valorencuesta;
  var $usu_asignado;
  var $tipest_id;
  var $tic_fechahorainicio;
  var $tic_fechahorafin;
  var $tic_fechahoramaxencuesta;
  
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
  	$this->tic_id=0;
  	$this->tiptic_id="";
  	$this->usu_id="";
  	$this->pri_id="";
  	$this->ser_id="";
  	$this->tic_resumen="";
  	$this->tic_descripcion="";
  	$this->tic_fechahoraapertura=date("Y-m-d H:i:s");
  	
  	$this->msg="";
  }
  
  function exist()
  { 
    $sql=<<<vic
	    select tic_id from ticket
	    where tic_resumen='$this->tic_resumen' 
	    and tic_descripcion='$this->tic_descripcion' 
	    and tic_fechahoraapertura='$this->tic_fechahoraapertura' 
	    and usu_id='$this->usu_id' 
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="";
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
	    select tic_id,usu_id,tiptic_id,pri_id,
	    ser_id,tic_resumen,tic_descripcion,tic_fechahoraapertura,
	    tic_fechahoraultmodificacion,tic_fechasolucion,tic_fechacierre,
	    tic_valorencuesta,usu_asignado,tipest_id,tic_fechahorainicio,
	    tic_fechahorafin  
	    from ticket
	    where tic_id='$id'
vic;

	$rs=$this->con->Execute($sql);
	if($rs->EOF)
	{
	  $res="0";
	  $this->tic_id="0";
	  $this->tiptic_id="";
	  $this->usu_id="";
	  $this->pri_id="";
	  $this->ser_id="";
	  $this->tic_resumen="";
	  $this->tic_descripcion="";
	  $this->tic_fechahoraapertura="";
	}
	else 
	{
	  $this->tic_id=$rs->fields[0];
	  $this->usu_id=$rs->fields[1];
	  $this->tiptic_id=$rs->fields[2];
	  $this->pri_id=$rs->fields[3];
	  $this->ser_id=$rs->fields[4];
	  $this->tic_resumen=$rs->fields[5];
	  $this->tic_descripcion=$rs->fields[6];
	  $this->tic_fechahoraapertura=$rs->fields[7];
	  
	  $this->tic_fechahoraultmodificacion=$rs->fields[8];
	  $this->tic_fechasolucion=$rs->fields[9];
	  $this->tic_fechacierre=$rs->fields[10];
	  $this->tic_valorencuesta=$rs->fields[11];
	  $this->usu_asignado=$rs->fields[12];
	  $this->tipest_id=$rs->fields[13];
	  $this->tic_fechahorainicio=$rs->fields[14];
	  $this->tic_fechahorafin=$rs->fields[15];
	  
	  $res=$id;
	}
	return($res);	
  }
  

  function add()
  {
  	/*
  	$existe=$this->exist();
  	if(strlen($existe)==0)
  	{
  	  $sql=<<<va
  	insert into ticket 
  	(tiptic_id,usu_id,pri_id,
  	ser_id,tic_resumen,tic_descripcion,tic_fechahoraapertura)
  	values
  	('$this->tiptic_id','$this->usu_id','$this->pri_id',
  	'$this->ser_id','$this->tic_resumen','$this->tic_descripcion','$this->tic_fechahoraapertura')
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
	*/
  	return($res);
  }
  
  function fechaSumaDia($fecha,$nroDias)
  {
	$sqlRes=<<<mya
	select adddate(date('$fecha'),$nroDias)
mya;
	  $rsRes=&$this->con->Execute($sqlRes);
	  return($rsRes->fields[0]);
  }
  
  /**
   * Suma a una fecha el tiempo '<dia> <hh>:<mm>:<ss>'
   *
   * @param date $fecha
   * @param time $tiempo
   * @return datetime
   */
  function fechaSumaTiempo($fecha,$tiempo)
  {
	$sqlRes=<<<mya
	select addtime('$fecha','$tiempo')
mya;
	  $rsRes=&$this->con->Execute($sqlRes);
	  return($rsRes->fields[0]);
  }
  
  /**
   * Resta entre $dt1 y $dt2
   *
   * @param datetime $dt1
   * @param datetime $dt2
   * @return time
   */
  function fechaRestaTiempo($dt1,$dt2)
  {
    $sql=<<<mya
    select timediff('$dt1','$dt2')
mya;
	$rs=&$this->con->Execute($sql);
	return($rs->fields[0]);
  }
  
  function retornaObjHorario($analista)
  {
  	$oHorarioAnalista=new c_horarioxanalista($this->con,$analista);
	$oHorario=new c_horario($this->con);
	
	$oHorarioAnalista->info($analista);
	$oHorario->info($oHorarioAnalista->hor_id);
	return($oHorario);
  }
  
  /**
   * Calcula la fecha y hora de inicio y fin en base a los horarios del usuario
   *
   * @param string $analista
   * 
   */
  function calculaFHInicioFin($analista)
  {
	/*
  	$oHorarioAnalista=new c_horarioxanalista($this->con,$analista);
	$oHorario=new c_horario($this->con);
	
	$oHorarioAnalista->info($analista);
	$oHorario->info($oHorarioAnalista->hor_id);
	*/
	$oHorario=new c_horario($this->con);
	$oHorario=$this->retornaObjHorario($analista);
	
	
	$this->tic_fechahorainicio=$this->calculaFechaInicio($this->tic_fechahoraapertura,$oHorarioAnalista->hor_id,$oHorario->hor_inicio);
	
	//fechahora fin
	$oPrixSer=new c_prioridadxservicio($this->con);
	$nroHorasMaxTicket=$this->transformaNumeroHora2Hora($oPrixSer->getNroHoras($this->pri_id,$this->ser_id));
	$this->tic_fechahorafin=$this->calculaFechaFin($this->tic_fechahorainicio,$nroHorasMaxTicket,$oHorario->hor_inicio,$oHorario->hor_fin);
	
  }
  
  function transformaNumeroHora2Hora($nroHoras)
  {
  	$res=$nroHoras.":00:00";
  	return($res);
  }
  
  function transformaHora2Numero($hora)
  {
  	list($auxHora,$auxMinuto,$auxSegundo)=explode(":",$hora);
  	$res=$auxHora + ($auxMinuto/60) + ($auxSegundo/3600);
  }
  
  
  /**
   * Retorna 1 si la$fechaHoraTicket esta dentro de hor_inicio y hor_fin; 0 cc
   *
   * @param datetime $fechaHoraTicket
   * @param int $horarioId
   * @param time $horaInicio
   * @return int
   */
  function enHorario($fechaHoraTicket,$horarioId,$horaInicio)
  {
    $sql=<<<mya
select hor_id 
from horario 
where hor_id='$horarioId' and 
time('$fechaHoraTicket') between hor_inicio and hor_fin
mya;
	$rs=&$this->con->Execute($sql);
	if($rs->EOF)
	{
		$res=0;
	}
	else 
	{
		$res=1;
	}
	return($res);
  }
  
  /**
   * Retorna 0 si es un dia entre lunes y viernes
   * 1 si el dia es domingo, 2 si el dia es sabado
   * e indica el nro de Dias que deben anadirse para
   * la fecha de inicio
   *
   * @param datetime $fecha
   * @return int
   */
  function esDiaLaboral($fecha)
  {
  	$sql=<<<mya
  	select weekday('$fecha')
mya;
	$rs=&$this->con->Execute($sql);
	// 0 = lunes; 4 viernes; 
	$dia=$rs->fields[0];
	if($dia>4)
	{
	  $res=7-$dia;
    }
    else 
      $res=0;
    return($res);
  }
  
  function calculaFechaInicio($fechaApertura,$horarioId,$horaInicio)
  {
  	
  	$nroDias=$this->esDiaLaboral($fechaApertura);
  	
  	if ($nroDias>0) 
  	{
  	  $res=$this->fechaSumaDia($fechaApertura,$nroDias)." ".$horaInicio;
  	}
  	else 
  	{
  	  $enHorario=$this->enHorario($fechaApertura,$horarioId,$horaInicio);
  	  if($enHorario)
  	  {
  	  	$res=$fechaApertura;
	  }
	  else 
	  {
	  	$res=$this->fechaSumaDia($fechaApertura,1)." ".$horaInicio;
	  	$nroDias1=$this->esDiaLaboral($res);
	  	if ($nroDias1>0) 
	  	{
	  	  $res=$this->fechaSumaDia($res,$nroDias1)." ".$horaInicio;
	  	}
	  }
    }
    return($res);
  }
  
  /**
   * A partir del string aaaa-mm-dd hh:ii:ss obtener solo la fecha
   *
   * @param datetime $fechaHora
   * @return date
   */
  function obtenerFecha($fechaHora)
  {
  	list($auxFecha,$auxHora)=explode(" ",$fechaHora);
  	return($auxFecha);
  }
  
  /**
   * A partir del string aaaa-mm-dd hh:ii:ss obtener solo la hora
   *
   * @param datetime $fechaHora
   * @return time
   */
  function obtenerHora($fechaHora)
  {
  	list($auxFecha,$auxHora)=explode(" ",$fechaHora);
  	return($auxHora);
  }
  
  function calculaFechaFin($fechaInicio,$horas,$horaIni,$horaFin)
  {
  	$flagFalta=0;
  	do 
  	{
  		$auxFin=$this->fechaSumaTiempo($fechaInicio,$horas);
  		$auxTopeDia=$this->obtenerFecha($fechaInicio)." ".$horaFin;
  		$auxFalta=$this->fechaRestaTiempo($auxTopeDia,$auxFin);
  		
  		$signoAuxFalta=substr($auxFalta,0,1);
  		
  		if($this->con->debug==true)
  		{
  			$cad=<<<mya
auxFin ($auxFin) = this->fechaSumaTiempo($fechaInicio,$horas) <br>
auxTopeDia ($auxTopeDia) = this->obtenerFecha($fechaInicio) $horaFin; <br>
auxFalta ($auxFalta) = this->fechaRestaTiempo($auxTopeDia,$auxFin); <br>
signoAuxFalta ($signoAuxFalta)= substr($auxFalta,0,1);
mya;
		  echo $cad;
  		}
  		
  		if($signoAuxFalta=="-")
  		{
  	  		$flagFalta=1;
  	  		$fechaInicio=$this->fechaSumaDia($this->obtenerFecha($fechaInicio),1)." $horaIni";
  	  		$horas=substr($auxFalta,1);
  		}
  		else 
  		{
  			
  			$flagFalta=0;
  		}
  		if($this->con->debug==true)
  		{
  		  echo"flagFalta: $flagFalta <br>";	
		}
  	  
  	}while ($flagFalta);
  	
  	if($this->con->debug==true)
  	{
  		echo"auxFin: $auxFin <br>";	
	}
  	
	//nroDias si respuesta es dia no laboral
	list($auxF,$auxH)=explode(" ",$auxFin);
	$nroDias=$this->esDiaLaboral($auxFin);
  	
  	if ($nroDias>0) 
  	{
  	  if($nroDias==1)
  	    $nroDias=2;	
  	  $res=$this->fechaSumaDia($auxFin,$nroDias)." ".$auxH;
  	}
  	else 
  	  $res=$auxFin;
	
	if($this->con->debug==true)
  	{
  		echo"res: $res <br>";	
	}
		
  	return($res);
  }
  
  /**
   * Busca el analista al que va a asignar el ticket
   *
   * @return string
   */
  function asignaAnalista()
  {
  	$sqlDel=<<<mya
   	delete from tAsignacion
mya;
	$rsDel=&$this->con->Execute($sqlDel);
  	
  	$sql=<<<mya
  	insert into tAsignacion (usu_asignado,valoracion)
  	select t.usu_asignado,sum(1*p.pri_nivel) valoracion
	from ticket t, prioridad p
	where p.pri_id=t.pri_id
	and t.tipest_id not in ('CA','CE') 
	and t.ser_id=$this->ser_id 
	group by t.usu_asignado
mya;
	$rs=&$this->con->Execute($sql);
	
	$sqlAnalistaUsuario=<<<mya
  	insert into tAsignacion (usu_asignado,valoracion)
  	select distinct usu_id,0 valoracion
	from analistaxservicio 
	where ser_id=$this->ser_id 
	and usu_id not in (select distinct usu_asignado from tAsignacion )
mya;
	$rs=&$this->con->Execute($sqlAnalistaUsuario);
	
	$sqlA=<<<mya
	select usu_asignado, valoracion 
	from tAsignacion 
	order by valoracion asc
mya;
	$rsA=&$this->con->Execute($sqlA);
	if($rsA->EOF)
	  $res="soporte";
	else 
	  $res=$rsA->fields[0];  
	
  	return($res);
  }
  
  /**
   * Crear Ticket
   *
   * @return unknown
   */
  function addCliente()
  {
  	$existe=$this->exist();
  	if(strlen($existe)==0)
  	{
  	  $this->usu_asignado=$this->asignaAnalista();
  	  $this->calculaFHInicioFin($this->usu_asignado);
  		
  	  $sql=<<<va
  	insert into ticket 
  	(usu_id,tiptic_id,pri_id,ser_id,
  	tic_resumen,tic_descripcion,tic_fechahoraapertura,usu_asignado,
  	tipest_id,tic_fechahorainicio,tic_fechahorafin 
  	
  	)
  	values
  	('$this->usu_id','$this->tiptic_id','$this->pri_id','$this->ser_id',
  	'$this->tic_resumen','$this->tic_descripcion','$this->tic_fechahoraapertura','$this->usu_asignado',
  	'A','$this->tic_fechahorainicio','$this->tic_fechahorafin')
va;
  	  $rs=&$this->con->Execute($sql);
  	  if($rs)
  	  {
  	    $res=$this->exist();
  	    $log=new c_logticket($this->con,$this->usu_id);
  	    $log->creaTicket($res);
  	    $this->msg="Se ha creado su ticket satisfactoriamente!!!";
  	    //enviar mail a usuario
  	    $cadMailUsuario=<<<mya
  Se ha creado su ticket con la siguiente informacion: <br>	    
  Resumen: $this->tic_resumen
  <br>
  Descripcion:<br>
  $this->tic_descripcion
  <br>Fecha Inicio Solucion: $this->tic_fechahorainicio
  <br>Fecha Max. Solucion: $this->tic_fechahorafin
  <br>Soporte Asignado: $this->usu_asignado   
mya;
		$this->envioMail($res,$cadMailUsuario,$this->usu_id);
  	    //enviar mail a soporte
  	    $cadMailSoporte=<<<mya
  Helpdesk ha asignado a usted el siguiente ticket # $res: 
  Resumen: $this->tic_resumen
  <br>
  Descripcion:<br>
  $this->tic_descripcion
  <br>Fecha Inicio Solucion: $this->tic_fechahorainicio
  <br>Fecha Max. Solucion: $this->tic_fechahorafin
  <br>Soporte Asignado: $this->usu_asignado   
mya;
		$this->envioMail($res,$cadMailSoporte,$this->usu_asignado);
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
  	/*
  	$oAux=new c_ticket($this->con);
  	if(!$oAux->info($id))
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      $sql=<<<sql
        update ticket set
        tiptic_id='$this->tiptic_id',
        usu_id='$this->usu_id',
        pri_id='$this->pri_id', 
        ser_id='$this->ser_id', 
		tic_fechahoraapertura='$this->tic_fechahoraapertura'
        where 
        tic_id='$id'
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
    */
  	return($id);
  }
  
  function updateTransferirTicket($id,$usuario,$comentario)
  {
  	$oAux=new c_ticket($this->con);
  	if(!$oAux->info($id))
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      $sql=<<<sql
        update ticket set
        usu_asignado='$usuario',
        tic_fechahoraultmodificacion=now()  
        where 
        tic_id='$id'
sql;
      $rs=&$this->con->Execute($sql);
      
      $oLog=new c_logticket($this->con,$usuario);
      $oLog->transferirTicket($id,"De ".$oAux->usu_asignado." a ".$usuario." \n ".$comentario);
      
  	  //enviar mail a usuario
  	    $cadMail=<<<mya
  Le han transferido el ticket # $id con la siguiente informacion: <br>
  Soporte Inicial: $oAux->usu_asignado
  <br>Comentario:<br>
  $comentario
  <br>Fecha Inicio Solucion: $oAux->tic_fechahorainicio
  <br>Fecha Max. Solucion: $oAux->tic_fechahorafin
mya;
		$this->envioMail($id,$cadMail,$usuario);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function updateComentarioUsuario($id,$usuario,$comentario)
  {
  	$oAux=new c_ticket($this->con);
  	if(!$oAux->info($id))
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      $sql=<<<sql
        update ticket set
        tic_fechahoraultmodificacion=now()  
        where 
        tic_id='$id'
sql;
      $rs=&$this->con->Execute($sql);
    	
      $oLog=new c_logticket($this->con,$usuario);
      $oLog->comentarioUsuario($id,$comentario);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function updateActualizarEstado($id,$usuario,$estado,$comentario,$palabras)
  {
  	$oAux=new c_ticket($this->con);
  	if(!$oAux->info($id))
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      $sql=<<<sql
        update ticket set
        tipest_id='$estado',
        tic_fechahoraultmodificacion=now() 
        where 
        tic_id='$id'
sql;
	  $flagMail=0;	
      if(($estado=="CA")||($estado=="CE"))
      {
    	$this->con->debug=true;
      	
      	$flagEncuesta=0;
    	$this->tic_fechacierre=date("Y-m-d H:i:s");
    	$this->tic_fechahoraultmodificacion=$this->tic_fechacierre;
    	
    	$cadFHMEncuesta="";
    	
      	if($estado=="CA"){$msgEstado="Cancelado";};
    	if($estado=="CE")
    	{
    		$msgEstado="Cerrado/Resuelto";
    		$flagEncuesta=1;
    		//calcular la tic_fechahoramaxencuesta
    		$oHorario=new c_horario($this->con);
			$oHorario=$this->retornaObjHorario($oAux->usu_asignado);
    		$this->tic_fechahoramaxencuesta=$this->calculaFechaFin($this->tic_fechacierre,"16:00:00",$oHorario->hor_inicio,$oHorario->hor_fin);
    		$cadFHMEncuesta=<<<mya
,tic_fechahoramaxencuesta='$this->tic_fechahoramaxencuesta' 
mya;
    		
    	}
    	
      	$sql=<<<sql
        update ticket set
        tipest_id='$estado',
        tic_fechacierre='$this->tic_fechacierre',
        tic_fechahoraultmodificacion='$this->tic_fechahoraultmodificacion' 
        $cadFHMEncuesta 
        where 
        tic_id='$id'
sql;
		$flagMail=1;
      }
      
      $rs=&$this->con->Execute($sql);
      
      $oLog=new c_logticket($this->con,$usuario);
      $oLog->actualizarEstado($id,$estado,$comentario,$palabras);
      
      $oPar=new c_parametro($this->con);
      $oPar->info();
      $cadEncuesta="";
      if($flagEncuesta)
      {
      $cadEncuesta=<<<mya
    <br>Favor le solicitamos resolver la siguiente encuesta para evaluar el servicio:
    <a href="$oPar->par_homesite/encuestaR.php?ticId=$id">Click para resolver encuesta</a>
    <br>Es importante su evaluacion y debe ser realizada hasta $this->tic_fechahoramaxencuesta
mya;
      }
      
      if($flagMail)
      {
      	$oAux->info($id);
      	//enviar mail a usuario
  	    $cadMail=<<<mya
  El ticket # $id ha sido $msgEstado: <br>
  Soporte : $oAux->usu_asignado
  <br>Comentario/Solucion:<br>
  $comentario
  <br>Fecha Inicio Solucion: $oAux->tic_fechahorainicio
  <br>Fecha Max. Solucion: $oAux->tic_fechahorafin
  <br>Fecha Finalizacion/Cierre: $oAux->tic_fechacierre
  <br>$cadEncuesta
mya;
		$this->envioMail($id,$cadMail,$oAux->usu_id);
      }
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($id);
  }
  
  function updateCargarDocumento($id,$usuario,$nombre,$descripcion,$path="ticketDoc/")
  {
  	$oAux=new c_ticket($this->con);
  	if(!$oAux->info($id))
  	{
  	  $this->msg="Dato no existe y no se puede actualizar";
    }
    else 
    {
      $oDoc=new c_documentoxticket($this->con,$usuario);
      $oDoc->tic_id=$id;
      $oDoc->doc_nombre=$nombre;
      $oDoc->doc_descripcion=$descripcion;
      $oDoc->doc_path=$path;
      
      $oDoc->add();
    	
    	$sql=<<<sql
        update ticket set
        tic_fechahoraultmodificacion=now()  
        where 
        tic_id='$id'
sql;
      $rs=&$this->con->Execute($sql);
      
      if(!$rs)
        $this->msg="Error al ejecutar sentencia de actualización";

    }
  	return($oDoc->doc_nombre);
  }
  
  function del($id)
  {
  	/*
  	if($id>0)
  	{
  	  $oAux=new c_ticket($this->con);
  	  $oAux->info($id);
  	
  	  $sql=<<<sql
  	delete from ticket 
  	where tic_id='$id'
sql;
      $rs=&$this->con->Execute($sql);
  	}
  	*/
    return($id);
  }
  
  function __destruct()
  {
  	
  }
  
  function sqlSelect($orderby,$conRaiz=0)
  {
  	if(strlen($orderby)==0)
  	  $cadOrderby=" order by tic_id ";
  	else 
  	  $cadOrderby=$orderby;  
  	$cadRaiz="";  
  	if($conRaiz==0)
  	  $cadRaiz=<<<mya
  	where tic_id>0 
mya;
  	
  	$sql=<<<cad
		select tic_id,concat(pri_id,' ; PN:',tic_resumen,' ; SN:',tic_descripcion) texto
		from ticket 
		$cadRaiz
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
  	
  	$rs=&$this->con->Execute($this->sqlSelect("order by pri_id,tic_resumen,tic_descripcion",1));
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
  	  $ncampos=5;
  	  //tTipoTicket|tPrioridad|tServicio|tResumen|tDescripcion
	  if($ncampos==count($dato))
	  {
        $this->tiptic_id=$dato[0];
	    $this->pri_id=$dato[1];
	    $this->ser_id=$dato[2];
	    $this->tic_resumen=$dato[3];
	    $this->tic_descripcion=$dato[4];
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
	    $this->tiptic_id=$dato[0];
	    $this->usu_id=$dato[1];
	    $this->pri_id=$dato[2];
	    $this->ser_id=$dato[3];
	    $this->tic_resumen=$dato[4];
	    $this->tic_descripcion=$dato[5];
	    $this->tic_fechahoraapertura=$dato[6];
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
  function adminAdminMiTicket($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$post=NULL)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&mtu=".$post["sUsername"];
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <!--
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  -->
  	  <br>
va;
  	$aSql="";
  	if(strlen($post["sUsername"])>0)
  	  $aSql=" and u1.usu_id='".$post["sUsername"]."' ";
  	
  	$sql=<<<va
  	select t.tic_id as id1,
	u1.usu_nombre,tt.tiptic_nombre,p.pri_nombre,s.ser_nombre,t.tic_resumen,t.tic_fechahoraapertura,t.tic_fechahorainicio,te.tipest_nombre
,t.tic_id as id2
	from ticket t, usuario u1, usuario u2, tipoticket tt, prioridad p, servicio s, tipoestado te
	where
	u1.usu_id=t.usu_id
	and u2.usu_id=t.usu_asignado
	and tt.tiptic_id=t.tiptic_id
	and p.pri_id=t.pri_id
	and s.ser_id=t.ser_id
	and te.tipest_id=t.tipest_id
	$aSql 
	order by t.tic_fechahoraapertura desc 
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("# Ticket","Cliente","Tipo Ticket","Prioridad","Servicio","Resumen","Fecha Apertura","Fecha Inicio Atencion","Estado","Ver");
	  //$cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,'images/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");
			
	  $cad.=build_table_sindelCad($rs,false,$mainheaders,$titulo,'images/yearview.gif','70%','true',$destUpdate,$param_destino,"total",1,"verTicket",'800','500');		
			
	  //variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
	  $cextra="id_aplicacion|id_subaplicacion|principal";
	}
	
	$cad.=<<<va
	  <input type="hidden" name="cextra" value="$cextra">
	</form>
va;
	return($cad);
  }
  
  function adminAdminAsignado($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$post=NULL)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&mtu=".$post["sUsername"];
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <!--
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  -->
  	  <br>
va;
  	$aSql="";
  	if(strlen($post["sUsername"])>0)
  	  $aSql=" and t.usu_asignado='".$post["sUsername"]."' ";
  	
  	$sql=<<<va
  	select t.tic_id as id1,
	u1.usu_nombre,tt.tiptic_nombre,p.pri_nombre,s.ser_nombre,t.tic_resumen,t.tic_fechahoraapertura,t.tic_fechahorainicio,te.tipest_nombre
,t.tic_id as id2
	from ticket t, usuario u1, usuario u2, tipoticket tt, prioridad p, servicio s, tipoestado te
	where
	u1.usu_id=t.usu_id
	and u2.usu_id=t.usu_asignado
	and tt.tiptic_id=t.tiptic_id
	and p.pri_id=t.pri_id
	and s.ser_id=t.ser_id
	and te.tipest_id=t.tipest_id 
	and te.tipest_id not in ('CE','CA')
	$aSql 
	order by t.tic_fechahoraapertura desc 
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("# Ticket","Cliente","Tipo Ticket","Prioridad","Servicio","Resumen","Fecha Apertura","Fecha Inicio Atencion","Estado","Trabajar con");
	  //$cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,'images/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");
			
	  $cad.=build_table_sindelCad($rs,false,$mainheaders,$titulo,'images/yearview.gif','70%','true',$destUpdate,$param_destino,"total",1,"verTicket",'800','500');		
			
	  //variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
	  $cextra="id_aplicacion|id_subaplicacion|principal";
	}
	
	$cad.=<<<va
	  <input type="hidden" name="cextra" value="$cextra">
	</form>
va;
	return($cad);
  }
  
  function adminAdminCerrado($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$post=NULL)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&mtu=".$post["sUsername"];
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <!--
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  -->
  	  <br>
va;
  	$aSql="";
  	if(strlen($post["sUsername"])>0)
  	  $aSql=" and t.usu_asignado='".$post["sUsername"]."' ";
  	
  	$sql=<<<va
  	select t.tic_id as id1,
	u1.usu_nombre,tt.tiptic_nombre,p.pri_nombre,s.ser_nombre,t.tic_resumen,t.tic_fechahoraapertura,t.tic_fechahorainicio,te.tipest_nombre
,t.tic_id as id2
	from ticket t, usuario u1, usuario u2, tipoticket tt, prioridad p, servicio s, tipoestado te
	where
	u1.usu_id=t.usu_id
	and u2.usu_id=t.usu_asignado
	and tt.tiptic_id=t.tiptic_id
	and p.pri_id=t.pri_id
	and s.ser_id=t.ser_id
	and te.tipest_id=t.tipest_id 
	and te.tipest_id not in ('CE','CA')

	order by t.tic_fechahoraapertura desc 
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("# Ticket","Cliente","Tipo Ticket","Prioridad","Servicio","Resumen","Fecha Apertura","Fecha Inicio Atencion","Estado","Ver");
	  //$cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,'images/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");
			
	  $cad.=build_table_sindelCad($rs,false,$mainheaders,$titulo,'images/yearview.gif','70%','true',$destUpdate,$param_destino,"total",1,"verTicket",'800','500');		
			
	  //variable con campos extras, son los usados como id_aplicacion,id_subaplicacion
	  $cextra="id_aplicacion|id_subaplicacion|principal";
	}
	
	$cad.=<<<va
	  <input type="hidden" name="cextra" value="$cextra">
	</form>
va;
	return($cad);
  }
  
  function adminAdminSolucionProblemas($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$post=NULL)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&mtu=".$post["sUsername"];
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <!--
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  -->
  	  <br>
va;
  	$aSql="";
  	if(strlen($post["bTicket"])>0)
  	  $aSql=" and t.tic_id='".$post["bTicket"]."' ";
  	$bSql="";  
  	if((strlen($post["bServicio"])>0)&&($post["bServicio"]!="0"))
  	  $bSql=" and t.ser_id='".$post["bServicio"]."' ";
  	$cSql="";
  	if(strlen($post["bPalabra"])>0)
  	{
  	  $auxPal=$post["bPalabra"];
  	  $cSql=<<<mya
	and
	(
  	t.tic_resumen like '%$auxPal%'
  	or t.tic_descripcion like '%$auxPal%'
  	or l.logtic_comentario like '%$auxPal%'
 	or l.logtic_palabraclave like '%$auxPal%'
	)  	  
mya;
	}
  	
	//select t.tic_id,s.ser_nombre,t.tic_resumen,t.tic_descripcion,t.tic_fechacierre,l.logtic_comentario,l.logtic_palabraclave,t.tic_id
  	$sql=<<<va
  	select t.tic_id,s.ser_nombre,concat(substring(t.tic_resumen,1,80),'...'),t.tic_fechacierre,concat(substring(l.logtic_comentario,1,100),'...'),t.tic_id
	from ticket t,servicio s,logticket l
	where s.ser_id=t.ser_id
	and t.tipest_id='CE'
	and l.tic_id=t.tic_id
	and l.tipacc_id='S' and l.tipest_id='CE' 
	$aSql 
	$bSql
  	$cSql
	order by t.tic_fechacierre,t.tic_id 
va;
  	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("# Ticket","Servicio","Resumen","Descripci&oacute;n","Fecha Soluci&oacute;n","Comentario","Palabras Clave","Ver");
	  $mainheaders=array("# Ticket","Servicio","Resumen","Fecha Soluci&oacute;n","Comentario","Ver");
			
	  $cad.=build_table_sindelCad($rs,false,$mainheaders,$titulo,'images/yearview.gif','70%','false',$destUpdate,$param_destino,"total",1,"verTicket",'800','500');
		
			
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
	$oTipoticket=new c_tipoticket($this->con);
	$oPadre=new c_ticket($this->con);
	
    $campo=array(
				array("etiqueta"=>"* Tipo ticket","nombre"=>"tTipo","tipo_campo"=>"select","sql"=>$oTipoticket->sqlSelect(""),"valor"=>""),
				array("etiqueta"=>"* Padre","nombre"=>"tPadre","tipo_campo"=>"select","sql"=>$oPadre->sqlSelect("",1),"valor"=>""),
				array("etiqueta"=>"* Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* PN","nombre"=>"tPn","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* SN","nombre"=>"tSN","tipo_campo"=>"text","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Activo","nombre"=>"tMail","tipo_campo"=>"text","sql"=>"","valor"=>"")
				
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
  
  function formaCrearTicket($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$arr=null)
  {
	$oTipoticket=new c_tipoticket($this->con);
	$oPriorididad=new c_prioridad($this->con);
	$oServicio=new c_servicio($this->con);

	
    $campo=array(
				array("etiqueta"=>"* Tipo Ticket","nombre"=>"tTipoTicket","tipo_campo"=>"select","sql"=>$oTipoticket->sqlSelect(""),"valor"=>""),
				array("etiqueta"=>"* Prioridad","nombre"=>"tPrioridad","tipo_campo"=>"select","sql"=>$oPriorididad->sqlSelect(""),"valor"=>""),
				array("etiqueta"=>"* Servicio","nombre"=>"tServicio","tipo_campo"=>"select","sql"=>$oServicio->sqlSelect(""),"valor"=>""),
				array("etiqueta"=>"* Resumen","nombre"=>"tResumen","tipo_campo"=>"area","sql"=>"","valor"=>""),
				array("etiqueta"=>"* Descripcion","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>""),
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"tUsuario","valor"=>$arr["mtu"]),
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
  
  function formaCrearComentario($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$arr=null)
  {
	$oTipoticket=new c_tipoticket($this->con);
	$oPriorididad=new c_prioridad($this->con);
	$oServicio=new c_servicio($this->con);

	
    $campo=array(
				array("etiqueta"=>"* Comentario","nombre"=>"tComentario","tipo_campo"=>"area","sql"=>"","valor"=>""),
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"tUsuario","valor"=>$arr["mtu"]),
			  		array("nombre"=>"id","valor"=>$arr["id"]),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_addCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJsCrearComentario();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Add" value="Añadir" onClick="return vValidar();">
  		  <!--<input type="button" name="AddB" value="Añadir" onClick="return vValidarB(document.form1,'$formaAction');">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">-->
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  
  function formaActualizarEstado($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$arr=null)
  {
	$oTipoticket=new c_tipoticket($this->con);
	$oPriorididad=new c_prioridad($this->con);
	$oServicio=new c_servicio($this->con);

	$oEstado=new c_tipoestado($this->con);
	
    $campo=array(
				array("etiqueta"=>"* Estado","nombre"=>"tEstado","tipo_campo"=>"select","sql"=>$oEstado->sqlSelect(""),"valor"=>""),
    			array("etiqueta"=>"* Comentario","nombre"=>"tComentario","tipo_campo"=>"area","sql"=>"","valor"=>""),
    			array("etiqueta"=>"* Palabras Clave","nombre"=>"tPalabraClave","tipo_campo"=>"area","sql"=>"","valor"=>""),
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"tUsuario","valor"=>$arr["mtu"]),
			  		array("nombre"=>"id","valor"=>$arr["id"]),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_addCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJsCrearComentario();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Add" value="Añadir" onClick="return vValidar();">
  		  <!--<input type="button" name="AddB" value="Añadir" onClick="return vValidarB(document.form1,'$formaAction');">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">-->
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  
  function formaTransferir($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$arr=null)
  {
	$oTipoticket=new c_tipoticket($this->con);
	$oPriorididad=new c_prioridad($this->con);
	$oServicio=new c_servicio($this->con);

	$oAux=new c_ticket($this->con);
	$oAux->info($arr["id"]);
	
	$sqlSoporte=<<<mya
	select axs.usu_id,u.usu_nombre
	from analistaxservicio axs, usuario u
	where u.usu_id=axs.usu_id
  	and axs.ser_id=$oAux->ser_id
	union
	select axs.usu_id,u.usu_nombre
	from analistaxservicio axs, usuario u
	where u.usu_id=axs.usu_id 
mya;
	
    $campo=array(
				array("etiqueta"=>"* Soporte","nombre"=>"tSoporte","tipo_campo"=>"select","sql"=>$sqlSoporte,"valor"=>""),
    			array("etiqueta"=>"* Comentario","nombre"=>"tComentario","tipo_campo"=>"area","sql"=>"","valor"=>""),
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"tUsuario","valor"=>$arr["mtu"]),
			  		array("nombre"=>"id","valor"=>$arr["id"]),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_addCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJsCrearComentario();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1">
  		  $cadForm
  		  <input type="submit" name="Add" value="Añadir" onClick="return vValidar();">
  		  <!--<input type="button" name="AddB" value="Añadir" onClick="return vValidarB(document.form1,'$formaAction');">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">-->
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
  }
  
  function formaCargarDocumento($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$titulo,$arr=null)
  {
	$oTipoticket=new c_tipoticket($this->con);
	$oPriorididad=new c_prioridad($this->con);
	$oServicio=new c_servicio($this->con);

	$oEstado=new c_tipoestado($this->con);
	
    $campo=array(
				array("etiqueta"=>"* Archivo","nombre"=>"tFile","tipo_campo"=>"file","sql"=>"","valor"=>""),
    			array("etiqueta"=>"* Descripci&oacute;n","nombre"=>"tDescripcion","tipo_campo"=>"area","sql"=>"","valor"=>"")
				);
	$campo_hidden=array(
					array("nombre"=>"id_aplicacion","valor"=>$id_aplicacion),
			  		array("nombre"=>"id_subaplicacion","valor"=>$id_subaplicacion),
			  		array("nombre"=>"tUsuario","valor"=>$arr["mtu"]),
			  		array("nombre"=>"id","valor"=>$arr["id"]),
					array("nombre"=>"principal","valor"=>$principal)
				);
	
	$cadForm=build_addCad($this->con,'false',$titulo,'images/personwrite.gif',"50%",'true'
		,$campo,$campo_hidden);
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion;
  	
	$cadValidaForma=$this->validaJsCrearComentario();
  	$cad=<<<va
  		$cadValidaForma
  		<form action="$formaAction" method="post" name="form1" enctype="multipart/form-data" >
  		  $cadForm
  		  <input type="submit" name="Add" value="Cargar Archivo" onClick="return vValidar();">
  		  <!--<input type="button" name="AddB" value="Añadir" onClick="return vValidarB(document.form1,'$formaAction');">
  		  <input type="button" name="Cancel" value="Regresar" onClick="self.location='$principal$param_destino'">-->
		</form>
va;
	return($cad);
	// onClick="validate();return returnVal;"
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
  function verTicket($formaAction,$principal,$id_aplicacion,$id_subaplicacion,$destAdd,$destUpdate,$titulo,$post=NULL)
  {
	$param_destino="?id_aplicacion=".$id_aplicacion."&id_subaplicacion=".$id_subaplicacion."&principal=".$principal."&mtu=".$post["sUsername"];
  	
  	$cad=<<<va
	<form action="$formaAction" method="post" name="form1">
	  <input type="hidden" name="principal" value="$principal">
	  <input type="hidden" name="id_aplicacion" value="$id_aplicacion">
	  <input type="hidden" name="id_subaplicacion" value="$id_subaplicacion">	
  	  <input type="button" name="Add" value="Añadir" onClick="self.location='$destAdd$param_destino'">
  	  <!--
  	  <input type="submit" name="Del" value="Eliminar" onClick="return confirmdeletef();">
  	  -->
  	  <br>
va;
  	$aSql="";
  	if(strlen($post["sUsername"])>0)
  	  $aSql=" and u1.usu_id='".$post["sUsername"]."' ";
  	
  	$sql=<<<va
  	select t.tic_id as id1,
	u1.usu_nombre,tt.tiptic_nombre,p.pri_nombre,s.ser_nombre,t.tic_resumen,t.tic_fechahoraapertura,t.tic_fechahorainicio,te.tipest_nombre
,t.tic_id as id2
	from ticket t, usuario u1, usuario u2, tipoticket tt, prioridad p, servicio s, tipoestado te
	where
	u1.usu_id=t.usu_id
	and u2.usu_id=t.usu_asignado
	and tt.tiptic_id=t.tiptic_id
	and p.pri_id=t.pri_id
	and s.ser_id=t.ser_id
	and te.tipest_id=t.tipest_id
	$aSql 
	order by t.tic_fechahoraapertura desc 
va;
	$rs= &$this->con->Execute($sql);
    if ($rs->EOF) 
	  $cad.="<hr><b>No se encontraron registros!!!</b><hr>";
	else
	{
	  //$mainheaders=array("Del","Class Part","Part Number","Description","Applicability","Modify");		
	  $mainheaders=array("# Ticket","Cliente","Tipo Ticket","Prioridad","Servicio","Resumen","Fecha Apertura","Fecha Inicio Atencion","Estado","Ver");
	  //$cad.=build_table_adminCad($rs,false,$mainheaders,$titulo,'images/yearview.gif','50%','true','chc',$destUpdate,$param_destino,"total");
			
	  $cad.=build_table_sindelCad($rs,false,$mainheaders,$titulo,'images/yearview.gif','70%','true',$destUpdate,$param_destino,"total",1,"verTicket",'800','500');		
			
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
    $oPerfil=new c_perfil($this->con);
	$oArea=new c_area($this->con);
	$oTipoticket=new c_tipoticket($this->con);
      
    $oAux=new c_ticket($this->con);
  	$oAux->info($id);
	$campo=array(
				array("etiqueta"=>"* Username","nombre"=>"tUsername","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->tic_id),
				array("etiqueta"=>"* Clave","nombre"=>"tClave","tipo_campo"=>"password","sql"=>"","valor"=>$oAux->usu_id),
				array("etiqueta"=>"*  Nombre","nombre"=>"tNombre","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->pri_id),
				array("etiqueta"=>"* Perfil","nombre"=>"tPerfil","tipo_campo"=>"select","sql"=>$oPerfil->sqlSelect(""),"valor"=>$oAux->ser_id),
				array("etiqueta"=>"* Area","nombre"=>"tArea","tipo_campo"=>"select","sql"=>$oArea->sqlSelect(""),"valor"=>$oAux->tic_resumen),
				array("etiqueta"=>"* Tipo de ticket","nombre"=>"tTipoticket","tipo_campo"=>"select","sql"=>$oTipoticket->sqlSelect(""),"valor"=>$oAux->tiptic_id),
				array("etiqueta"=>"* E-mail","nombre"=>"tMail","tipo_campo"=>"text","sql"=>"","valor"=>$oAux->tic_descripcion)
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
  	  define('tUsername', 'string', 'Username',1,20,document);
  	  define('tClave', 'string', 'Clave',1,20,document);
  	  define('tNombre', 'string', 'Nombre',1,100,document);
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
  
  /**
   * Construye código javascript para validación de formularios
   *
   * @return string
   */
  function validaJsCrearComentario()
  {
  	//<script language="JavaScript" src="js/validation.js"></script>
  	$cad=<<<va
  	
  	<script language="javascript">
	function valida()
	{
  	  define('tComentario', 'string', 'Comentario',1,300,document);
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
  
  function envioMail($ticket,$texto,$usuario)
  {
  	include_once("class/c_parametro.php");
  	$oPar=new c_parametro($this->con);
  	
  	$oUsuario=new c_usuario($this->con);
  	$res=$oUsuario->info($usuario);
  	  	
  	if($res==$usuario)
  	{
  		//envio de mail
  	    $to=$oUsuario->usu_mail;
  	    
  	    $subject="Helpdesk - Ticket # ".$ticket;
  	    
  	    //$link=$pagina."?usuid=".$oaux->cli_id."&v1234=".$oaux->cli_codactivacion;
  	    
  	    $msg=<<<mya
<html>
  <head>
    <title>Helpdesk HQ1</title>
  </head>
  <body>
  	  Estimado(a) $oUsuario->cli_Nombre: <br>
  
	  $texto	
  	  <br>
  	  
  	  Saludos,
  	  
  	  Helpdesk HQ1
		
  </body>  
</html>
mya;

  		$cabecera = "Return-Path: ".$from."\n"; 
  		$cabecera .= "From: helpdesk".$from."\n"; 
  		$cabecera .= "Reply-To: helpdesk".$from."\n"; 
  		$cabecera .= "MIME-Version: 1.0\n"; 
  		$cabecera .= "X-Sender: helpdesk".$from."\n"; 
  		$cabecera .= "X-Mailer: PHP\n"; //mailer 
  		$cabecera .= "X-Priority: 3\n"; //1 UrgentMessage, 3 Normal	
  		$cabecera .= "Content-type: text/html\n";  	    
  		//escribir archivo
  		@mail($to,$subject,$msg,$cabecera);
  	}
  }
  
  function updateValorEncuesta($ticket,$valor)
  {
    $oAux=new c_ticket($this->con);
    $existe=$oAux->info($ticket);
    if(($existe)&&($oAux->tipest_id=="CE")&&($oAux->tic_valorencuesta==""))
    {
    	$sql=<<<mya
update ticket 
set tic_valorencuesta='$valor' 
where tic_id=$ticket
mya;
		$rs=&$this->con->Execute($sql);
		return($ticket);
    }
    
  }
  
}


?>