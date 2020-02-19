-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.51


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema helpdeskhq1
--

CREATE DATABASE IF NOT EXISTS helpdeskhq1;
USE helpdeskhq1;

--
-- Definition of table `analistaxservicio`
--

DROP TABLE IF EXISTS `analistaxservicio`;
CREATE TABLE `analistaxservicio` (
  `ser_id` int(11) NOT NULL,
  `usu_id` varchar(25) NOT NULL,
  `usu_audit` varchar(25) default NULL,
  `usu_faudit` datetime default NULL,
  PRIMARY KEY  (`ser_id`,`usu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `analistaxservicio`
--

/*!40000 ALTER TABLE `analistaxservicio` DISABLE KEYS */;
INSERT INTO `analistaxservicio` (`ser_id`,`usu_id`,`usu_audit`,`usu_faudit`) VALUES 
 (2,'jmesias','jmesias','2009-07-19 09:57:55'),
 (2,'soporte','jmesias','2009-07-19 09:57:55'),
 (2,'vrengifo','jmesias','2009-07-19 09:57:55'),
 (3,'jmesias','vrengifo','2009-07-19 17:51:18'),
 (3,'soporte','jmesias','2009-07-19 18:10:41'),
 (3,'vrengifo','jmesias','2009-07-19 09:58:35');
/*!40000 ALTER TABLE `analistaxservicio` ENABLE KEYS */;


--
-- Definition of table `area`
--

DROP TABLE IF EXISTS `area`;
CREATE TABLE `area` (
  `are_id` int(10) unsigned NOT NULL auto_increment,
  `are_nombre` varchar(100) default NULL,
  `are_descripcion` blob,
  PRIMARY KEY  (`are_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `area`
--

/*!40000 ALTER TABLE `area` DISABLE KEYS */;
INSERT INTO `area` (`are_id`,`are_nombre`,`are_descripcion`) VALUES 
 (1,'Sistemas',0x495420),
 (2,'Administracion',0x446570617274616D656E746F2041646D696E69737472617469766F);
/*!40000 ALTER TABLE `area` ENABLE KEYS */;


--
-- Definition of table `documentoxticket`
--

DROP TABLE IF EXISTS `documentoxticket`;
CREATE TABLE `documentoxticket` (
  `tic_id` bigint(20) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `doc_nombre` varchar(250) default NULL,
  `doc_descripcion` text,
  `doc_path` varchar(250) default NULL,
  `usu_audit` varchar(25) default NULL,
  `usu_faudit` datetime default NULL,
  PRIMARY KEY  (`doc_id`,`tic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `documentoxticket`
--

/*!40000 ALTER TABLE `documentoxticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentoxticket` ENABLE KEYS */;


--
-- Definition of table `encuesta`
--

DROP TABLE IF EXISTS `encuesta`;
CREATE TABLE `encuesta` (
  `enc_id` int(10) unsigned NOT NULL auto_increment,
  `ser_id` int(11) default NULL,
  `enc_nombre` varchar(100) default NULL,
  `enc_descripcion` text,
  `enc_activa` char(1) default NULL,
  `usu_audit` varchar(25) default NULL,
  `usu_faudit` datetime default NULL,
  PRIMARY KEY  (`enc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `encuesta`
--

/*!40000 ALTER TABLE `encuesta` DISABLE KEYS */;
INSERT INTO `encuesta` (`enc_id`,`ser_id`,`enc_nombre`,`enc_descripcion`,`enc_activa`,`usu_audit`,`usu_faudit`) VALUES 
 (1,0,'Encuesta General','Encuesta para todos los servicios','1','vrengifo','2009-07-19 17:42:00'),
 (3,5,'Prueba Encuesta','Descripcion Prueba Encuesta','1','vrengifo','2009-07-28 10:46:34');
/*!40000 ALTER TABLE `encuesta` ENABLE KEYS */;


--
-- Definition of table `horario`
--

DROP TABLE IF EXISTS `horario`;
CREATE TABLE `horario` (
  `hor_id` int(10) unsigned NOT NULL auto_increment,
  `hor_nombre` varchar(100) default NULL,
  `hor_inicio` time default NULL,
  `hor_fin` time default NULL,
  `hor_descripcion` varchar(255) default NULL,
  PRIMARY KEY  (`hor_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `horario`
--

/*!40000 ALTER TABLE `horario` DISABLE KEYS */;
INSERT INTO `horario` (`hor_id`,`hor_nombre`,`hor_inicio`,`hor_fin`,`hor_descripcion`) VALUES 
 (1,'Diurno 1','07:00:00','15:30:00','Diurno 1'),
 (2,'Diurno 2','08:00:00','16:30:00','Diurno 2');
/*!40000 ALTER TABLE `horario` ENABLE KEYS */;


--
-- Definition of table `horarioxanalista`
--

DROP TABLE IF EXISTS `horarioxanalista`;
CREATE TABLE `horarioxanalista` (
  `usu_id` varchar(25) NOT NULL,
  `hor_id` int(11) NOT NULL,
  `usu_audit` varchar(25) default NULL,
  `usu_faudit` datetime default NULL,
  PRIMARY KEY  (`usu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `horarioxanalista`
--

/*!40000 ALTER TABLE `horarioxanalista` DISABLE KEYS */;
INSERT INTO `horarioxanalista` (`usu_id`,`hor_id`,`usu_audit`,`usu_faudit`) VALUES 
 ('jmesias',1,'jmesias','2009-07-19 10:03:11'),
 ('soporte',1,'jmesias','2009-07-19 10:03:11'),
 ('vrengifo',2,'jmesias','2009-07-19 10:03:11');
/*!40000 ALTER TABLE `horarioxanalista` ENABLE KEYS */;


--
-- Definition of table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `ite_id` int(10) unsigned NOT NULL auto_increment,
  `tipite_id` int(11) default NULL,
  `ite_id_padre` int(10) unsigned default NULL,
  `ite_nombre` varchar(200) default NULL,
  `ite_descripcion` blob,
  `ite_pn` varchar(100) default NULL,
  `ite_sn` varchar(100) default NULL,
  `ite_activo` char(1) default NULL,
  PRIMARY KEY  (`ite_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item`
--

/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` (`ite_id`,`tipite_id`,`ite_id_padre`,`ite_nombre`,`ite_descripcion`,`ite_pn`,`ite_sn`,`ite_activo`) VALUES 
 (0,NULL,0,'Raiz',NULL,' ',' ','1'),
 (2,2,0,'Computador',0x506F72746174696C20485020506176696C6C696F6E20445633303030,'HP123456','AXN123X2C3','1'),
 (3,2,0,'IBM-P4',0x50432049424D205034,'8297-D6S','LK51701','1'),
 (4,2,3,'Monito Lenovo',0x4D6F6E69746F72204C656E6F766F,'LE001','A123456','1'),
 (6,1,0,'Sistema Operativo Windows XP',0x53697374656D61204F706572617469766F2057696E646F7773205850,'SWEIRNIDE0WS','SNEURMIBAELR','1');
/*!40000 ALTER TABLE `item` ENABLE KEYS */;


--
-- Definition of table `itemxusuario`
--

DROP TABLE IF EXISTS `itemxusuario`;
CREATE TABLE `itemxusuario` (
  `usu_id` varchar(25) NOT NULL,
  `ite_id` int(11) NOT NULL,
  `usu_audit` varchar(25) default NULL,
  `usu_faudit` datetime default NULL,
  PRIMARY KEY  (`usu_id`,`ite_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `itemxusuario`
--

/*!40000 ALTER TABLE `itemxusuario` DISABLE KEYS */;
INSERT INTO `itemxusuario` (`usu_id`,`ite_id`,`usu_audit`,`usu_faudit`) VALUES 
 ('jmesias',2,'vrengifo','2009-07-28 14:16:56'),
 ('vrengifo',3,'vrengifo','2009-07-28 14:28:46');
/*!40000 ALTER TABLE `itemxusuario` ENABLE KEYS */;


--
-- Definition of table `logmovimientoitem`
--

DROP TABLE IF EXISTS `logmovimientoitem`;
CREATE TABLE `logmovimientoitem` (
  `logmov_id` bigint(20) unsigned NOT NULL auto_increment,
  `tipaccite_id` int(11) NOT NULL,
  `usu_id` varchar(25) NOT NULL,
  `ite_id` int(11) NOT NULL,
  `logmov_descripcion` text,
  `usu_audit` varchar(25) default NULL,
  `usu_faudit` datetime default NULL,
  PRIMARY KEY  (`logmov_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logmovimientoitem`
--

/*!40000 ALTER TABLE `logmovimientoitem` DISABLE KEYS */;
INSERT INTO `logmovimientoitem` (`logmov_id`,`tipaccite_id`,`usu_id`,`ite_id`,`logmov_descripcion`,`usu_audit`,`usu_faudit`) VALUES 
 (9,1,'jmesias',2,'Asignacion: Equipo completamente nuevo','vrengifo','2009-07-28 14:16:56'),
 (10,2,'vrengifo',2,'Mantenimiento: Mantenimiento preventivo','vrengifo','2009-07-28 14:17:47'),
 (11,1,'vrengifo',3,'Asignacion: Prueba','vrengifo','2009-07-28 14:28:46');
/*!40000 ALTER TABLE `logmovimientoitem` ENABLE KEYS */;


--
-- Definition of table `logticket`
--

DROP TABLE IF EXISTS `logticket`;
CREATE TABLE `logticket` (
  `logtic_id` bigint(20) unsigned NOT NULL auto_increment,
  `tic_id` bigint(20) NOT NULL,
  `tipacc_id` char(2) default NULL,
  `tipest_id` char(2) NOT NULL,
  `usu_id` varchar(25) default NULL,
  `logtic_comentario` text,
  `usu_audit` varchar(25) default NULL,
  `usu_faudit` datetime default NULL,
  `logtic_palabraclave` varchar(200) default NULL,
  PRIMARY KEY  (`logtic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logticket`
--

/*!40000 ALTER TABLE `logticket` DISABLE KEYS */;
INSERT INTO `logticket` (`logtic_id`,`tic_id`,`tipacc_id`,`tipest_id`,`usu_id`,`logtic_comentario`,`usu_audit`,`usu_faudit`,`logtic_palabraclave`) VALUES 
 (1,1,'CR','A','vrengifo','Ticket creado','vrengifo','2009-07-22 11:50:18',''),
 (2,2,'CR','A','vrengifo','Ticket creado','vrengifo','2009-07-23 11:51:21',''),
 (3,3,'CR','A','vrengifo','Ticket creado','vrengifo','2009-07-24 11:51:54',''),
 (4,4,'CR','A','vrengifo','Ticket creado','vrengifo','2009-07-25 11:52:55',''),
 (5,5,'CR','A','vrengifo','Ticket creado','vrengifo','2009-07-26 11:53:22',''),
 (6,6,'CR','A','vrengifo','Ticket creado','vrengifo','2009-07-26 11:54:31',''),
 (7,7,'CR','A','vrengifo','Ticket creado','vrengifo','2009-07-26 11:55:24',''),
 (8,8,'CR','A','vrengifo','Ticket creado','vrengifo','2009-07-26 12:15:59',''),
 (13,8,'U','','vrengifo','Creo que he olvidado mi clave y por lo tanto se bloqueo mi usuario.  Favor su ayuda','vrengifo','2009-07-26 15:19:32',''),
 (14,8,'C','EP','vrengifo','Se esta chequeando su informacion, en unos momentos nos contactaremos con usted','vrengifo','2009-07-26 15:34:52',''),
 (15,8,'T','','jmesias','Transferencia de ticket:De vrengifo a jmesias<br />Juan Carlos:\r\n\r\nFavor ayudarme con el chequeo de la clave en el directorio activo de este usuario\r\n\r\n','jmesias','2009-07-26 15:48:30',''),
 (16,8,'T','','vrengifo','Transferencia de ticket:De jmesias a vrengifo \n Victor:\r\n\r\nSe ha reseteado la clave del usuario.','vrengifo','2009-07-26 15:51:03',''),
 (17,8,'S','CE','jmesias','Se verifico que la clave de usuario estaba bloqueada, por lo cual se solicito el reseteo de clave.  Se realizaron las pruebas con el usuario y finalmente accedio al sistema','jmesias','2009-07-26 15:52:15','clave error bloqueo bloqueada'),
 (18,6,'U','','vrengifo','Al parecer existe un  fallo de corriente','vrengifo','2009-07-26 17:21:30',''),
 (19,6,'C','EP','vrengifo','En este momento se esta ejecutando una revision de las tomas electricas','vrengifo','2009-07-26 17:24:11',''),
 (20,6,'T','','soporte','Transferencia de ticket:De vrengifo a soporte \n Estimado:\r\n\r\nFavor indicarme el status del chequeo de las tomas electricas','soporte','2009-07-26 17:25:03',''),
 (21,6,'C','P','soporte','No llegan empleados de EEQ y apenas se encuentre listo el trabajo se informara','soporte','2009-07-26 17:27:34',''),
 (22,6,'T','','vrengifo','Transferencia de ticket:De soporte a vrengifo \n Estimado:\r\n\r\nSe procedio a las verificaciones de tomas por parte de EEQ, y todo esta listo','vrengifo','2009-07-26 17:28:22',''),
 (23,9,'CR','A','vrengifo','Ticket creado','vrengifo','2009-07-26 18:51:14',''),
 (24,10,'CR','A','usuario','Ticket creado','usuario','2009-07-26 21:30:50',''),
 (25,11,'CR','A','usuario','Ticket creado','usuario','2009-07-26 21:31:55',''),
 (26,12,'CR','A','usuario','Ticket creado','usuario','2009-07-26 21:33:07',''),
 (29,6,'S','CE','vrengifo','Se procedio a chequear la informacion proporcionada por el usuario.  \r\nEl equipo esta funcionando correctamente.','vrengifo','2009-07-28 10:08:11','no arranca pc equipo');
/*!40000 ALTER TABLE `logticket` ENABLE KEYS */;


--
-- Definition of table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
CREATE TABLE `modulo` (
  `mod_id` int(10) unsigned NOT NULL auto_increment,
  `mod_nombre` varchar(100) default NULL,
  `mod_formulario` varchar(200) default NULL,
  `mod_imagen` varchar(200) default NULL,
  `mod_orden` int(11) default NULL,
  PRIMARY KEY  (`mod_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `modulo`
--

/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` (`mod_id`,`mod_nombre`,`mod_formulario`,`mod_imagen`,`mod_orden`) VALUES 
 (1,'Modulos','admin.php','modModulos.png',0),
 (2,'Reportes','admin.php','modReportes.png',40),
 (3,'Usuario-Perfil','admin.php','modUsuarioPerfil.png',5),
 (4,'Inventario','admin.php','modInventario.png',25),
 (5,'Admin. Helpdesk','admin.php','modAdminHelpdesk.png',30),
 (6,'Helpdesk','admin.php','modHelpdesk.png',35);
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;


--
-- Definition of table `opcion`
--

DROP TABLE IF EXISTS `opcion`;
CREATE TABLE `opcion` (
  `opc_id` int(10) unsigned NOT NULL auto_increment,
  `opc_id_padre` int(10) unsigned default NULL,
  `opc_nombre` varchar(80) default NULL,
  `opc_url` varchar(250) default NULL,
  `opc_estado` char(1) default NULL,
  PRIMARY KEY  (`opc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `opcion`
--

/*!40000 ALTER TABLE `opcion` DISABLE KEYS */;
INSERT INTO `opcion` (`opc_id`,`opc_id_padre`,`opc_nombre`,`opc_url`,`opc_estado`) VALUES 
 (0,0,'Raiz',NULL,NULL),
 (2,0,'Administracion',NULL,'1'),
 (3,0,'Tickets',NULL,'1'),
 (4,0,'Inventario',NULL,'1'),
 (5,0,'Base de Conocimiento',NULL,'1'),
 (6,2,'Inventario',NULL,'1'),
 (7,6,'Tipo de Item','tipoItem.php','1'),
 (8,6,'Tipo de Accion','tipoAccionItem.php','1'),
 (9,6,'Item','item.php','1'),
 (10,2,'Seguridad',NULL,'1'),
 (11,10,'Tipo de Usuario','tipoUsuario.php','1'),
 (12,10,'Area / Departamento','area.php','1'),
 (13,10,'Usuario','usuario.php','1'),
 (14,2,'Perfil',NULL,'1'),
 (15,14,'Perfil','perfil.php','1'),
 (16,14,'Opciones / Menus','opcion.php','1'),
 (17,14,'Opciones por Perfil','opcionxperfil.php','1'),
 (18,2,'Helpdesk',NULL,'1'),
 (19,18,'Tipo de Ticket','tipoTicket.php','1'),
 (20,18,'Prioridad','prioridad.php','1'),
 (21,18,'Servicio','servicio.php','1'),
 (22,18,'Analista por Servicio','analistaxservicio.php','1'),
 (23,18,'Horario','horario.php','1'),
 (24,18,'Horario Analista','horarioxanalista.php','1'),
 (25,18,'Encuesta','encuesta.php','1'),
 (26,3,'Administracion de Tickets','ticketAdministracion.php','1'),
 (27,3,'Creados por Usuario','ticketUsuario.php','1'),
 (28,3,'Asignados por Usuario','ticketAsignadoUsuario.php','1'),
 (29,3,'Cerrados por Usuario','ticketCerradoUsuario.php','1'),
 (30,4,'Movimientos',NULL,'1'),
 (31,30,'Recursos por Usuario','itemxusuario.php','1'),
 (32,30,'Consulta',NULL,'1'),
 (33,32,'Listar Recursos por Usuario','rep_RecursosUsuario.php','1'),
 (34,32,'Listar Recursos por Area','rep_RecursosArea.php','1'),
 (35,5,'Por Servicio','conocimientoServicio.php','1'),
 (36,5,'Por Usuario','conocimientoUsuario.php','1'),
 (37,5,'Por Analista','conocimientoAnalista.php','1');
/*!40000 ALTER TABLE `opcion` ENABLE KEYS */;


--
-- Definition of table `opcionxperfil`
--

DROP TABLE IF EXISTS `opcionxperfil`;
CREATE TABLE `opcionxperfil` (
  `opc_id` int(11) NOT NULL,
  `per_id` int(11) NOT NULL,
  PRIMARY KEY  (`opc_id`,`per_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `opcionxperfil`
--

/*!40000 ALTER TABLE `opcionxperfil` DISABLE KEYS */;
/*!40000 ALTER TABLE `opcionxperfil` ENABLE KEYS */;


--
-- Definition of table `parametro`
--

DROP TABLE IF EXISTS `parametro`;
CREATE TABLE `parametro` (
  `par_id` int(11) NOT NULL auto_increment,
  `par_fecha` varchar(100) default NULL,
  `par_fechahora` varchar(100) default NULL,
  `par_fechaformato` varchar(10) default NULL,
  `par_fechasql` varchar(100) default NULL,
  `par_fechahorasql` varchar(100) default NULL,
  `par_sepdecimal` char(1) default NULL,
  `par_seplista` char(1) default NULL,
  `par_cuentamail` varchar(250) default NULL,
  `par_homesite` varchar(250) default NULL,
  PRIMARY KEY  (`par_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parametro`
--

/*!40000 ALTER TABLE `parametro` DISABLE KEYS */;
INSERT INTO `parametro` (`par_id`,`par_fecha`,`par_fechahora`,`par_fechaformato`,`par_fechasql`,`par_fechahorasql`,`par_sepdecimal`,`par_seplista`,`par_cuentamail`,`par_homesite`) VALUES 
 (1,'Y-m-d','Y-m-d H:i:s',NULL,NULL,NULL,'.',':','helpdesk@localhost','http://localhost/helpdeskHQ/');
/*!40000 ALTER TABLE `parametro` ENABLE KEYS */;


--
-- Definition of table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
CREATE TABLE `perfil` (
  `per_id` int(10) unsigned NOT NULL auto_increment,
  `per_nombre` varchar(100) default NULL,
  `per_descripcion` text,
  PRIMARY KEY  (`per_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `perfil`
--

/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` (`per_id`,`per_nombre`,`per_descripcion`) VALUES 
 (1,'Administrador','Perfil de Administrador del Sistema'),
 (2,'Soporte','Perfil de Soporte / Analista'),
 (3,'Usuario','Perfil de Usuario'),
 (4,'Gerentes','Perfil de Gerentes (Reportes del Sistema)');
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;


--
-- Definition of table `perfilxsubmodulo`
--

DROP TABLE IF EXISTS `perfilxsubmodulo`;
CREATE TABLE `perfilxsubmodulo` (
  `per_id` int(11) default NULL,
  `mod_id` int(11) default NULL,
  `submod_id` int(11) default NULL,
  `usu_audit` varchar(20) default NULL,
  `usu_faudit` datetime default NULL,
  KEY `relationship_93_fk` (`mod_id`,`submod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `perfilxsubmodulo`
--

/*!40000 ALTER TABLE `perfilxsubmodulo` DISABLE KEYS */;
INSERT INTO `perfilxsubmodulo` (`per_id`,`mod_id`,`submod_id`,`usu_audit`,`usu_faudit`) VALUES 
 (1,1,1,'vrengifo','2009-07-10 00:41:57'),
 (1,1,2,'vrengifo','2009-07-10 00:41:57'),
 (1,3,3,'vrengifo','2009-07-10 00:41:57'),
 (1,3,4,'vrengifo','2009-07-10 00:41:57'),
 (1,3,5,'vrengifo','2009-07-10 00:41:57'),
 (1,3,6,'vrengifo','2009-07-10 02:12:51'),
 (1,3,7,'vrengifo','2009-07-10 02:12:51'),
 (1,4,8,'vrengifo','2009-07-10 02:40:47'),
 (1,4,9,'vrengifo','2009-07-11 08:29:53'),
 (1,5,12,'vrengifo','2009-07-11 09:57:11'),
 (1,5,13,'vrengifo','2009-07-11 09:57:11'),
 (1,5,14,'vrengifo','2009-07-11 09:57:11'),
 (1,5,15,'vrengifo','2009-07-11 09:57:11'),
 (1,5,16,'vrengifo','2009-07-11 09:57:11'),
 (1,5,17,'vrengifo','2009-07-11 09:57:11'),
 (1,5,18,'vrengifo','2009-07-11 09:57:11'),
 (1,5,19,'vrengifo','2009-07-11 09:57:11'),
 (1,6,20,'vrengifo','2009-07-11 09:57:17'),
 (1,6,21,'vrengifo','2009-07-11 09:57:17'),
 (1,6,22,'vrengifo','2009-07-11 09:57:17'),
 (1,6,23,'vrengifo','2009-07-11 09:57:17'),
 (1,4,10,'vrengifo','2009-07-11 09:57:25'),
 (1,4,11,'vrengifo','2009-07-11 09:57:25'),
 (3,6,20,'vrengifo','2009-07-11 10:06:34'),
 (3,6,21,'vrengifo','2009-07-11 10:06:34'),
 (3,6,22,'vrengifo','2009-07-11 10:06:34'),
 (3,6,23,'vrengifo','2009-07-11 10:06:34'),
 (2,6,20,'vrengifo','2009-07-11 10:07:50'),
 (2,6,21,'vrengifo','2009-07-11 10:07:50'),
 (2,6,22,'vrengifo','2009-07-11 10:07:50'),
 (2,6,23,'vrengifo','2009-07-11 10:07:50'),
 (1,2,24,'vrengifo','2009-07-19 17:45:35'),
 (1,2,25,'vrengifo','2009-07-19 17:45:35'),
 (1,2,26,'vrengifo','2009-07-19 17:45:35'),
 (1,2,27,'vrengifo','2009-07-19 17:45:35'),
 (4,2,24,'vrengifo','2009-07-19 17:45:47'),
 (4,2,25,'vrengifo','2009-07-19 17:45:47'),
 (4,2,26,'vrengifo','2009-07-19 17:45:47'),
 (4,2,27,'vrengifo','2009-07-19 17:45:47'),
 (2,2,24,'vrengifo','2009-07-19 17:46:00'),
 (2,2,25,'vrengifo','2009-07-19 17:46:00'),
 (2,2,26,'vrengifo','2009-07-19 17:46:00'),
 (2,2,27,'vrengifo','2009-07-19 17:46:00'),
 (3,2,24,'vrengifo','2009-07-19 17:46:11'),
 (3,2,25,'vrengifo','2009-07-19 17:46:11'),
 (3,2,26,'vrengifo','2009-07-19 17:46:11'),
 (3,2,27,'vrengifo','2009-07-19 17:46:11');
/*!40000 ALTER TABLE `perfilxsubmodulo` ENABLE KEYS */;


--
-- Definition of table `pregunta`
--

DROP TABLE IF EXISTS `pregunta`;
CREATE TABLE `pregunta` (
  `enc_id` int(11) NOT NULL,
  `pre_id` int(11) NOT NULL,
  `pre_nombre` text,
  PRIMARY KEY  (`pre_id`,`enc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pregunta`
--

/*!40000 ALTER TABLE `pregunta` DISABLE KEYS */;
INSERT INTO `pregunta` (`enc_id`,`pre_id`,`pre_nombre`) VALUES 
 (1,1,'Como evalua la solucion del ticket?'),
 (1,2,'Como evalua los conocimientos del soporte?'),
 (1,3,'Como evalua el servicio de helpdesk?'),
 (1,4,'Prueba'),
 (3,1,'Pregunta 1: Evaluacion del servicio?'),
 (3,2,'Pregunta 2: Atencion del soporte?');
/*!40000 ALTER TABLE `pregunta` ENABLE KEYS */;


--
-- Definition of table `prioridad`
--

DROP TABLE IF EXISTS `prioridad`;
CREATE TABLE `prioridad` (
  `pri_id` int(10) unsigned NOT NULL auto_increment,
  `pri_nombre` varchar(50) default NULL,
  `pri_nivel` int(11) default NULL,
  `pri_nrohorasdefault` int(10) unsigned default NULL,
  PRIMARY KEY  (`pri_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prioridad`
--

/*!40000 ALTER TABLE `prioridad` DISABLE KEYS */;
INSERT INTO `prioridad` (`pri_id`,`pri_nombre`,`pri_nivel`,`pri_nrohorasdefault`) VALUES 
 (1,'Critica',40,4),
 (2,'Alta',30,8),
 (3,'Media',20,12),
 (4,'Baja',15,16);
/*!40000 ALTER TABLE `prioridad` ENABLE KEYS */;


--
-- Definition of table `prioridadxservicio`
--

DROP TABLE IF EXISTS `prioridadxservicio`;
CREATE TABLE `prioridadxservicio` (
  `ser_id` int(11) NOT NULL,
  `pri_id` int(11) NOT NULL,
  `prixser_cantidadhoras` int(11) default NULL,
  PRIMARY KEY  (`ser_id`,`pri_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prioridadxservicio`
--

/*!40000 ALTER TABLE `prioridadxservicio` DISABLE KEYS */;
INSERT INTO `prioridadxservicio` (`ser_id`,`pri_id`,`prixser_cantidadhoras`) VALUES 
 (2,1,4),
 (2,2,8),
 (2,3,12),
 (2,4,16),
 (3,1,4),
 (3,2,8),
 (3,3,12),
 (3,4,16),
 (6,1,4),
 (6,2,8),
 (6,3,12),
 (6,4,16);
/*!40000 ALTER TABLE `prioridadxservicio` ENABLE KEYS */;


--
-- Definition of table `respuesta`
--

DROP TABLE IF EXISTS `respuesta`;
CREATE TABLE `respuesta` (
  `enc_id` int(11) NOT NULL,
  `pre_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `res_nombre` text,
  `res_peso` int(11) default NULL,
  PRIMARY KEY  (`res_id`,`pre_id`,`enc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `respuesta`
--

/*!40000 ALTER TABLE `respuesta` DISABLE KEYS */;
INSERT INTO `respuesta` (`enc_id`,`pre_id`,`res_id`,`res_nombre`,`res_peso`) VALUES 
 (1,1,1,'Excelente',4),
 (1,2,1,'Excelente',4),
 (1,3,1,'Excelente',4),
 (1,1,2,'Normal',2),
 (1,2,2,'Normal',2),
 (1,3,2,'Normal',2),
 (1,1,3,'Baja',1),
 (1,2,3,'Baja',1),
 (1,3,3,'Baja',1),
 (1,0,1,'Pesima',0),
 (1,1,4,'Pesima',0),
 (1,3,4,'Malisimo',0),
 (1,4,1,'Uno',1),
 (1,4,2,'Dos',2),
 (3,1,1,'Excelente',4),
 (3,1,2,'Buena',2),
 (3,1,3,'Pesima',0),
 (3,2,1,'Excelente',4),
 (3,2,2,'Buena',2),
 (3,2,3,'Deficiente',0);
/*!40000 ALTER TABLE `respuesta` ENABLE KEYS */;


--
-- Definition of table `respuestaencuesta`
--

DROP TABLE IF EXISTS `respuestaencuesta`;
CREATE TABLE `respuestaencuesta` (
  `tic_id` bigint(20) NOT NULL,
  `enc_id` int(11) NOT NULL,
  `pre_id` int(11) NOT NULL,
  `res_id` int(11) NOT NULL,
  `res_valor` int(11) default NULL,
  PRIMARY KEY  (`tic_id`,`pre_id`,`enc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `respuestaencuesta`
--

/*!40000 ALTER TABLE `respuestaencuesta` DISABLE KEYS */;
INSERT INTO `respuestaencuesta` (`tic_id`,`enc_id`,`pre_id`,`res_id`,`res_valor`) VALUES 
 (8,1,4,2,2),
 (8,1,3,1,4),
 (8,1,2,1,4),
 (8,1,1,1,4);
/*!40000 ALTER TABLE `respuestaencuesta` ENABLE KEYS */;


--
-- Definition of table `servicio`
--

DROP TABLE IF EXISTS `servicio`;
CREATE TABLE `servicio` (
  `ser_id` int(10) unsigned NOT NULL auto_increment,
  `ser_id_padre` int(10) unsigned default NULL,
  `ser_nombre` varchar(100) default NULL,
  `ser_descripcion` text,
  PRIMARY KEY  (`ser_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `servicio`
--

/*!40000 ALTER TABLE `servicio` DISABLE KEYS */;
INSERT INTO `servicio` (`ser_id`,`ser_id_padre`,`ser_nombre`,`ser_descripcion`) VALUES 
 (0,0,'Todos los servicios',NULL),
 (2,0,'Software','Software en general'),
 (3,0,'Hardware','Hardware'),
 (4,0,'Sistema Hospitalario','Sistema Sofcase HQ1'),
 (5,0,'Sistema ESIGEF','Sistema Financiero del Ministerio'),
 (6,0,'LUMINO','Interfaz de Laboratorio');
/*!40000 ALTER TABLE `servicio` ENABLE KEYS */;


--
-- Definition of table `submodulo`
--

DROP TABLE IF EXISTS `submodulo`;
CREATE TABLE `submodulo` (
  `mod_id` int(10) unsigned NOT NULL,
  `submod_id` int(10) unsigned NOT NULL auto_increment,
  `submod_nombre` varchar(100) default NULL,
  `submod_formulario` varchar(200) default NULL,
  `submod_imagen` varchar(200) default NULL,
  `submod_orden` int(11) default NULL,
  PRIMARY KEY  (`submod_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `submodulo`
--

/*!40000 ALTER TABLE `submodulo` DISABLE KEYS */;
INSERT INTO `submodulo` (`mod_id`,`submod_id`,`submod_nombre`,`submod_formulario`,`submod_imagen`,`submod_orden`) VALUES 
 (1,1,'Modulo','modulo.php','modulo.png',1),
 (1,2,'Submodulo','submodulo.php','submodulo.png',2),
 (3,3,'Usuario','usuario.php','usuario.png',1),
 (3,4,'Perfil','perfil.php','perfil.png',5),
 (3,5,'Perfil x Submodulo','perfilxsubmodulo.php','perfilxsubmodulo.png',10),
 (3,6,'Area','area.php','area.png',15),
 (3,7,'Tipo de Usuario','tipousuario.php','tipousuario.png',20),
 (4,8,'Tipo de Item','tipoitem.php','tipoitem.png',1),
 (4,9,'Acciones Item','tipoaccionitem.php','tipoaccionitem.png',5),
 (4,10,'Item','item.php','item.png',10),
 (4,11,'Movimientos','movimientoItem.php','movimientoItem.png',15),
 (5,12,'Tipo de Ticket','tipoticket.php','tipoticket.png',1),
 (5,13,'Prioridad','prioridad.php','prioridad.png',5),
 (5,14,'Servicio','servicio.php','servicio.png',15),
 (5,15,'Prioridad x Servicio','prioridadxservicio.php','prioridadxservicio.png',20),
 (5,16,'Analista x Servicio','analistaxservicio.php','analistaxservicio.png',25),
 (5,17,'Horario','horario.php','horario.png',30),
 (5,18,'Horario Analista','horarioxanalista.php','horarioxanalista.png',35),
 (5,19,'Encuesta','encuesta.php','encuesta.png',40),
 (6,20,'Mis Tickets','miticket.php','miticket.png',1),
 (6,21,'Asignados','ticketAsignado.php','ticketAsignado.png',5),
 (6,22,'Cerrados','ticketCerrado.php','ticketCerrado.png',15),
 (6,23,'Solucion de Problemas','solucionProblemas.php','baseconocimiento.png',20),
 (2,24,'Por Soporte','repSoporte.php','repSoporte.png',1),
 (2,25,'Por Departamentos','repDepartamento.php','repDepartamento.png',2),
 (2,26,'Tickets Atencion','repTicket.php','repTicket.png',3),
 (2,27,'Generales','repGenerales.php','repGenerales.png',4);
/*!40000 ALTER TABLE `submodulo` ENABLE KEYS */;


--
-- Definition of table `tasignacion`
--

DROP TABLE IF EXISTS `tasignacion`;
CREATE TABLE `tasignacion` (
  `usu_asignado` varchar(25) NOT NULL,
  `valoracion` bigint(20) default NULL,
  PRIMARY KEY  (`usu_asignado`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tasignacion`
--

/*!40000 ALTER TABLE `tasignacion` DISABLE KEYS */;
INSERT INTO `tasignacion` (`usu_asignado`,`valoracion`) VALUES 
 ('jmesias',30),
 ('soporte',105),
 ('vrengifo',45);
/*!40000 ALTER TABLE `tasignacion` ENABLE KEYS */;


--
-- Definition of table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
CREATE TABLE `ticket` (
  `tic_id` bigint(20) unsigned NOT NULL auto_increment,
  `usu_id` varchar(25) default NULL COMMENT 'Usuario Afectado',
  `tiptic_id` int(11) default NULL,
  `pri_id` int(11) default NULL,
  `ser_id` int(11) default NULL,
  `tic_resumen` text,
  `tic_descripcion` text,
  `tic_fechahoraapertura` datetime default NULL,
  `tic_fechahoraultmodificacion` datetime default NULL,
  `tic_fechasolucion` datetime default NULL,
  `tic_fechacierre` datetime default NULL,
  `tic_valorencuesta` int(11) default NULL,
  `usu_asignado` varchar(25) default NULL,
  `tipest_id` char(2) default NULL,
  `tic_fechahorainicio` datetime default NULL,
  `tic_fechahorafin` datetime default NULL,
  PRIMARY KEY  (`tic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ticket`
--

/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
INSERT INTO `ticket` (`tic_id`,`usu_id`,`tiptic_id`,`pri_id`,`ser_id`,`tic_resumen`,`tic_descripcion`,`tic_fechahoraapertura`,`tic_fechahoraultmodificacion`,`tic_fechasolucion`,`tic_fechacierre`,`tic_valorencuesta`,`usu_asignado`,`tipest_id`,`tic_fechahorainicio`,`tic_fechahorafin`) VALUES 
 (1,'vrengifo',1,4,3,'Problema de hardware','No arranca el equipo asignado a mi persona','2009-07-22 11:50:18',NULL,NULL,NULL,NULL,'soporte','A','2009-07-22 11:50:18','2009-07-24 10:50:18'),
 (2,'vrengifo',1,4,3,'Problema de hardware','No arranca el equipo asignado a mi persona','2009-07-23 11:51:21',NULL,NULL,NULL,NULL,'soporte','A','2009-07-23 11:51:21','2009-07-27 10:51:21'),
 (3,'vrengifo',1,4,3,'Problema de hardware','No arranca el equipo asignado a mi persona','2009-07-24 11:51:54',NULL,NULL,NULL,NULL,'soporte','A','2009-07-24 11:51:54','2009-07-28 10:51:54'),
 (4,'vrengifo',1,4,3,'Problema de hardware','No arranca el equipo asignado a mi persona','2009-07-25 11:52:55',NULL,NULL,NULL,NULL,'soporte','A','2009-07-27 07:00:00','2009-07-28 14:30:00'),
 (5,'vrengifo',1,4,3,'Problema de hardware','No arranca el equipo asignado a mi persona','2009-07-26 11:53:22',NULL,NULL,NULL,NULL,'soporte','A','2009-07-27 07:00:00','2009-07-28 14:30:00'),
 (6,'vrengifo',1,4,3,'Problema de hardware','No arranca el equipo asignado a mi persona','2009-07-26 11:54:31','2009-07-28 09:08:11',NULL,'2009-07-28 09:08:11',NULL,'vrengifo','CE','2009-07-27 07:00:00','2009-07-28 14:30:00'),
 (8,'vrengifo',2,1,4,'Ayuda urgente -> msg: no tiene autorizacion para realizar esta operacion','Ayuda urgente -> msg: no tiene autorizacion para realizar esta operacion cuando estoy tratando de ingresar informacion del paciente','2009-07-26 12:15:59','2009-07-26 14:52:15',NULL,'2009-07-26 14:52:15',14,'vrengifo','CE','2009-07-27 07:00:00','2009-07-27 11:00:00'),
 (9,'vrengifo',2,2,3,'Prueba con mail','prueba con mail','2009-07-26 18:51:13',NULL,NULL,NULL,NULL,'soporte','A','2009-07-27 07:00:00','2009-07-27 15:00:00'),
 (10,'usuario',2,2,3,'Prueba de ticket','Chequear la asignacion de tickets','2009-07-26 21:30:49',NULL,NULL,NULL,NULL,'jmesias','A','2009-07-27 07:00:00','2009-07-27 15:00:00'),
 (11,'usuario',2,2,3,'otra prueba de asignacion','otra prueba de asignacion','2009-07-26 21:31:55','2009-07-27 11:02:12',NULL,NULL,NULL,'vrengifo','A','2009-07-27 08:00:00','2009-07-27 16:00:00'),
 (12,'usuario',2,3,3,'prueba 3','prueba 3 asignacion','2009-07-26 21:33:06',NULL,NULL,NULL,NULL,'jmesias','A','2009-07-27 07:00:00','2009-07-28 10:30:00');
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;


--
-- Definition of table `tipoaccion`
--

DROP TABLE IF EXISTS `tipoaccion`;
CREATE TABLE `tipoaccion` (
  `tipacc_id` char(2) NOT NULL,
  `tipacc_nombre` varchar(50) default NULL,
  PRIMARY KEY  (`tipacc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tipoaccion`
--

/*!40000 ALTER TABLE `tipoaccion` DISABLE KEYS */;
INSERT INTO `tipoaccion` (`tipacc_id`,`tipacc_nombre`) VALUES 
 ('A','Adjuntar Archivo'),
 ('C','Comentario'),
 ('CR','Creacion'),
 ('S','Solucion'),
 ('T','Transferencia'),
 ('U','Comentario de Usuario');
/*!40000 ALTER TABLE `tipoaccion` ENABLE KEYS */;


--
-- Definition of table `tipoaccionitem`
--

DROP TABLE IF EXISTS `tipoaccionitem`;
CREATE TABLE `tipoaccionitem` (
  `tipaccite_id` int(10) unsigned NOT NULL auto_increment,
  `tipaccite_nombre` varchar(100) default NULL,
  PRIMARY KEY  (`tipaccite_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tipoaccionitem`
--

/*!40000 ALTER TABLE `tipoaccionitem` DISABLE KEYS */;
INSERT INTO `tipoaccionitem` (`tipaccite_id`,`tipaccite_nombre`) VALUES 
 (1,'Asignacion'),
 (2,'Mantenimiento'),
 (3,'Dado de baja'),
 (4,'Desasignacion'),
 (5,'Reasignacion');
/*!40000 ALTER TABLE `tipoaccionitem` ENABLE KEYS */;


--
-- Definition of table `tipoestado`
--

DROP TABLE IF EXISTS `tipoestado`;
CREATE TABLE `tipoestado` (
  `tipest_id` char(2) NOT NULL,
  `tipest_nombre` varchar(50) default NULL,
  `tipest_orden` int(11) default NULL,
  PRIMARY KEY  (`tipest_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tipoestado`
--

/*!40000 ALTER TABLE `tipoestado` DISABLE KEYS */;
INSERT INTO `tipoestado` (`tipest_id`,`tipest_nombre`,`tipest_orden`) VALUES 
 ('',' ',0),
 ('A','Asignado',1),
 ('CA','Cancelado',6),
 ('CE','Cerrado',4),
 ('EP','En Proceso',2),
 ('P','Pendiente',5),
 ('R','Resuelto',3);
/*!40000 ALTER TABLE `tipoestado` ENABLE KEYS */;


--
-- Definition of table `tipoitem`
--

DROP TABLE IF EXISTS `tipoitem`;
CREATE TABLE `tipoitem` (
  `tipite_id` int(10) unsigned NOT NULL auto_increment,
  `tipite_nombre` varchar(100) default NULL,
  `tipite_descripcion` text,
  PRIMARY KEY  (`tipite_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tipoitem`
--

/*!40000 ALTER TABLE `tipoitem` DISABLE KEYS */;
INSERT INTO `tipoitem` (`tipite_id`,`tipite_nombre`,`tipite_descripcion`) VALUES 
 (1,'Software','Software'),
 (2,'Hardware','Hardware'),
 (20,'Prueba','prueba 1234');
/*!40000 ALTER TABLE `tipoitem` ENABLE KEYS */;


--
-- Definition of table `tipoticket`
--

DROP TABLE IF EXISTS `tipoticket`;
CREATE TABLE `tipoticket` (
  `tiptic_id` int(10) unsigned NOT NULL auto_increment,
  `tiptic_nombre` varchar(50) default NULL,
  PRIMARY KEY  (`tiptic_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tipoticket`
--

/*!40000 ALTER TABLE `tipoticket` DISABLE KEYS */;
INSERT INTO `tipoticket` (`tiptic_id`,`tiptic_nombre`) VALUES 
 (1,'Requerimiento'),
 (2,'Incidente');
/*!40000 ALTER TABLE `tipoticket` ENABLE KEYS */;


--
-- Definition of table `tipousuario`
--

DROP TABLE IF EXISTS `tipousuario`;
CREATE TABLE `tipousuario` (
  `tipusu_id` int(10) unsigned NOT NULL auto_increment,
  `tipusu_nombre` varchar(100) default NULL,
  `tipusu_descripcion` text,
  PRIMARY KEY  (`tipusu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tipousuario`
--

/*!40000 ALTER TABLE `tipousuario` DISABLE KEYS */;
INSERT INTO `tipousuario` (`tipusu_id`,`tipusu_nombre`,`tipusu_descripcion`) VALUES 
 (1,'Usuario','u'),
 (2,'Analista / Soporte','so'),
 (3,'Administrador','a'),
 (5,'Proveedor','pro');
/*!40000 ALTER TABLE `tipousuario` ENABLE KEYS */;


--
-- Definition of table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `usu_id` varchar(25) NOT NULL,
  `are_id` int(11) default NULL,
  `per_id` int(11) default NULL,
  `tipusu_id` int(11) default NULL,
  `usu_clave` varchar(25) default NULL,
  `usu_nombre` varchar(150) default NULL,
  `usu_mail` varchar(100) default NULL,
  PRIMARY KEY  (`usu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usuario`
--

/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` (`usu_id`,`are_id`,`per_id`,`tipusu_id`,`usu_clave`,`usu_nombre`,`usu_mail`) VALUES 
 ('jmesias',1,1,3,'jmesias','Juan Carlos Mesias','jaymesias@localhost'),
 ('soporte',1,2,2,'soporte','Soporte Prueba','soporte@localhost'),
 ('usuario',2,3,1,'usuario','Usuario Prueba','usuario@localhost'),
 ('vrengifo',1,1,3,'vrengifo','Victor Rengifo','vrengifo@localhost');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;


--
-- Definition of table `verdaderofalso`
--

DROP TABLE IF EXISTS `verdaderofalso`;
CREATE TABLE `verdaderofalso` (
  `verfal_id` int(10) unsigned NOT NULL,
  `verfal_nombre` varchar(45) NOT NULL,
  `verfal_sino` varchar(45) NOT NULL,
  `verfal_valor` char(1) default NULL,
  PRIMARY KEY  (`verfal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `verdaderofalso`
--

/*!40000 ALTER TABLE `verdaderofalso` DISABLE KEYS */;
INSERT INTO `verdaderofalso` (`verfal_id`,`verfal_nombre`,`verfal_sino`,`verfal_valor`) VALUES 
 (0,'Falso','No','0'),
 (1,'Verdadero','Si','1');
/*!40000 ALTER TABLE `verdaderofalso` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
