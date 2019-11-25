<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2014 - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Version 1.0.0 viernes, 28 de marzo de 2014
--- Modelo Version 1.0.0 lunes, 07 de abril de 2014
--- Jueves 10 de abril de 2014 se agrega carga masiva mediante archivo plano
--- Modelo Version 1.0.4 jueves, 29 de mayo de 2014
--- Modelo Versión 1.1.0 martes, 08 de julio de 2014
--- Septiembre 11 de 2014 Se incorporan forzar acreditación y nota acredita.
--- Modelo Versión 2.9.7 jueves, 01 de octubre de 2015
--- Modelo Versión 2.11.0 lunes, 21 de diciembre de 2015
--- Modelo Versión 2.12.1 domingo, 31 de enero de 2016
--- Sabado, 23 de Abril de 2016 - Se agrega proceso de carga masiva de responsables campus.
--- Modelo Versión 2.12.13 miércoles, 22 de junio de 2016
*/
/** Archivo ofercampus.php.
* Modulo 1715 ofer08oferta.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date sábado, 1 de junio de 2019
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['deb_doc'])!=0){
	$_REQUEST['debug']=1;
	}
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
	}
if ($bDebug){
	$iSegIni=microtime(true);
	$iSegundos=floor($iSegIni);
	$sMili=floor(($iSegIni-$iSegundos)*1000);
	if ($sMili<100){if ($sMili<10){$sMili=':00'.$sMili;}else{$sMili=':0'.$sMili;}}else{$sMili=':'.$sMili;}
	$sDebug=$sDebug.''.date('H:i:s').$sMili.' Inicia pagina <br>';
	}
if (!file_exists('./app.php')){
	echo '<b>Error N 1 de instalaci&oacute;n</b><br>No se ha establecido un archivo de configuraci&oacute;n, por favor comuniquese con el administrador del sistema.';
	die();
	}
mb_internal_encoding('UTF-8');
require './app.php';
require $APP->rutacomun.'unad_sesion.php';
if (isset($APP->https)==0){$APP->https=0;}
if ($APP->https==2){
	$bObliga=false;
	if (isset($_SERVER['HTTPS'])==0){
		$bObliga=true;
		}else{
		if ($_SERVER['HTTPS']!='on'){$bObliga=true;}
		}
	if ($bObliga){
		$pageURL='https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		header('Location:'.$pageURL);
		die();
		}
	}
//if (!file_exists('./opts.php')){require './opts.php';if ($OPT->opcion==1){$bOpcion=true;}}
$bPeticionXAJAX=false;
if ($_SERVER['REQUEST_METHOD']=='POST'){if (isset($_POST['xjxfun'])){$bPeticionXAJAX=true;}}
if (!$bPeticionXAJAX){$_SESSION['u_ultimominuto']=(date('W')*1440)+(date('H')*60)+date('i');}
require $APP->rutacomun.'unad_todas.php';
require $APP->rutacomun.'libs/clsdbadmin.php';
require $APP->rutacomun.'unad_librerias.php';
require $APP->rutacomun.'libhtml.php';
require $APP->rutacomun.'xajax/xajax_core/xajax.inc.php';
require $APP->rutacomun.'unad_xajax.php';
require $APP->rutacomun.'libs/clsplanos.php';
require $APP->rutacomun.'libmail.php';
require $APP->rutacomun.'nusoap/nusoap.php';
require 'liboai.php';
require $APP->rutacomun.'libdatos.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1701;
$iCodModulo=1715;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_1707='lg/lg_1707_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1707)){$mensajes_1707='lg/lg_1707_es.php';}
require $mensajes_todas;
require $mensajes_1707;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
if ($bDebug){
	$sDebug=$sDebug.''.fecha_microtiempo().' Probando conexi&oacute;n con la base de datos <b>'.$APP->dbname.'</b> en <b>'.$APP->dbhost.'</b><br>';
	}
if (!$objDB->Conectar()){
	$bCerrado=true;
	if ($bDebug){
		$sDebug=$sDebug.''.fecha_microtiempo().' Error al intentar conectar con la base de datos <b>'.$objDB->serror.'</b><br>';
		}
	}
$idTercero=$_SESSION['unad_id_tercero'];
$bOtroUsuario=false;
if (isset($_REQUEST['deb_doc'])!=0){
	if (seg_revisa_permiso($iCodModulo, 1707, $objDB)){
		$sSQL='SELECT unad11id, unad11razonsocial FROM unad11terceros WHERE unad11doc="'.$_REQUEST['deb_doc'].'"';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$idTercero=$fila['unad11id'];
			$bOtroUsuario=true;
			if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Se verifica la ventana de trabajo para el usuario '.$fila['unad11razonsocial'].'.<br>';}
			}else{
			$sError='No se ha encontrado el documento &quot;'.$_REQUEST['deb_doc'].'&quot;';
			$_REQUEST['deb_doc']='';
			}
		}else{
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' No cuenta con permiso de ingreso como otro usuario Modulo '.$iCodModulo.' Permiso.<br>';}
		$_REQUEST['deb_doc']='';
		}
	}else{
	$_REQUEST['deb_doc']='';
	}
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=ofercertifica.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
$sRutaCore='../core/';
$mensajes_1711=$APP->rutacomun.'lg/lg_1711_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1711)){$mensajes_1711=$APP->rutacomun.'lg/lg_1711_es.php';}
$mensajes_1712='lg/lg_1712_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1712)){$mensajes_1712='lg/lg_1712_es.php';}
$mensajes_1713='lg/lg_1713_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1713)){$mensajes_1713='lg/lg_1713_es.php';}
$mensajes_1730='lg/lg_1730_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1730)){$mensajes_1730='lg/lg_1730_es.php';}
$mensajes_1738='lg/lg_1738_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_1738)){$mensajes_1738='lg/lg_1738_es.php';}
$mensajes_148=$sRutaCore.'lg/lg_148_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_148)){$mensajes_148=$sRutaCore.'lg/lg_148_es.php';}
require $mensajes_1711;
require $mensajes_1712;
require $mensajes_1713;
require $mensajes_1730;
require $mensajes_1738;
require $mensajes_148;
if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Termino de llamar las librerias<br>';}
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 1707 ofer08oferta
require 'lib1707.php';
require 'lib1707campus.php';
// -- 1708 Libreria de oferta
require 'lib1708.php';
// -- 1711 Actores
require $APP->rutacomun.'lib1711.php';
// -- 1712 Historico de oferta
require 'lib1712.php';
// -- 1713 Anotaciones
require 'lib1713.php';
//funciones importadas para la acreditación.
//Rubrica de evaluacion
// -- 1730 Cambios de situacion
// -- 148 Aulas adicionales
require 'lib148.php';
// -- 1718 Detalle de las agendas
require 'lib1718.php';
// -- 1730 Cambios de situacion
require 'lib1730.php';
// Se cambia la ubicacion por defecto para permitir el uso en los pasos personalizados
// -- 1738 Matricula del curso
require 'lib1738.php';
//La clase foro.
require $APP->rutacomun.'libs/clsforo.php';
//Archivos de ayuda estatica.
require 'ayuda/ayuda1707_es.php';

require 'lib1711_evalua.php';
require 'lib1712_evalua.php';
require 'lib1713_evalua.php';

$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'Cargar_ofer08idcurso');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f1707_HtmlTablaCampus');
$xajax->register(XAJAX_FUNCTION,'f1707_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f1707_InfoCurso');
$xajax->register(XAJAX_FUNCTION,'f1711_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1711_Traer');
$xajax->register(XAJAX_FUNCTION,'f1711_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1711_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1711_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f1712_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1712_Traer');
$xajax->register(XAJAX_FUNCTION,'f1712_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1712_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1712_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f1713_Guardar');
$xajax->register(XAJAX_FUNCTION,'f1713_Traer');
$xajax->register(XAJAX_FUNCTION,'f1713_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f1713_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1713_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f1718_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1730_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f1738_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f148_Guardar');
$xajax->register(XAJAX_FUNCTION,'f148_Traer');
$xajax->register(XAJAX_FUNCTION,'f148_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f148_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f148_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'bExisteRol');
$xajax->register(XAJAX_FUNCTION,'Agenda_Actualizar');
$xajax->register(XAJAX_FUNCTION,'Agenda_Cambia');
$xajax->register(XAJAX_FUNCTION,'Agenda_ActualizarHija');
require $APP->rutacomun.'libforoxajax.php';
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$bcargo=false;
$sError='';
$sErrorCerrando='';
$sErrDatos='';
$iTipoError=0;
$bLimpiaHijos=false;
$bMueveScroll=false;
$iSector=1;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf1707'])==0){$_REQUEST['paginaf1707']=1;}
if (isset($_REQUEST['lppf1707'])==0){$_REQUEST['lppf1707']=20;}
if (isset($_REQUEST['boculta1707'])==0){$_REQUEST['boculta1707']=1;}
if (isset($_REQUEST['paginaf1711'])==0){$_REQUEST['paginaf1711']=1;}
if (isset($_REQUEST['lppf1711'])==0){$_REQUEST['lppf1711']=10;}
if (isset($_REQUEST['boculta1711'])==0){$_REQUEST['boculta1711']=1;}
if (isset($_REQUEST['paginaf1712'])==0){$_REQUEST['paginaf1712']=1;}
if (isset($_REQUEST['lppf1712'])==0){$_REQUEST['lppf1712']=20;}
if (isset($_REQUEST['boculta1712'])==0){$_REQUEST['boculta1712']=1;}
if (isset($_REQUEST['paginaf1713'])==0){$_REQUEST['paginaf1713']=1;}
if (isset($_REQUEST['lppf1713'])==0){$_REQUEST['lppf1713']=20;}
if (isset($_REQUEST['boculta1713'])==0){$_REQUEST['boculta1713']=1;}
if (isset($_REQUEST['paginaf1718'])==0){$_REQUEST['paginaf1718']=1;}
if (isset($_REQUEST['lppf1718'])==0){$_REQUEST['lppf1718']=20;}
if (isset($_REQUEST['paginaf1730'])==0){$_REQUEST['paginaf1730']=1;}
if (isset($_REQUEST['lppf1730'])==0){$_REQUEST['lppf1730']=5;}
if (isset($_REQUEST['boculta1730'])==0){$_REQUEST['boculta1730']=0;}
if (isset($_REQUEST['paginaf1738'])==0){$_REQUEST['paginaf1738']=1;}
if (isset($_REQUEST['lppf1738'])==0){$_REQUEST['lppf1738']=20;}
if (isset($_REQUEST['boculta1738'])==0){$_REQUEST['boculta1738']=0;}
if (isset($_REQUEST['paginaf148'])==0){$_REQUEST['paginaf148']=1;}
if (isset($_REQUEST['lppf148'])==0){$_REQUEST['lppf148']=20;}
if (isset($_REQUEST['boculta148'])==0){$_REQUEST['boculta148']=1;}
if (isset($_REQUEST['paginaf1791'])==0){$_REQUEST['paginaf1791']=1;}
if (isset($_REQUEST['lppf1791'])==0){$_REQUEST['lppf1791']=5;}
if (isset($_REQUEST['boculta1791'])==0){$_REQUEST['boculta1791']=1;}
if (isset($_REQUEST['paginaf1792'])==0){$_REQUEST['paginaf1791']=2;}
if (isset($_REQUEST['lppf1792'])==0){$_REQUEST['lppf1792']=5;}
if (isset($_REQUEST['boculta1792'])==0){$_REQUEST['boculta1792']=1;}

// -- Inicializar variables de datos.
if (isset($_REQUEST['ofer08idper_aca'])==0){$_REQUEST['ofer08idper_aca']=$_SESSION['oai_per_aca'];}
if (isset($_REQUEST['ofer08idcurso'])==0){$_REQUEST['ofer08idcurso']='';}
if (isset($_REQUEST['ofer08cead'])==0){$_REQUEST['ofer08cead']='';}
if (isset($_REQUEST['ofer08id'])==0){$_REQUEST['ofer08id']='';}
if (isset($_REQUEST['ofer08idescuela'])==0){$_REQUEST['ofer08idescuela']='-1';}
if (isset($_REQUEST['ofer08estadooferta'])==0){$_REQUEST['ofer08estadooferta']=0;}
if (isset($_REQUEST['ofer08idagenda'])==0){$_REQUEST['ofer08idagenda']='';}
if (isset($_REQUEST['ofer08diainical'])==0){$_REQUEST['ofer08diainical']='';}
if (isset($_REQUEST['ofer08numestudiantes'])==0){$_REQUEST['ofer08numestudiantes']=0;}
if (isset($_REQUEST['ofer08numestaula1'])==0){$_REQUEST['ofer08numestaula1']=0;}
if (isset($_REQUEST['ofer08fechaoferta'])==0){$_REQUEST['ofer08fechaoferta']='00/00/0000';}//fecha_hoy();}
if (isset($_REQUEST['ofer08fechacancela'])==0){$_REQUEST['ofer08fechacancela']='00/00/0000';}//fecha_hoy();}
if (isset($_REQUEST['ofer08estadocampus'])==0){$_REQUEST['ofer08estadocampus']=0;}
if (isset($_REQUEST['ofer08idnav'])==0){$_REQUEST['ofer08idnav']='';}
if (isset($_REQUEST['ofer08origen'])==0){$_REQUEST['ofer08origen']='';}
if (isset($_REQUEST['ofer08fechasolicrestaurar'])==0){$_REQUEST['ofer08fechasolicrestaurar']='00/00/0000';}//fecha_hoy();}
if (isset($_REQUEST['ofer08migrados'])==0){$_REQUEST['ofer08migrados']='';}
if (isset($_REQUEST['ofer08fecharestaurado'])==0){$_REQUEST['ofer08fecharestaurado']='00/00/0000';}//fecha_hoy();}
if (isset($_REQUEST['ofer08idusariorestaura'])==0){$_REQUEST['ofer08idusariorestaura']=0;}//$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['ofer08idusariorestaura_td'])==0){$_REQUEST['ofer08idusariorestaura_td']=$APP->tipo_doc;}
if (isset($_REQUEST['ofer08idusariorestaura_doc'])==0){$_REQUEST['ofer08idusariorestaura_doc']='';}
if (isset($_REQUEST['ofer08fechaaccede'])==0){$_REQUEST['ofer08fechaaccede']='00/00/0000';}//fecha_hoy();}
if (isset($_REQUEST['ofer08usuarioconfirmaacceso'])==0){$_REQUEST['ofer08usuarioconfirmaacceso']=0;}//$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['ofer08usuarioconfirmaacceso_td'])==0){$_REQUEST['ofer08usuarioconfirmaacceso_td']=$APP->tipo_doc;}
if (isset($_REQUEST['ofer08usuarioconfirmaacceso_doc'])==0){$_REQUEST['ofer08usuarioconfirmaacceso_doc']='';}
if (isset($_REQUEST['ofer08fechaaprobado'])==0){$_REQUEST['ofer08fechaaprobado']='00/00/0000';}//fecha_hoy();}
if (isset($_REQUEST['ofer08idaprueba'])==0){$_REQUEST['ofer08idaprueba']=0;}//$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['ofer08idaprueba_td'])==0){$_REQUEST['ofer08idaprueba_td']=$APP->tipo_doc;}
if (isset($_REQUEST['ofer08idaprueba_doc'])==0){$_REQUEST['ofer08idaprueba_doc']='';}
if (isset($_REQUEST['ofer08fechaacredita'])==0){$_REQUEST['ofer08fechaacredita']='';}//fecha_hoy();}
if (isset($_REQUEST['ofer08idacredita'])==0){$_REQUEST['ofer08idacredita']=0;}//$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['ofer08idacredita_td'])==0){$_REQUEST['ofer08idacredita_td']=$APP->tipo_doc;}
if (isset($_REQUEST['ofer08idacredita_doc'])==0){$_REQUEST['ofer08idacredita_doc']='';}
if (isset($_REQUEST['ofer08idevalacredita'])==0){$_REQUEST['ofer08idevalacredita']='';}
if (isset($_REQUEST['ofer08puntajeacredita'])==0){$_REQUEST['ofer08puntajeacredita']='';}
if (isset($_REQUEST['ofer08restaurado'])==0){$_REQUEST['ofer08restaurado']='N';}
if (isset($_REQUEST['ofer08idcursoncontents'])==0){$_REQUEST['ofer08restaurado']=0;}
if (isset($_REQUEST['ofer08idcursonav'])==0){$_REQUEST['ofer08idcursonav']=0;}
if (isset($_REQUEST['ofer08tipostandard'])==0){$_REQUEST['ofer08tipostandard']=-1;}
if (isset($_REQUEST['ofer08obligaacreditar'])==0){$_REQUEST['ofer08obligaacreditar']='S';}
if (isset($_REQUEST['ofer08notaacredita'])==0){$_REQUEST['ofer08notaacredita']='';}
if (isset($_REQUEST['ofer08idnavalista'])==0){$_REQUEST['ofer08idnavalista']='';}
if (isset($_REQUEST['ofer08fechaestadocampus'])==0){$_REQUEST['ofer08fechaestadocampus']='';}//{fecha_hoy();}
if (isset($_REQUEST['ofer08motivocancela'])==0){$_REQUEST['ofer08motivocancela']='';}
if (isset($_REQUEST['ofer08procesado'])==0){$_REQUEST['ofer08procesado']='';}
if (isset($_REQUEST['ofer08metodomatricula'])==0){$_REQUEST['ofer08metodomatricula']='';}
if (isset($_REQUEST['ofer08copiaidusuario'])==0){$_REQUEST['ofer08copiaidusuario']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['ofer08copiaidusuario_td'])==0){$_REQUEST['ofer08copiaidusuario_td']=$APP->tipo_doc;}
if (isset($_REQUEST['ofer08copiaidusuario_doc'])==0){$_REQUEST['ofer08copiaidusuario_doc']='';}
if (isset($_REQUEST['ofer08copiafecha'])==0){$_REQUEST['ofer08copiafecha']='';}//{fecha_hoy();}
if (isset($_REQUEST['ofer08copiaruta'])==0){$_REQUEST['ofer08copiaruta']='';}
if (isset($_REQUEST['ofer08idresponsablepti'])==0){$_REQUEST['ofer08idresponsablepti']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['ofer08idresponsablepti_td'])==0){$_REQUEST['ofer08idresponsablepti_td']=$APP->tipo_doc;}
if (isset($_REQUEST['ofer08idresponsablepti_doc'])==0){$_REQUEST['ofer08idresponsablepti_doc']='';}
if (isset($_REQUEST['ofer08incluyelaboratorio'])==0){$_REQUEST['ofer08incluyelaboratorio']='N';}
if (isset($_REQUEST['ofer08puntajelaboratorio'])==0){$_REQUEST['ofer08puntajelaboratorio']='';}
if (isset($_REQUEST['ofer08incluyesalida'])==0){$_REQUEST['ofer08incluyesalida']='N';}
if (isset($_REQUEST['ofer08puntajesalida'])==0){$_REQUEST['ofer08puntajesalida']='';}
if (isset($_REQUEST['ofer08tablacidusuario'])==0){$_REQUEST['ofer08tablacidusuario']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['ofer08tablacidusuario_td'])==0){$_REQUEST['ofer08tablacidusuario_td']=$APP->tipo_doc;}
if (isset($_REQUEST['ofer08tablacidusuario_doc'])==0){$_REQUEST['ofer08tablacidusuario_doc']='';}
if (isset($_REQUEST['ofer08tablacfecha'])==0){$_REQUEST['ofer08tablacfecha']='';}//{fecha_hoy();}
if (isset($_REQUEST['ofer08tablacruta'])==0){$_REQUEST['ofer08tablacruta']='';}
if (isset($_REQUEST['ofer08numrevisiones'])==0){$_REQUEST['ofer08numrevisiones']=0;}
if (isset($_REQUEST['ofer08idorigenoferta'])==0){$_REQUEST['ofer08idorigenoferta']=0;}
if (isset($_REQUEST['ofer08grupoidforma'])==0){$_REQUEST['ofer08grupoidforma']=0;}
if (isset($_REQUEST['ofer08grupominest'])==0){$_REQUEST['ofer08grupominest']=5;}
if (isset($_REQUEST['ofer08grupomaxest'])==0){$_REQUEST['ofer08grupomaxest']=5;}
if (isset($_REQUEST['ofer08idcohorte'])==0){$_REQUEST['ofer08idcohorte']='';}
if (isset($_REQUEST['ofer08numdevoluciones'])==0){$_REQUEST['ofer08numdevoluciones']=0;}
if (isset($_REQUEST['ofer08numajustes'])==0){$_REQUEST['ofer08numajustes']=0;}
if (isset($_REQUEST['ofer08estadodiseno'])==0){$_REQUEST['ofer08estadodiseno']=0;}
if ((int)$_REQUEST['paso']>0){
	//Actores
	if (isset($_REQUEST['ofer11per_aca'])==0){$_REQUEST['ofer11per_aca']='';}
	if (isset($_REQUEST['ofer11idescuela'])==0){$_REQUEST['ofer11idescuela']='';}
	if (isset($_REQUEST['ofer11idrol'])==0){$_REQUEST['ofer11idrol']='';}
	if (isset($_REQUEST['ofer11id'])==0){$_REQUEST['ofer11id']='';}
	if (isset($_REQUEST['ofer11idtercero'])==0){$_REQUEST['ofer11idtercero']=0;}//$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['ofer11idtercero_td'])==0){$_REQUEST['ofer11idtercero_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['ofer11idtercero_doc'])==0){$_REQUEST['ofer11idtercero_doc']='';}
	if (isset($_REQUEST['ofer11detalle'])==0){$_REQUEST['ofer11detalle']='';}
	if (isset($_REQUEST['ofer11fechaacceso'])==0){$_REQUEST['ofer11fechaacceso']='';}
	//Historico de oferta
	if (isset($_REQUEST['ofer12consec'])==0){$_REQUEST['ofer12consec']='';}
	if (isset($_REQUEST['ofer12id'])==0){$_REQUEST['ofer12id']='';}
	if (isset($_REQUEST['ofer12fechaoferta'])==0){$_REQUEST['ofer12fechaoferta']='';}//fecha_hoy();}
	if (isset($_REQUEST['ofer12fechacancela'])==0){$_REQUEST['ofer12fechacancela']='';}//fecha_hoy();}
	if (isset($_REQUEST['ofer12idtercero'])==0){$_REQUEST['ofer12idtercero']=0;}//$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['ofer12idtercero_td'])==0){$_REQUEST['ofer12idtercero_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['ofer12idtercero_doc'])==0){$_REQUEST['ofer12idtercero_doc']='';}
	//Anotaciones
	if (isset($_REQUEST['ofer13consec'])==0){$_REQUEST['ofer13consec']='';}
	if (isset($_REQUEST['ofer13id'])==0){$_REQUEST['ofer13id']='';}
	if (isset($_REQUEST['ofer13fecha'])==0){$_REQUEST['ofer13fecha']=fecha_hoy();}
	if (isset($_REQUEST['ofer13hora'])==0){$_REQUEST['ofer13hora']=fecha_hora();}
	if (isset($_REQUEST['ofer13minuto'])==0){$_REQUEST['ofer13minuto']=fecha_minuto();}
	if (isset($_REQUEST['ofer13idusuario'])==0){$_REQUEST['ofer13idusuario']=$_SESSION['unad_id_tercero'];}
	if (isset($_REQUEST['ofer13idusuario_td'])==0){$_REQUEST['ofer13idusuario_td']=$APP->tipo_doc;}
	if (isset($_REQUEST['ofer13idusuario_doc'])==0){$_REQUEST['ofer13idusuario_doc']='';}
	if (isset($_REQUEST['ofer13anotacion'])==0){$_REQUEST['ofer13anotacion']='';}
	if (isset($_REQUEST['ofer13atendida'])==0){$_REQUEST['ofer13atendida']='N';}
	//Aulas adicionales
	if (isset($_REQUEST['unad48per_aca'])==0){$_REQUEST['unad48per_aca']=$_REQUEST['ofer08idper_aca'];}
	if (isset($_REQUEST['unad48consec'])==0){$_REQUEST['unad48consec']='';}
	if (isset($_REQUEST['unad48id'])==0){$_REQUEST['unad48id']='';}
	if (isset($_REQUEST['unad48identificador'])==0){$_REQUEST['unad48identificador']='';}
	if (isset($_REQUEST['unad48numestudiantes'])==0){$_REQUEST['unad48numestudiantes']='';}
	if (isset($_REQUEST['unad48diainicial'])==0){$_REQUEST['unad48diainicial']='';}
	}
//Cambios de situacion
if (isset($_REQUEST['ofer30anotacion'])==0){$_REQUEST['ofer30anotacion']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['boculta1707carga'])==0){$_REQUEST['boculta1707carga']=1;}
if (isset($_REQUEST['masivo_ofertar'])==0){$_REQUEST['masivo_ofertar']='S';}
if (isset($_REQUEST['bescuela'])==0){$_REQUEST['bescuela']='';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if (isset($_REQUEST['bcodcurso'])==0){$_REQUEST['bcodcurso']='';}
if (isset($_REQUEST['blistar2'])==0){$_REQUEST['blistar2']='';}
if (isset($_REQUEST['bnav'])==0){$_REQUEST['bnav']='';}
if (isset($_REQUEST['bproceso'])==0){$_REQUEST['bproceso']='';}
if (isset($_REQUEST['bresponsable'])==0){$_REQUEST['bresponsable']='';}
if (isset($_REQUEST['bestandar'])==0){$_REQUEST['bestandar']='';}
if (isset($_REQUEST['bcohorte'])==0){$_REQUEST['bcohorte']='';}
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
//Verificar a que tiene permiso de acceder.
$iEstadoBase=20;
//if ($_SESSION['oai_per_aca']<222){$iEstadoBase=1;}
$sCondiEscuelas='';
$iPrimerEscuela='';
list($iNumEscuelas, $sListaEscuelas, $iPrimerEscuela)=OFER_NumEscuelasV2($idTercero, $objDB);
if (($_REQUEST['ofer08idescuela']=='-1')||($_REQUEST['ofer08idescuela']=='')){$_REQUEST['ofer08idescuela']=$iPrimerEscuela;}
if ($_REQUEST['bescuela']==''){$_REQUEST['bescuela']=$iPrimerEscuela;}
if ($iNumEscuelas!=-1){
	$sCondiEscuelas='exte01id IN (99'.$sListaEscuelas.')';
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['ofer08idusariorestaura_td']=$APP->tipo_doc;
	$_REQUEST['ofer08idusariorestaura_doc']='';
	$_REQUEST['ofer08usuarioconfirmaacceso_td']=$APP->tipo_doc;
	$_REQUEST['ofer08usuarioconfirmaacceso_doc']='';
	$_REQUEST['ofer08idaprueba_td']=$APP->tipo_doc;
	$_REQUEST['ofer08idaprueba_doc']='';
	$_REQUEST['ofer08idacredita_td']=$APP->tipo_doc;
	$_REQUEST['ofer08idacredita_doc']='';
	$_REQUEST['ofer08idresponsablepti_td']=$APP->tipo_doc;
	$_REQUEST['ofer08idresponsablepti_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='ofer08idper_aca='.$_REQUEST['ofer08idper_aca'].' AND ofer08idcurso='.$_REQUEST['ofer08idcurso'].' AND ofer08cead='.$_REQUEST['ofer08cead'].'';
		}else{
		$sSQLcondi='ofer08id='.$_REQUEST['ofer08id'].'';
		}
	$sSQL='SELECT * FROM ofer08oferta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['ofer08idper_aca']=$fila['ofer08idper_aca'];
		$_REQUEST['ofer08idcurso']=$fila['ofer08idcurso'];
		$_REQUEST['ofer08cead']=$fila['ofer08cead'];
		$_REQUEST['ofer08id']=$fila['ofer08id'];
		$_REQUEST['ofer08idescuela']=$fila['ofer08idescuela'];
		$_REQUEST['ofer08estadooferta']=$fila['ofer08estadooferta'];
		$_REQUEST['ofer08idagenda']=$fila['ofer08idagenda'];
		$_REQUEST['ofer08diainical']=$fila['ofer08diainical'];
		$_REQUEST['ofer08numestudiantes']=$fila['ofer08numestudiantes'];
		$_REQUEST['ofer08numestaula1']=$fila['ofer08numestaula1'];
		$_REQUEST['ofer08fechaoferta']=$fila['ofer08fechaoferta'];
		$_REQUEST['ofer08fechacancela']=$fila['ofer08fechacancela'];
		$_REQUEST['ofer08estadocampus']=$fila['ofer08estadocampus'];
		$_REQUEST['ofer08idnav']=$fila['ofer08idnav'];
		$_REQUEST['ofer08origen']=$fila['ofer08origen'];
		$_REQUEST['ofer08fechasolicrestaurar']=$fila['ofer08fechasolicrestaurar'];
		$_REQUEST['ofer08migrados']=$fila['ofer08migrados'];
		$_REQUEST['ofer08fecharestaurado']=$fila['ofer08fecharestaurado'];
		$_REQUEST['ofer08idusariorestaura']=$fila['ofer08idusariorestaura'];
		$_REQUEST['ofer08fechaaccede']=$fila['ofer08fechaaccede'];
		$_REQUEST['ofer08usuarioconfirmaacceso']=$fila['ofer08usuarioconfirmaacceso'];
		$_REQUEST['ofer08fechaaprobado']=$fila['ofer08fechaaprobado'];
		$_REQUEST['ofer08idaprueba']=$fila['ofer08idaprueba'];
		$_REQUEST['ofer08fechaacredita']=$fila['ofer08fechaacredita'];
		$_REQUEST['ofer08idacredita']=$fila['ofer08idacredita'];
		$_REQUEST['ofer08idevalacredita']=$fila['ofer08idevalacredita'];
		$_REQUEST['ofer08puntajeacredita']=$fila['ofer08puntajeacredita'];
		$_REQUEST['ofer08restaurado']=$fila['ofer08restaurado'];
		$_REQUEST['ofer08idcursoncontents']=$fila['ofer08idcursoncontents'];
		$_REQUEST['ofer08idcursonav']=$fila['ofer08idcursonav'];
		$_REQUEST['ofer08tipostandard']=$fila['ofer08tipostandard'];
		$_REQUEST['ofer08obligaacreditar']=$fila['ofer08obligaacreditar'];
		$_REQUEST['ofer08notaacredita']=$fila['ofer08notaacredita'];
		$_REQUEST['ofer08idnavalista']=$fila['ofer08idnavalista'];
		$_REQUEST['ofer08fechaestadocampus']=$fila['ofer08fechaestadocampus'];
		$_REQUEST['ofer08motivocancela']=$fila['ofer08motivocancela'];
		$_REQUEST['ofer08procesado']=$fila['ofer08procesado'];
		$_REQUEST['ofer08metodomatricula']=$fila['ofer08metodomatricula'];
		$_REQUEST['ofer08copiaidusuario']=$fila['ofer08copiaidusuario'];
		$_REQUEST['ofer08copiafecha']=$fila['ofer08copiafecha'];
		$_REQUEST['ofer08copiaruta']=$fila['ofer08copiaruta'];
		$_REQUEST['ofer08idresponsablepti']=$fila['ofer08idresponsablepti'];
		$_REQUEST['ofer08incluyelaboratorio']=$fila['ofer08incluyelaboratorio'];
		$_REQUEST['ofer08puntajelaboratorio']=$fila['ofer08puntajelaboratorio'];
		$_REQUEST['ofer08incluyesalida']=$fila['ofer08incluyesalida'];
		$_REQUEST['ofer08puntajesalida']=$fila['ofer08puntajesalida'];
		$_REQUEST['ofer08numrevisiones']=$fila['ofer08numrevisiones'];
		$_REQUEST['ofer08idorigenoferta']=$fila['ofer08idorigenoferta'];
		$_REQUEST['ofer08grupoidforma']=$fila['ofer08grupoidforma'];
		$_REQUEST['ofer08grupominest']=$fila['ofer08grupominest'];
		$_REQUEST['ofer08grupomaxest']=$fila['ofer08grupomaxest'];
		$_REQUEST['ofer08tablacidusuario']=$fila['ofer08tablacidusuario'];
		$_REQUEST['ofer08tablacfecha']=$fila['ofer08tablacfecha'];
		$_REQUEST['ofer08tablacruta']=$fila['ofer08tablacruta'];
		$_REQUEST['ofer08numestaula1']=$fila['ofer08numestaula1'];
		$_REQUEST['ofer08idcohorte']=$fila['ofer08idcohorte'];
		$_REQUEST['ofer08numdevoluciones']=$fila['ofer08numdevoluciones'];
		$_REQUEST['ofer08numajustes']=$fila['ofer08numajustes'];
		$_REQUEST['ofer08estadodiseno']=$fila['ofer08estadodiseno'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta1707']=0;
		if ($_REQUEST['ofer08idcursonav']==0){
			$_REQUEST['ofer08idcursonav']=OAI_TraerIdCursoMoodle($_REQUEST['ofer08idper_aca'], $_REQUEST['ofer08idcurso'], 1, $_REQUEST['ofer08id'], $objDB);
			//SE GUARDAR EL DATO.
			$sSQL='UPDATE ofer08oferta SET ofer08idcursonav='.$_REQUEST['ofer08idcursonav'].' WHERE ofer08id='.$_REQUEST['ofer08id'].'';
			$tabla=$objDB->ejecutasql($sSQL);
			}
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Registrar la copia de seguridad.
$bGuardaCopia=false;
if ($_REQUEST['paso']==44){
	$_REQUEST['paso']=12;
	$bGuardaCopia=true;
	$bMueveScroll=true;
	}
//Registrar la tabla de calificaciones.
$bGuardaTablaCalificaciones=false;
if ($_REQUEST['paso']==45){
	$_REQUEST['paso']=12;
	$bGuardaTablaCalificaciones=true;
	$bMueveScroll=true;
	}
//Insertar o modificar un elemento
if ($_REQUEST['paso']==12){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f1707_db_GuardarV3Campus($_REQUEST, $objDB, $bGuardaCopia, $bGuardaTablaCalificaciones, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		$bEntra=false;
		$idProcesoBitacora=1701;
		if ($bGuardaCopia){$bEntra=true;}
		if ($bGuardaTablaCalificaciones){
			$bEntra=true;
			$idProcesoBitacora=1703;
			}
		if ($bEntra){
			$ofer08idcurso_nombre=OAI_NombreCurso($_REQUEST['ofer08idcurso'], $objDB);
			require $APP->rutacomun.'libs/cls1504.php';
			$objBitacora=new clsT1504();
			$objBitacora->nuevo($idProcesoBitacora);
			$objBitacora->bita04detalle='Copia de curso '.$_REQUEST['ofer08idcurso'].' '.$ofer08idcurso_nombre;
			if ($bGuardaTablaCalificaciones){
				$objBitacora->bita04detalle='Tabla de calificaciones curso: '.$_REQUEST['ofer08idcurso'].' '.$ofer08idcurso_nombre.' 
'.$_REQUEST['ofer08tablacruta'];
				}
			list($sErrorB, $iTipoErrorB, $idAccion, $sDebugB)=$objBitacora->guardar($objDB, $bDebug);
			if ($iTipoErrorB==0){
				$sError=$sError.'<br>ERROR AL GENERAR LA BITACORA: '.$sErrorB;
				}else{
				$sError=$sError.'<br>Se ha generado la bitacora '.$objBitacora->bita04consec;
				}
			}
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	// -- Solamente la ventana de oferta tiene permiso de eliminar.
	$sError=$ERR['4'];
	}

//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['ofer08idper_aca']=$_SESSION['oai_per_aca'];
	$_REQUEST['ofer08idcurso']='';
	$_REQUEST['ofer08cead']='';
	$_REQUEST['ofer08id']='';
	//$_REQUEST['ofer08idescuela']='-1';
	$_REQUEST['ofer08estadooferta']=0;
	$_REQUEST['ofer08idagenda']='';
	$_REQUEST['ofer08diainical']=0;
	$_REQUEST['ofer08numestudiantes']=0;
	$_REQUEST['ofer08numestaula1']=0;
	$_REQUEST['ofer08fechaoferta']='00/00/0000';//fecha_hoy();
	$_REQUEST['ofer08fechacancela']='00/00/0000';//fecha_hoy();
	$_REQUEST['ofer08estadocampus']=0;
	$_REQUEST['ofer08idnav']='';
	$_REQUEST['ofer08idcursonav']='';
	$_REQUEST['ofer08origen']='';
	$_REQUEST['ofer08fechasolicrestaurar']='00/00/0000';
	$_REQUEST['ofer08migrados']='';
	$_REQUEST['ofer08fecharestaurado']='00/00/0000';
	$_REQUEST['ofer08idusariorestaura']='';
	$_REQUEST['ofer08fechaaccede']='00/00/0000';
	$_REQUEST['ofer08usuarioconfirmaacceso']='';
	$_REQUEST['ofer08fechaaprobado']='00/00/0000';//fecha_hoy();
	$_REQUEST['ofer08idaprueba']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['ofer08idaprueba_td']=$APP->tipo_doc;
	$_REQUEST['ofer08idaprueba_doc']='';
	$_REQUEST['ofer08restaurado']='N';
	$_REQUEST['ofer08numestaula1']='';
	$_REQUEST['ofer08idcursonav']='';
	$_REQUEST['ofer08tipostandard']=-1;
	$_REQUEST['ofer08obligaacreditar']='S';
	$_REQUEST['ofer08notaacredita']='';
	$_REQUEST['ofer08metodomatricula']=0;
	$_REQUEST['ofer08incluyelaboratorio']='N';
	$_REQUEST['ofer08puntajelaboratorio']='';
	$_REQUEST['ofer08incluyesalida']='N';
	$_REQUEST['ofer08puntajesalida']='';
	$_REQUEST['ofer08numrevisiones']=0;
	$_REQUEST['ofer08idorigenoferta']=0;
	$_REQUEST['ofer08grupoidforma']=0;
	$_REQUEST['ofer08grupominest']=5;
	$_REQUEST['ofer08grupomaxest']=5;
	$_REQUEST['ofer08idcohorte']='';
	$_REQUEST['ofer08numdevoluciones']=0;
	$_REQUEST['ofer08numajustes']=0;
	$_REQUEST['ofer08estadodiseno']=0;
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['ofer11idrol']='';
	$_REQUEST['ofer11id']='';
	$_REQUEST['ofer11idtercero']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['ofer11idtercero_td']=$APP->tipo_doc;
	$_REQUEST['ofer11idtercero_doc']='';
	
	$_REQUEST['unad48consec']='';
	$_REQUEST['unad48id']='';
	$_REQUEST['unad48identificador']='';
	$_REQUEST['unad48numestudiantes']='';
	$_REQUEST['unad48diainicial']='';
	}
//ofertar el curso
if ($_REQUEST['paso']==21){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	}
//cancelar la oferta
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	}
//Acreditado
if ($_REQUEST['paso']==24){
	}
//25 - Confirma acceso
if ($_REQUEST['paso']==25){
	$_REQUEST['paso']=2;
	}
// 26 Informa que no puede acceder
if ($_REQUEST['paso']==26){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	}
// Solicitar Revision ncontents
if ($_REQUEST['paso']==27){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	}
// Enviar a acreditacion - {Alistamiento}
if ($_REQUEST['paso']==28){
	$_REQUEST['paso']=2;
	}
//termina la evaluacion...
$iAprueba=0;
if ($_REQUEST['paso']==29){
	$_REQUEST['paso']=2;
	}
//enviar a VIMEP
if ($_REQUEST['paso']==30){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	}
//devolver a evaluacion
if ($_REQUEST['paso']==31){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	}
//acreditado
if ($_REQUEST['paso']==32){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	}
//acreditacion devuelta
if ($_REQUEST['paso']==33){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	}
//aprobado
if ($_REQUEST['paso']==34){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	}
// activar un curso que no accede.
if ($_REQUEST['paso']==36){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Inicia proceso de activacion<br>';}
	$shoy=fecha_hoy();
	$sSQL='SELECT ofer08estadocampus FROM ofer08oferta WHERE ofer08id='.$_REQUEST['ofer08id'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['ofer08estadocampus']!=-1){
			$sError='El curso no se encuentra Sin Acceso';
			}
		}else{
		$sError='No se ha encontrado el registro {ref '.$_REQUEST['ofer08id'].'}';
		}
	if ($sError==''){
		if ($bDebug){$sDebug=$sDebug.fecha_microtiempo().' Solicita el cambio de estado de '.$_REQUEST['ofer08estadocampus'].' a '.$iEstadoBase.'<br>';}
		//$idOferta, $idOrigen, $idDestino, $sNota, $objDB
		$sError=f1730_CambiaEstado($_REQUEST['ofer08id'], $_REQUEST['ofer08estadocampus'], $iEstadoBase, '', $objDB);
		if ($sError==''){
			$_REQUEST['ofer08estadocampus']=$iEstadoBase;
			}
		}
	}
//Terminada la revision contents
//Retorna a alistamiento por parte de campus (42) sin actualizar
if (($_REQUEST['paso']==37)||($_REQUEST['paso']==42)){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	$shoy=fecha_hoy();
	$sSQL='SELECT ofer08estadocampus FROM ofer08oferta WHERE ofer08id='.$_REQUEST['ofer08id'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['ofer08estadocampus']!=4){
			$sError='El curso no se encuentra Por Actualizar Contents';
			}
		}else{
		$sError='No se ha entontrado el registro {ref '.$_REQUEST['ofer08id'].'}';
		}
	if ($sError==''){
		$sError=f1730_CambiaEstado($_REQUEST['ofer08id'], $_REQUEST['ofer08estadocampus'], 2, '', $objDB);
		if ($sError==''){
			$_REQUEST['ofer08estadocampus']=2;
			}
		}
	if ($sError==''){
		if ($_REQUEST['paso']==37){
			$sadd='ofer08origen="'.$_REQUEST['ofer08origen'].'", ofer08restaurado="S"';
			$sSQL='UPDATE ofer08oferta SET '.$sadd.' WHERE ofer08id='.$_REQUEST['ofer08id'].'';
			$result=$objDB->ejecutasql($sSQL);
			}
		}
	}
//Solicitar Restauracion
if ($_REQUEST['paso']==38){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	$sSQL='SELECT ofer08idper_aca, ofer08estadocampus FROM ofer08oferta WHERE ofer08id='.$_REQUEST['ofer08id'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$iEstadoPrev=1;
		if ($fila['ofer08idper_aca']<222){$iEstadoPrev=15;}
		if ($fila['ofer08estadocampus']!=$iEstadoPrev){
			$sError='El curso no se encuentra para migrar';
			}
		}else{
		$sError='No se ha entontrado el registro {ref '.$_REQUEST['ofer08id'].'}';
		}
	if ($sError==''){
		$sError=f1730_CambiaEstado($_REQUEST['ofer08id'], $_REQUEST['ofer08estadocampus'], 19, '', $objDB);
		if ($sError==''){
			$sHoy=fecha_hoy();
			$sSQL='UPDATE ofer08oferta SET ofer08fechasolicrestaurar="'.$sHoy.'" WHERE ofer08id='.$_REQUEST['ofer08id'].'';
			$result=$objDB->ejecutasql($sSQL);
			$_REQUEST['ofer08estadocampus']=19;
			$_REQUEST['ofer08fechasolicrestaurar']=$sHoy;
			}
		}
	}
//Solicitud devuelta
if ($_REQUEST['paso']==39){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	$sSQL='SELECT ofer08estadocampus FROM ofer08oferta WHERE ofer08id='.$_REQUEST['ofer08id'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		if ($fila['ofer08estadocampus']!=19){
			$sError='El curso no se encuentra para restaurar';
			}
		}else{
		$sError='No se ha entontrado el registro {ref '.$_REQUEST['ofer08id'].'}';
		}
	if ($sError==''){
		$sError=f1730_CambiaEstado($_REQUEST['ofer08id'], $_REQUEST['ofer08estadocampus'], 15, '', $objDB);
		if ($sError==''){
			$sSQL='UPDATE ofer08oferta SET ofer08fechasolicrestaurar="00/00/0000" WHERE ofer08id='.$_REQUEST['ofer08id'].'';
			$result=$objDB->ejecutasql($sSQL);
			$_REQUEST['ofer08estadocampus']=15;
			$_REQUEST['ofer08fechasolicrestaurar']='00/00/0000';
			}
		}
	}
//Restaurado - Completo
if ($_REQUEST['paso']==40){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	$sSQL='SELECT ofer08idper_aca, ofer08estadocampus, ofer08idnav, ofer08idcursonav FROM ofer08oferta WHERE ofer08id='.$_REQUEST['ofer08id'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		//Verificar que tenga asignado el nav y el id de moodle.
		if ($fila['ofer08idcursonav']==0){$sError=$ERR['ofer08idcursonav'];}
		if ($fila['ofer08idnav']==0){$sError=$ERR['ofer08idnav'];}
		if ($fila['ofer08estadocampus']!=19){
			$sError='El curso no se encuentra para restaurar';
			}
		}else{
		$sError='No se ha entontrado el registro {ref '.$_REQUEST['ofer08id'].'}';
		}
	if ($sError==''){
		$sError=f1730_CambiaEstado($_REQUEST['ofer08id'], $_REQUEST['ofer08estadocampus'], 20, '', $objDB);
		if ($sError==''){
			$sHoy=fecha_hoy();
			$sSQL='UPDATE ofer08oferta SET ofer08idusariorestaura='.$_SESSION['unad_id_tercero'].', ofer08fecharestaurado="'.$sHoy.'", ofer08migrados="'.$_REQUEST['ofer08migrados'].'" WHERE ofer08id='.$_REQUEST['ofer08id'].'';
			$result=$objDB->ejecutasql($sSQL);
			$_REQUEST['ofer08estadocampus']=20;
			$_REQUEST['ofer08idusariorestaura']=$_SESSION['unad_id_tercero'];
			$_REQUEST['ofer08fecharestaurado']=$sHoy;
			$sError='Se ha registrado la restauraci&oacute;n del curso';
			$iTipoError=1;
			//Hacer la bitacora.
			$ofer08idcurso_nombre=OAI_NombreCurso($_REQUEST['ofer08idcurso'], $objDB);
			require $APP->rutacomun.'libs/cls1504.php';
			$objBitacora=new clsT1504();
			$objBitacora->nuevo(1702);
			$objBitacora->bita04detalle='Restauracion de curso: '.$_REQUEST['ofer08idcurso'].' '.$ofer08idcurso_nombre.' 
'.$_REQUEST['ofer08migrados'].'';
			list($sErrorB, $iTipoErrorB, $idAccion, $sDebugB)=$objBitacora->guardar($objDB, $bDebug);
			if ($iTipoErrorB==0){
				$sError=$sError.'<br>ERROR AL GENERAR LA BITACORA: '.$sErrorB;
				}else{
				$sError=$sError.'<br>Se ha generado la bitacora '.$objBitacora->bita04consec;
				}
			}
		}
	}

//Retornar a alistamiento (41)
if ($_REQUEST['paso']==41){
	$_REQUEST['paso']=2;
	}
//Devuelde de campus a acreditación
if ($_REQUEST['paso']==43){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	$iEstado=0;
	if (trim($_REQUEST['ofer30anotacion'])==''){
		$sError='Este proceso requiere que se haya ingresado una anotaci&oacute;n';
		}
	if ($sError==''){
		$sSQL='SELECT ofer08idper_aca, ofer08estadocampus FROM ofer08oferta WHERE ofer08id='.$_REQUEST['ofer08id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$iEstado=$fila['ofer08estadocampus'];
			if ($fila['ofer08idper_aca']<222){
				switch($iEstado){
					case 15:
					case 19:
					case 20:
					break;
					default:
					$sError='El curso no se encuentra en alistamiento';
					}
				}else{
				if ($fila['ofer08estadocampus']!=10){
					$sError='El curso no se encuentra en alistamiento';
					}
				}
			}else{
			$sError='No se ha entontrado el registro {ref '.$_REQUEST['ofer08id'].'}';
			}
		}
	if ($sError==''){
		$sError=f1730_CambiaEstado($_REQUEST['ofer08id'], $iEstado, 8, $_REQUEST['ofer30anotacion'], $objDB);
		if ($sError==''){
			$_REQUEST['ofer08estadocampus']=8;
			$sError='<b>El curso ha sido devuelto a Acreditaci&oacute;n</b>';
			}
		}
	}

//Subida masiva
$sErrDatos='';
if (($_REQUEST['paso']==50)||($_REQUEST['paso']==52)){
	$_REQUEST['paso']=$_REQUEST['paso']-30;
	if ($sError==''){
		}
	}
$sInfoDegub='';
$sInfoDegub2='';
//Carga masiva de resposnables.
if ($_REQUEST['paso']==60){
	$_REQUEST['paso']=2;
	if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){
		$sError=$ERR['3'];
		}
	if ($sError==''){
		$bPasa=false;
		if($_FILES['archivodatos']['type']=='text/plain'){$bPasa=true;}
		if($_FILES['archivodatos']['type']=='application/xls'){$bPasa=true;}
		if (!$bPasa){
			$sError='Tipo de archivo no permitido {'.$_FILES['archivodatos']['type'].'}';
			}
		}
	$totallin=0;
	if ($sError==''){
		$objplano=new clsplanos($_FILES['archivodatos']['tmp_name']);
		$objplano->Leer();
		$totallin=$objplano->iLineas;
		if ($totallin<5){$sError='El archivo no contine datos';}
		}
	if ($sError==''){
		$sHoy=fecha_hoy();
		$idPeraca=$_REQUEST['ofer08idper_aca'];
		$iActualizados=0;
		for ($k=5;$k<=$totallin;$k++){
			$base=trim($objplano->aCuerpo[$k]);
			$sData=explode($_REQUEST['csv_separa'],$base);
			if ($sData[0]!=''){
				$idCurso=$sData[0];
				$id11=0;
				$sDoc=trim($sData[2]);
				//echo $sData[0].' - '.$sData[2].'<br>';
				//Encontrar el tercero.
				if ($sDoc!=''){
					list($respuesta,$id11)=tabla_terceros_info('CC', $sDoc, $objDB);
					}
				if ($id11!=0){
					//Ya tengo el curso y el tercero, comprobar que sea.
					$sSQL='SELECT ofer08id, ofer08idresponsablepti FROM ofer08oferta WHERE ofer08idper_aca='.$idPeraca.' AND ofer08idcurso='.$idCurso.' AND ofer08cead=0';
					$tabla=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla)>0){
						$fila=$objDB->sf($tabla);
						if ($fila['ofer08idresponsablepti']!=$id11){
							$id08=$fila['ofer08id'];
							$sSQL='UPDATE ofer08oferta SET ofer08idresponsablepti='.$id11.' WHERE ofer08id='.$id08;
							$result=$objDB->ejecutasql($sSQL);
							$iActualizados++;
							}
						}else{
						if ($sInfoDegub!=''){$sInfoDegub=$sInfoDegub.'<br>';}
						$sInfoDegub=$sInfoDegub.'Se se ha encontrado oferta para el curso '.$idCurso.'';
						}
					}
				}
			}
		$sInfoDegub='Periodo: '.$idPeraca.', Lineas procesadas: '.($totallin-4).', Actualizadas: '.$iActualizados.'<br>'.$sInfoDegub;
		//if ($sErrDatos!=''){$sErrDatos='<br>'.$sErrDatos;}
		//$sErrDatos='<b>Resultado de la importaci&oacute;n</b>: Lineas Totales: <b>'.$totallin.'</b> Importados: <b>'.$iImp.'</b>, Actualizados: <b>'.$iAct.'</b>'.$sErrDatos;
		}
	}
//Carga masiva de navs
if ($_REQUEST['paso']==61){
	$_REQUEST['paso']=2;
	if (!seg_revisa_permiso($iCodModulo, 3, $objDB)){
		$sError=$ERR['3'];
		}
	if ($sError==''){
		$bPasa=false;
		if($_FILES['archivodatosnav']['type']=='text/plain'){$bPasa=true;}
		if($_FILES['archivodatosnav']['type']=='application/xls'){$bPasa=true;}
		if (!$bPasa){
			$sError='Tipo de archivo no permitido {'.$_FILES['archivodatosnav']['type'].'}';
			}
		}
	$totallin=0;
	if ($sError==''){
		$objplano=new clsplanos($_FILES['archivodatosnav']['tmp_name']);
		$objplano->Leer();
		$totallin=$objplano->iLineas;
		if ($totallin<5){$sError='El archivo no contine datos';}
		}
	if ($sError==''){
		$sHoy=fecha_hoy();
		$idPeraca=$_REQUEST['ofer08idper_aca'];
		$iActualizados=0;
		for ($k=5;$k<=$totallin;$k++){
			$base=trim($objplano->aCuerpo[$k]);
			$sData=explode($_REQUEST['csv_separa'],$base);
			if ($sData[0]!=''){
				$idCurso=$sData[0];
				$idNav=0;
				$idNav=numeros_validar(trim($sData[2]));
				if ($idNav==''){$idNav=0;}
				//echo $sData[0].' - '.$sData[2].'<br>';
				//Encontrar el tercero.
				if ($idNav!=0){
					//Ya tengo el curso y el tercero, comprobar que sea.
					$sSQL='SELECT ofer08id, ofer08idnav FROM ofer08oferta WHERE ofer08idper_aca='.$idPeraca.' AND ofer08idcurso='.$idCurso.' AND ofer08cead=0';
					$tabla=$objDB->ejecutasql($sSQL);
					if ($objDB->nf($tabla)>0){
						$fila=$objDB->sf($tabla);
						if ($fila['ofer08idnav']!=$idNav){
							$id08=$fila['ofer08id'];
							$sSQL='UPDATE ofer08oferta SET ofer08idnav='.$idNav.', ofer08idcursonav=0 WHERE ofer08id='.$id08;
							$result=$objDB->ejecutasql($sSQL);
							$iActualizados++;
							}
						}else{
						if ($sInfoDegub2!=''){$sInfoDegub2=$sInfoDegub2.'<br>';}
						$sInfoDegub2=$sInfoDegub2.'Se se ha encontrado oferta para el curso '.$idCurso.'';
						}
					}
				}
			}
		$sInfoDegub2='Periodo: '.$idPeraca.', Lineas procesadas: '.($totallin-4).', Actualizadas: '.$iActualizados.'<br>'.$sInfoDegub2;
		}
	}

//Actualizar la matricula... ha fallado...
if ($_REQUEST['paso']==71){
	$_REQUEST['paso']=2;
	$bMueveScroll=true;
	list($sErrorMat, $iDirectores, $iOtros, $sDebugMat)=f1711_GestionarMatriculaCurso($_REQUEST['ofer08id'], $objDB);
	$sDebug=$sDebug.$sDebugMat;
	list($sError, $sDebugRep)=f1730_CargarCursoARepositorio($_REQUEST['ofer08idper_aca'], $_REQUEST['ofer08idcurso'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugRep;
	$sError='Se actualizo los actores para el curso.';
	$iTipoError=1;
	}

// -- Devuelve un html de las acciones que puede hacer el usuario de una agenda.
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos('n');
$objTercero=new clsHtmlTercero();
$objCombos->nuevo('ofer08grupoidforma', $_REQUEST['ofer08grupoidforma'], false, '{'.$ETI['msg_seleccione'].'}');
$objCombos->addItem(0, 'Asignaci&oacute;n autom&aacute;tica');
$objCombos->addItem(1, 'Asignaci&oacute;n por tutor');
$objCombos->addItem(9, 'Sin grupos');
$html_ofer08grupoidforma=$objCombos->html('', $objDB);
if ($_REQUEST['paso']==0){
	$html_ofer08idper_aca=html_combo('ofer08idper_aca', 'exte02id', 'exte02nombre', 'exte02per_aca', 'exte02id='.$_SESSION['oai_per_aca'], 'exte02nombre', $_REQUEST['ofer08idper_aca'], $objDB, 'RevisaLlave()', false, '{'.$ETI['msg_seleccione'].'}', '');
	$html_ofer08idcurso=html_combo_ofer08idcurso($objDB, $_REQUEST['ofer08idcurso'], $_REQUEST['ofer08idescuela'], $iNumEscuelas);
	$html_ofer08cead=html_combo('ofer08cead', 'unad24id', 'unad24nombre', 'unad24sede', '', 'unad24nombre', $_REQUEST['ofer08cead'], $objDB, 'RevisaLlave()', true, 'Campus', '0');
	$bVacio=false;
	if ($iNumEscuelas==-1){$bVacio=true;}
	$html_ofer08idescuela=html_combo('ofer08idescuela', 'exte01id', 'exte01nombre', 'exte01escuela', $sCondiEscuelas, 'exte01nombre', $_REQUEST['ofer08idescuela'], $objDB, 'carga_combo_ofer08idcurso();', $bVacio, '{'.$ETI['msg_todos'].'}', '-1');
	}else{
	list($ofer08idper_aca_nombre, $sErrorDet)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id',$_REQUEST['ofer08idper_aca'],'{Sin dato}', $objDB);
	$html_ofer08idper_aca=html_oculto('ofer08idper_aca', $_REQUEST['ofer08idper_aca'], $ofer08idper_aca_nombre);
	$ofer08idcurso_nombre=OAI_NombreCurso($_REQUEST['ofer08idcurso'], $objDB);
	$html_ofer08idcurso=html_oculto('ofer08idcurso', $_REQUEST['ofer08idcurso'], $_REQUEST['ofer08idcurso'].' - '.$ofer08idcurso_nombre);
	$ofer08cead_nombre='CAMPUS';
	$html_ofer08cead=html_oculto('ofer08cead', $_REQUEST['ofer08cead'], $ofer08cead_nombre);
	list($ofer08idescuela_nombre, $sErrorDet)=tabla_campoxid('exte01escuela','exte01nombre','exte01id',$_REQUEST['ofer08idescuela'],'{Sin dato}', $objDB);
	$html_ofer08idescuela=html_oculto('ofer08idescuela', $_REQUEST['ofer08idescuela'], $ofer08idescuela_nombre);
	}
$ofer08incluyelaboratorio_nombre=$ETI['no'];
if ($_REQUEST['ofer08incluyelaboratorio']=='S'){$ofer08incluyelaboratorio_nombre=$ETI['si'];}
$html_ofer08incluyelaboratorio=html_oculto('ofer08incluyelaboratorio', $_REQUEST['ofer08incluyelaboratorio'], $ofer08incluyelaboratorio_nombre);
$ofer08incluyesalida_nombre=$ETI['no'];
if ($_REQUEST['ofer08incluyesalida']=='S'){$ofer08incluyesalida_nombre=$ETI['si'];}
$html_ofer08incluyesalida=html_oculto('ofer08incluyesalida', $_REQUEST['ofer08incluyesalida'], $ofer08incluyesalida_nombre);
$et_ofer08obligaacreditar=$ETI['msg_ninguno'];
$ofer11idtercero_rs='';
if ($_REQUEST['paso']>0){
	$sSQL='SELECT ofer37nombre FROM ofer37proceso WHERE ofer37id="'.$_REQUEST['ofer08obligaacreditar'].'"';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$et_ofer08obligaacreditar=$fila['ofer37nombre'];
		}
	list($_REQUEST['ofer11idtercero'],$_REQUEST['ofer11idtercero_td'],$_REQUEST['ofer11idtercero_doc'],$ofer11idtercero_rs)=tabla_terceros_traer($_REQUEST['ofer11idtercero'],$_REQUEST['ofer11idtercero_td'],$_REQUEST['ofer11idtercero_doc'], $objDB);
	}
$html_ofer08obligaacreditar=html_oculto('ofer08obligaacreditar', $_REQUEST['ofer08obligaacreditar'], $et_ofer08obligaacreditar);
$html_ofer08metodomatricula=html_combo('ofer08metodomatricula', 'unad61id', 'unad61nombre', 'unad61origenmatricula', 'unad61id IN (0,1,3)', 'unad61id', $_REQUEST['ofer08metodomatricula'], $objDB, '', false, '{'.$ETI['msg_seleccione'].'}', '');
	$html_ofer08idcohorte=f1707_NombreCohorte($_REQUEST['ofer08idcohorte'], $_REQUEST['ofer08obligaacreditar'], $_REQUEST['ofer08estadocampus'], $objDB);
list($ofer08idusariorestaura_rs, $_REQUEST['ofer08idusariorestaura'], $_REQUEST['ofer08idusariorestaura_td'], $_REQUEST['ofer08idusariorestaura_doc'])=html_tercero($_REQUEST['ofer08idusariorestaura_td'], $_REQUEST['ofer08idusariorestaura_doc'], $_REQUEST['ofer08idusariorestaura'], 0, $objDB);
list($ofer08usuarioconfirmaacceso_rs, $_REQUEST['ofer08usuarioconfirmaacceso'], $_REQUEST['ofer08usuarioconfirmaacceso_td'], $_REQUEST['ofer08usuarioconfirmaacceso_doc'])=html_tercero($_REQUEST['ofer08usuarioconfirmaacceso_td'], $_REQUEST['ofer08usuarioconfirmaacceso_doc'], $_REQUEST['ofer08usuarioconfirmaacceso'], 0, $objDB);
list($ofer08idaprueba_rs, $_REQUEST['ofer08idaprueba'], $_REQUEST['ofer08idaprueba_td'], $_REQUEST['ofer08idaprueba_doc'])=html_tercero($_REQUEST['ofer08idaprueba_td'], $_REQUEST['ofer08idaprueba_doc'], $_REQUEST['ofer08idaprueba'], 0, $objDB);
list($ofer08idacredita_rs, $_REQUEST['ofer08idacredita'], $_REQUEST['ofer08idacredita_td'], $_REQUEST['ofer08idacredita_doc'])=html_tercero($_REQUEST['ofer08idacredita_td'], $_REQUEST['ofer08idacredita_doc'], $_REQUEST['ofer08idacredita'], 0, $objDB);
list($ofer08estadooferta_nombre, $sErrorDet)=tabla_campoxid('ofer07estadooferta','ofer07nombre','ofer07id',$_REQUEST['ofer08estadooferta'],'{Sin dato}', $objDB);
list($ofer08tipostandard_nombre, $sErrorDet)=tabla_campoxid('unad42tipostandard','unad42nombre','unad42id',$_REQUEST['ofer08tipostandard'],'-', $objDB);
$html_ofer08tipostandard=html_combo('ofer08tipostandard', 'unad42id', 'unad42nombre', 'unad42tipostandard', '', 'unad42nombre', $_REQUEST['ofer08tipostandard'], $objDB, '', true, '{'.$ETI['msg_seleccione'].'}', '');
$ofer08idagenda_nombre='{'.$ETI['msg_sinasignar'].'}';
list($ofer08copiaidusuario_rs, $_REQUEST['ofer08copiaidusuario'], $_REQUEST['ofer08copiaidusuario_td'], $_REQUEST['ofer08copiaidusuario_doc'])=html_tercero($_REQUEST['ofer08copiaidusuario_td'], $_REQUEST['ofer08copiaidusuario_doc'], $_REQUEST['ofer08copiaidusuario'], 0, $objDB);
list($ofer08idresponsablepti_rs, $_REQUEST['ofer08idresponsablepti'], $_REQUEST['ofer08idresponsablepti_td'], $_REQUEST['ofer08idresponsablepti_doc'])=html_tercero($_REQUEST['ofer08idresponsablepti_td'], $_REQUEST['ofer08idresponsablepti_doc'], $_REQUEST['ofer08idresponsablepti'], 0, $objDB);
list($ofer08tablacidusuario_rs, $_REQUEST['ofer08tablacidusuario'], $_REQUEST['ofer08tablacidusuario_td'], $_REQUEST['ofer08tablacidusuario_doc'])=html_tercero($_REQUEST['ofer08tablacidusuario_td'], $_REQUEST['ofer08tablacidusuario_doc'], $_REQUEST['ofer08tablacidusuario'], 0, $objDB);
$ofer08idagenda_cerrada=true;
$ofer08idagenda_idcurso=0;
$ofer08idagenda_consec=0;
if($_REQUEST['paso']>0){
	if((int)$_REQUEST['ofer08idagenda']>0){
		$sSQL='SELECT ofer05consec, ofer05nombre, ofer05cerrada, ofer05idcurso FROM ofer05agenda WHERE ofer05id='.$_REQUEST['ofer08idagenda'];
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$ofer08idagenda_nombre=$fila['ofer05nombre'];
			if ($fila['ofer05cerrada']=='N'){
				$ofer08idagenda_cerrada=false;
				if ($fila['ofer05idcurso']==0){
					$_REQUEST['ofer08idagenda']=0;
					}
				}
			$ofer08idagenda_idcurso=$fila['ofer05idcurso'];
			$ofer08idagenda_consec=$fila['ofer05consec'];
			}else{
			if ($_REQUEST['ofer08idper_aca']>289){$ofer08idagenda_cerrada=false;}
			}
		}else{
		if ($_REQUEST['ofer08idper_aca']>289){$ofer08idagenda_cerrada=false;}
		}
	//Ver si tiene el permiso de forzar cambio de agendas
	if ($ofer08idagenda_cerrada){
		if (seg_revisa_permiso($iCodModulo, 1705, $objDB)){
			$ofer08idagenda_cerrada=false;
			}
		}
	}
$ofer08estadocampus_nombre='';
$ofer08estadodiseno_nombre='';
$html_AvanceMatricula='';
if($_REQUEST['paso']>0){
	$sCNombre='ofer15nombre';
	if ($_SESSION['oai_per_aca']<222){
		$sCNombre='ofer15prevnombre';
		}
	list($ofer08estadocampus_nombre, $sErrorDet)=tabla_campoxid('ofer15estadocampus',$sCNombre,'ofer15id',$_REQUEST['ofer08estadocampus'],'{Sin dato}', $objDB);
	if ($_REQUEST['ofer08estadodiseno']==1){$ofer08estadodiseno_nombre=' - '.$ETI['msg_endiseno'].'';}
	list($iTotalAntiguos, $iTotalNuevos, $sDebugT)=f2207_TotalMatricula($_REQUEST['ofer08idper_aca'], '', '', $_REQUEST['ofer08idcurso'], $objDB);
	$sDet='';
	if ($iTotalAntiguos!=0){$sDet='<b>'.formato_numero($iTotalAntiguos).' Antiguos</b> ';}
	if ($iTotalNuevos!=0){$sDet=$sDet.'<b>'.formato_numero($iTotalNuevos).' Nuevos</b>.';}
	if ($sDet==''){$sDet='<b>Sin estudiantes</b>.';}
	$html_AvanceMatricula='Avance de matricula: '.$sDet;
	}
$html_ofer08idnav=html_combo('ofer08idnav', 'unad39id', 'CONCAT(CASE unad39activo WHEN "S" THEN "" ELSE "[INACTIVO] " END, unad39nombre)', 'unad39nav', '', 'unad39activo DESC, unad39nombre', $_REQUEST['ofer08idnav'], $objDB, '', true, '{'.$ETI['msg_ninguno'].'}', '0');
$ofer08idevalacredita_nombre='&nbsp;';
if ($_REQUEST['paso']>0){
	$ofer08idevalacredita_nombre='{'.$ETI['msg_ninguna'].'}';
	if ((int)$_REQUEST['ofer08idevalacredita']!=0){
		$sSQL='SELECT ofer24titulo, ofer26fecha FROM ofer26evaluacioncurso, ofer24evaluacion WHERE ofer26idevaluacion=ofer24id AND ofer26id='.$_REQUEST['ofer08idevalacredita'];
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$fila=$objDB->sf($tabla);
			$ofer08idevalacredita_nombre=$fila['ofer26fecha'].' - '.$fila['ofer24titulo'];
			}
		}
	}
$html_ofer08idevalacredita=html_oculto('ofer08idevalacredita', $_REQUEST['ofer08idevalacredita'], $ofer08idevalacredita_nombre);
if ((int)$_REQUEST['paso']>0){
	if ((int)$_REQUEST['ofer11id']==0){
		$html_ofer11idrol=html_combo_ofer11idrol($objDB, $_REQUEST['ofer11idrol']);
		}else{
		list($ofer11idrol_nombre, $sErrorDet)=tabla_campoxid('ofer10rol','ofer10nombre','ofer10id',$_REQUEST['ofer11idrol'],'{'.$ETI['msg_sindato'].'}', $objDB);
		$html_ofer11idrol=html_oculto('ofer11idrol', $_REQUEST['ofer11idrol'], $ofer11idrol_nombre);
		}
	$ofer11idtercero_rs='';
	list($_REQUEST['ofer11idtercero'],$_REQUEST['ofer11idtercero_td'],$_REQUEST['ofer11idtercero_doc'],$ofer11idtercero_rs)=tabla_terceros_traer($_REQUEST['ofer11idtercero'],$_REQUEST['ofer11idtercero_td'],$_REQUEST['ofer11idtercero_doc'], $objDB);
	list($ofer12idtercero_rs, $_REQUEST['ofer12idtercero'], $_REQUEST['ofer12idtercero_td'], $_REQUEST['ofer12idtercero_doc'])=html_tercero($_REQUEST['ofer12idtercero_td'], $_REQUEST['ofer12idtercero_doc'], $_REQUEST['ofer12idtercero'], 0, $objDB);
	list($ofer13idusuario_rs, $_REQUEST['ofer13idusuario'], $_REQUEST['ofer13idusuario_td'], $_REQUEST['ofer13idusuario_doc'])=html_tercero($_REQUEST['ofer13idusuario_td'], $_REQUEST['ofer13idusuario_doc'], $_REQUEST['ofer13idusuario'], 0, $objDB);
	}
$ofer26id=0;
//Determinar si la agenda del curso es cerrada la pueden cambiar si no... no.
	//Se da el caso de Agendas Que vienen marcadas como cerradas por el permiso 1705
if ($ofer08idagenda_cerrada){
	$html_ofer08idagenda=html_oculto('ofer08idagenda', $_REQUEST['ofer08idagenda'], $ofer08idagenda_nombre);
	}else{
	$html_ofer08idagenda=html_combo_ofer08idagendaV2($_REQUEST['ofer08idagenda'], $_REQUEST['ofer08idcurso'], $_REQUEST['ofer08idper_aca'], $objDB);
	}
//Alistar datos adicionales
$html_ActoresPrograma='';
if ($_REQUEST['paso']==0){
	}else{
	list($html_ActoresPrograma, $idDecano, $idSecretario, $idLider, $sDebugA)=f1707_ActoresPrograma($_REQUEST['ofer08id'], $objDB, 0, $bDebug);
	}
$id_rpt=0;

$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf1707()';
$objCombos->addItem(1, 'Pendientes de copia');
$objCombos->addItem(2, 'Tablas de calificaciones faltantes');
//$objCombos->addItem(3, 'Id Moodle en cero');
//$objCombos->addItem(4, 'Para migrar + Id Moodle en cero');
$objCombos->addItem(5, 'Para migrar + Id Moodle');
$html_blistar=$objCombos->html('', $objDB);

$html_bnav=html_combo('bnav', 'unad39id', 'CONCAT(CASE unad39activo WHEN "S" THEN "" ELSE "[INACTIVO] " END, unad39nombre)', 'unad39nav', '', 'unad39activo DESC, unad39nombre', $_REQUEST['bnav'], $objDB, 'paginarf1707()', true, '{'.$ETI['msg_todos'].'}|{'.$ETI['msg_ninguno'].'}', '|0');
$html_bproceso=html_combo('bproceso', 'ofer37id', 'ofer37nombre', 'ofer37proceso', '', 'ofer37orden', $_REQUEST['bproceso'], $objDB, 'paginarf1707()', true, '{'.$ETI['msg_todos'].'}', '');
$sIds='-99';
$idPeraca=$_SESSION['oai_per_aca'];
if ($idPeraca==''){$idPeraca=-99;}
$sSQL='SELECT ofer08idresponsablepti FROM ofer08oferta WHERE ofer08idper_aca='.$idPeraca.' AND ofer08idresponsablepti>0 GROUP BY ofer08idresponsablepti';
$tabla=$objDB->ejecutasql($sSQL);
while ($fila=$objDB->sf($tabla)){
	$sIds=$sIds.','.$fila['ofer08idresponsablepti'];
	}
$html_bresponsable=html_combo('bresponsable', 'unad11id', 'unad11razonsocial', 'unad11terceros', 'unad11id IN ('.$sIds.')', 'unad11razonsocial', $_REQUEST['bresponsable'], $objDB, 'paginarf1707()', true, '{'.$ETI['msg_todos'].'}|{Sin Asignar}', '|0');
$bVacio=false;
if ($sCondiEscuelas==''){$bVacio=true;}
$html_bescuela=html_combo('bescuela', 'exte01id', 'exte01nombre', 'exte01escuela', $sCondiEscuelas, 'exte01nombre', $_REQUEST['bescuela'], $objDB, 'paginarf1707();', $bVacio, '{'.$ETI['msg_todos'].'}', '');
$html_blistar2=html_combo('blistar2', 'ofer15id', 'ofer15nombre', 'ofer15estadocampus', '', 'ofer15id', $_REQUEST['blistar2'], $objDB, 'paginarf1707()', true, '{Sin filtro}', '');
$html_bestandar=html_combo('bestandar', 'unad42id', 'unad42nombre', 'unad42tipostandard', '', 'unad42nombre', $_REQUEST['bestandar'], $objDB, 'paginarf1707()', true, '{'.$ETI['msg_todos'].'}|{Sin Asignar}', '|0');
$objCombos->nuevo('bcohorte', $_REQUEST['bcohorte'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='paginarf1707()';
$sSQL='SELECT ofer52id AS id, CONCAT(ofer52consec, " - ", ofer52certfechaentregaescuela, " - ", ofer52certfechaentregavimep) AS nombre FROM ofer52cohortes WHERE ofer52idperaca='.$idPeraca.'';
$html_bcohorte=$objCombos->html($sSQL, $objDB);
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
if ($seg_6==1){}
if (true){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label130">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=1707;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}
	}
$bConBotonCopia=false;
$aParametros[0]='';//$_REQUEST['p1_1707'];
$aParametros[100]=$idTercero;
$aParametros[101]=$_REQUEST['paginaf1707'];
$aParametros[102]=$_REQUEST['lppf1707'];
$aParametros[103]=$_REQUEST['bescuela'];
$aParametros[104]=$_REQUEST['bnombre'];
$aParametros[105]=$_REQUEST['blistar'];
$aParametros[106]=$_REQUEST['bcodcurso'];
$aParametros[107]=$iNumEscuelas;
$aParametros[108]=$_REQUEST['blistar2'];
$aParametros[109]=$_REQUEST['bestandar'];
$aParametros[110]=$_REQUEST['bnav'];
$aParametros[111]=$_REQUEST['bproceso'];
$aParametros[112]=$_REQUEST['bresponsable'];
$aParametros[113]=$_REQUEST['bcohorte'];
list($sTabla1707, $sDebugTabla)=f1707_TablaDetalleV2Campus($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;

$sTabla148='';
$sTabla1711='';
$sTabla1712='';
$sTabla1713='';
$sTabla1718='';
$sTabla1726='';
$sTabla1730='';
$sTabla1738='';
$sTabla1707Historico='';
$sHTMLForo='';
if ($_REQUEST['paso']==2){
	$aParametros148[0]=$_REQUEST['ofer08idcurso'];
	$aParametros148[100]=$_REQUEST['unad48per_aca'];
	$aParametros148[101]=$_REQUEST['paginaf148'];
	$aParametros148[102]=$_REQUEST['lppf148'];
	$aParametros148[103]=1;
	//$aParametros148[103]=$_REQUEST['bnombre148'];
	//$aParametros148[104]=$_REQUEST['blistar148'];
	$sTabla148=f148_TablaDetalle($aParametros148, $objDB);
	if ($_REQUEST['ofer08copiaidusuario']==0){
		if ($_REQUEST['ofer08estadocampus']==10){$bConBotonCopia=true;}
		if ($_REQUEST['ofer08estadocampus']==12){$bConBotonCopia=true;}
		}
	$aParametros1711[0]=$_REQUEST['ofer08id'];
	$aParametros1711[101]=$_REQUEST['paginaf1711'];
	$aParametros1711[102]=$_REQUEST['lppf1711'];
	$aParametros1711[103]=$_REQUEST['ofer08idcurso'];
	$aParametros1711[104]=1;
	$sTabla1711=f1711_TablaDetalle($aParametros1711, $objDB);

	$aParametros1712[0]=$_REQUEST['ofer08id'];
	$aParametros1712[101]=$_REQUEST['paginaf1712'];
	$aParametros1712[102]=$_REQUEST['lppf1712'];
	$sTabla1712=f1712_TablaDetalle($aParametros1712, $objDB);

	$aParametros1713[0]=$_REQUEST['ofer08id'];
	$aParametros1713[101]=$_REQUEST['paginaf1713'];
	$aParametros1713[102]=$_REQUEST['lppf1713'];
	$sTabla1713=f1713_TablaDetalle($aParametros1713, $objDB);

	$aParametros[0]='';//$_REQUEST['p1_1718'];
	$aParametros[101]=$_REQUEST['paginaf1718'];
	$aParametros[102]=$_REQUEST['lppf1718'];
	$aParametros[103]=$_REQUEST['ofer08idper_aca'];
	$aParametros[104]=$_REQUEST['ofer08idcurso'];
	$sTabla1718=f1718_TablaDetalle($aParametros, $objDB);

	$aParametros1730[0]=$_REQUEST['ofer08id'];
	$aParametros1730[101]=$_REQUEST['paginaf1730'];
	$aParametros1730[102]=$_REQUEST['lppf1730'];
	//$aParametros1730[103]=$_REQUEST['bnombre1730'];
	//$aParametros1730[104]=$_REQUEST['blistar1730'];
	$sTabla1730=f1730_TablaDetalle($aParametros1730, $objDB);

	//Matricula del curso
	$aParametros1738[0]=$_REQUEST['ofer08id'];
	$aParametros1738[101]=$_REQUEST['paginaf1738'];
	$aParametros1738[102]=$_REQUEST['lppf1738'];
	//$aParametros1738[103]=$_REQUEST['bnombre1738'];
	//$aParametros1738[104]=$_REQUEST['blistar1738'];
	$sTabla1738=f1738_TablaDetalle($aParametros1738, $objDB);
	$objForo=new clsForo(1708, $_REQUEST['ofer08id'], true);
	list($sHTMLForo)=$objForo->html($idTercero, $objDB);

	$aParametros[100]=$idTercero;
	$aParametros[101]=1;
	$aParametros[102]=20;
	$aParametros[103]=$_REQUEST['ofer08idcurso'];
	$aParametros[104]=$_REQUEST['ofer08idper_aca'];
	list($sTabla1707Historico, $sDebugTabla)=f1707_TablaDetalleV2Historico($aParametros, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_1715']);
echo $et_menu;
forma_mitad();
if (false){
?>
<link rel="stylesheet" href="../ulib/css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/css/principal.css" type="text/css"/>
<link rel="stylesheet" href="../ulib/unad_estilos2018.css" type="text/css"/>
<?php
	}
?>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/criticalPath.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>css/principal.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>unad_estilos2018.css" type="text/css"/>
<?php
?>
<script language="javascript">
<!--
function limpiapagina(){
	expandesector(98);
	window.document.frmedita.paso.value=-1;
	window.document.frmedita.submit();
	}
function enviaguardar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	var dpaso=window.document.frmedita.paso;
		dpaso.value=12;
	window.document.frmedita.submit();
	}
function cambiapagina(){
	expandesector(98);
	window.document.frmedita.submit();
	}
function cambiapaginaV2(){
	expandesector(98);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function expandepanel(codigo,estado,valor){
	var objdiv= document.getElementById('div_p'+codigo);
	var objban= document.getElementById('boculta'+codigo);
	var otroestado='none';
	if (estado=='none'){otroestado='block';}
	objdiv.style.display=estado;
	objban.value=valor;
	verboton('btrecoge'+codigo,estado);
	verboton('btexpande'+codigo,otroestado);
	}
function verboton(idboton,estado){
	var objbt=document.getElementById(idboton);
	objbt.style.display=estado;
	}
function expandesector(codigo){
	document.getElementById('div_sector1').style.display='none';
	document.getElementById('div_sector2').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	if (window.document.frmedita.paso.value!=0){
		var sEst='none';
		if (codigo==1){sEst='block';}
		document.getElementById('cmdGuardarf').style.display=sEst;
		}
	}
function ter_retorna(){
	var sRetorna=window.document.frmedita.div96v2.value;
	if (sRetorna!=''){
		var idcampo=window.document.frmedita.div96campo.value;
		var illave=window.document.frmedita.div96llave.value;
		var did=document.getElementById(idcampo);
		var dtd=document.getElementById(idcampo+'_td');
		var ddoc=document.getElementById(idcampo+'_doc');
		dtd.value=window.document.frmedita.div96v1.value;
		ddoc.value=sRetorna;
		did.value=window.document.frmedita.div96v3.value;
		ter_muestra(idcampo, illave);
		}
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function ter_muestra(idcampo, illave){
	var params=new Array();
	params[1]=document.getElementById(idcampo+'_doc').value;
	if (params[1]!=''){
		params[0]=document.getElementById(idcampo+'_td').value;
		params[2]=idcampo;
		params[3]='div_'+idcampo;
		if (illave==1){params[4]='RevisaLlave';}
		//if (illave==1){params[5]='FuncionCuandoNoEsta';}
		xajax_unad11_Mostrar_v2(params);
		}else{
		document.getElementById(idcampo).value=0;
		document.getElementById('div_'+idcampo).innerHTML='&nbsp;';
		//FuncionCuandoNoHayNada
		}
	}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_1707.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_1707.value;
		window.document.frmlista.nombrearchivo.value='Oferta';
		window.document.frmlista.submit();
	}
function imprimep(){
		window.document.frmimpp.submit();
	}
function verrpt(){
	window.document.frmimprime.submit();
	}
function eliminadato(){
	if (confirm("<?php echo $ETI['confirma_eliminar']; ?>?")){
		expandesector(98);
		window.document.frmedita.paso.value=13;
		window.document.frmedita.submit();
		}
	}
function RevisaLlave(){
	var datos= new Array();
	datos[1]=window.document.frmedita.ofer08idper_aca.value;
	datos[2]=window.document.frmedita.ofer08idcurso.value;
	datos[3]=window.document.frmedita.ofer08cead.value;
	datos[4]=1;
	if ((datos[1]!='')&&(datos[2]!='')&&(datos[3]!='')){
		xajax_f1707_ExisteDato(datos);
		}else{
		if (datos[2]!=''){
			xajax_f1707_InfoCurso(datos);
			}
		}
	}
function cargadato(llave1, llave2, llave3){
	window.document.frmedita.ofer08idper_aca.value=String(llave1);
	window.document.frmedita.ofer08idcurso.value=String(llave2);
	window.document.frmedita.ofer08cead.value=String(llave3);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf1707(llave1){
	window.document.frmedita.ofer08id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function cod_ofer08idcurso(){
	var dcod=window.document.frmedita.ofer08idcurso_cod.value.trim();
	window.document.frmedita.ofer08idcurso.value=0;
	if (dcod!=''){
		var params=new Array();
		params[0]=dcod;
		params[1]='ofer08idcurso';
		params[2]='div_ofer08idcurso';
		params[9]=window.document.frmedita.debug.value;
		xajax_f1707_Busqueda_ofer08idcurso(params);
		}else{
		document.getElementById('div_ofer08idcurso').innerHTML='';
		}
	}
function carga_combo_ofer08idcurso(){
	var params=new Array();
	params[0]=window.document.frmedita.ofer08idescuela.value;
	params[1]=window.document.frmedita.iNumEscuelas.value;
	xajax_Cargar_ofer08idcurso(params);
	}
function paginarf1707(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[100]=<?php echo $idTercero; ?>;
	params[101]=window.document.frmedita.paginaf1707.value;
	params[102]=window.document.frmedita.lppf1707.value;
	params[103]=window.document.frmedita.bescuela.value;
	params[104]=window.document.frmedita.bnombre.value;
	params[105]=window.document.frmedita.blistar.value;
	params[106]=window.document.frmedita.bcodcurso.value;
	params[107]=window.document.frmedita.iNumEscuelas.value;
	params[108]=window.document.frmedita.blistar2.value;
	params[109]=window.document.frmedita.bestandar.value;
	params[110]=window.document.frmedita.bnav.value;
	params[111]=window.document.frmedita.bproceso.value;
	params[112]=window.document.frmedita.bresponsable.value;
	params[113]=window.document.frmedita.bcohorte.value;
	xajax_f1707_HtmlTablaCampus(params);
	}
function revfoco(objeto){
	setTimeout(function(){objeto.focus();},10);
	}
function siguienteobjeto(){}
document.onkeydown=function(e){
	if (document.all){
		if (event.keyCode==13){event.keyCode=9;}
		}else{
		if (e.which==13){siguienteobjeto();}
		}
	}
function objinicial(){
	document.getElementById("ofer08idper_aca").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f1707_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function retornacontrol2(){
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='ofer08idcurso'){
		window.document.frmedita.ofer08idcurso_cod.value=sValor;
		cod_ofer08idcurso();
		}
	if (sCampo=='ofer08idusariorestaura'){
		ter_traerxid('ofer08idusariorestaura', sValor);
		}
	if (sCampo=='ofer08usuarioconfirmaacceso'){
		ter_traerxid('ofer08usuarioconfirmaacceso', sValor);
		}
	if (sCampo=='ofer08idaprueba'){
		ter_traerxid('ofer08idaprueba', sValor);
		}
	if (sCampo=='ofer08idacredita'){
		ter_traerxid('ofer08idacredita', sValor);
		}
	if (sCampo=='ofer08copiaidusuario'){
		ter_traerxid('ofer08copiaidusuario', sValor);
		}
	if (sCampo=='ofer08idresponsablepti'){
		ter_traerxid('ofer08idresponsablepti', sValor);
		}
	if (sCampo=='ofer08tablacidusuario'){
		ter_traerxid('ofer08tablacidusuario', sValor);
		}
	if (sCampo=='ofer12idtercero'){
		ter_traerxid('ofer12idtercero', sValor);
		}
	if (sCampo=='ofer13idusuario'){
		ter_traerxid('ofer13idusuario', sValor);
		}
	if (sCampo=='ofer13idatiende'){
		ter_traerxid('ofer13idatiende', sValor);
		}
	if (sCampo=='ofer30idactor'){
		ter_traerxid('ofer30idactor', sValor);
		}
	retornacontrol();
	}
function MensajeAlarma(sHTML){
	MensajeAlarmaV2(sHTML, 0);
	}
function mantener_sesion(){xajax_sesion_mantenerV4();}
setInterval ('xajax_sesion_abandona_V2();', 60000);
function AyudaLocal(sCampo){
	var divAyuda=document.getElementById('div_ayuda_'+sCampo);
	if (typeof divAyuda==='undefined'){
		}else{
		verboton('cmdAyuda_'+sCampo, 'none');
		var sMensaje='Lo que quiera decir.';
		//if (sCampo=='sNombreCampo'){sMensaje='Mensaje para otro campo.';}
		divAyuda.innerHTML=sMensaje;
		divAyuda.style.display='block';
		}
	}
function cierraDiv96(ref){
	var sRetorna=window.document.frmedita.div96v2.value;
	MensajeAlarmaV2('', 0);
	retornacontrol();
	}
function actualizaagenda(){
	var params=new Array();
	var valores=new Array();
	valores[1]=window.document.frmedita.ofer08idper_aca.value;
	valores[2]=window.document.frmedita.ofer08idcurso.value;
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf1718.value;
	params[102]=window.document.frmedita.lppf1718.value;
	params[103]=window.document.frmedita.ofer08idper_aca.value;
	params[104]=window.document.frmedita.ofer08idcurso.value;
	xajax_Agenda_Actualizar(valores, params);
	}
function actualizaAgendaHija(){
	if (window.document.frmedita.unad48id.value==''){
		window.alert('No se ha seleccionado una aula adicional');
		}else{
		var params=new Array();
		var valores=new Array();
		valores[1]=window.document.frmedita.ofer08idper_aca.value;
		valores[2]=window.document.frmedita.ofer08idcurso.value;
		valores[3]=window.document.frmedita.unad48consec.value;
		valores[4]=window.document.frmedita.unad48id.value;
		//params[0]=window.document.frmedita.p1_1718.value;
		params[101]=window.document.frmedita.paginaf1718.value;
		params[102]=window.document.frmedita.lppf1718.value;
		params[103]=window.document.frmedita.ofer08idper_aca.value;
		params[104]=window.document.frmedita.ofer08idcurso.value;
		xajax_Agenda_ActualizarHija(valores, params);
		}
	}
function ofer_ofertar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=21;
	window.document.frmedita.submit();
	}
function ofer_cancelar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=22;
	window.document.frmedita.submit();
	}
function ofer_ingresoactivado(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=36;
	window.document.frmedita.submit();
	}
function ofer_actualizado(){
	if (confirm('Confirma que el curso ha sido actualizado en ncontens?')){
		expandesector(98);
		window.document.frmedita.paso.value=37;
		window.document.frmedita.submit();
		}
	}
function ofer_solrest(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=38;
	window.document.frmedita.submit();
	}
function ofer_retornarest(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=39;
	window.document.frmedita.submit();
	}
function ofer_migrado(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=40;
	window.document.frmedita.submit();
	}
function ofer_noactualizado(){
	if (confirm('Confirma que el curso NO REQUIERE ACTUALIZACION en ncontens?')){
		window.document.frmedita.iscroll.value=window.pageYOffset;
		expandesector(98);
		window.document.frmedita.paso.value=42;
		window.document.frmedita.submit();
		}
	}
function ofer_devueltoacredita(){
	if (window.document.frmedita.ofer30anotacion.value==''){
		window.alert('Se requiere un motivo para devolver.');
		window.document.frmedita.ofer30anotacion.focus();
		}else{
		window.document.frmedita.iscroll.value=window.pageYOffset;
		expandesector(98);
		window.document.frmedita.paso.value=43;
		window.document.frmedita.submit();
		}
	}
function RevisaRol(){
	var datos= new Array();
	datos[0]=1;
	datos[1]=window.document.frmedita.ofer08idper_aca.value;
	datos[2]=window.document.frmedita.ofer08idescuela.value;
	datos[3]=window.document.frmedita.ofer08idcurso.value;
	datos[4]=window.document.frmedita.ofer11idrol.value;
	if ((datos[1]!='')&&(datos[2]!='')&&(datos[3]!='')&&(datos[4]!='')){
		xajax_bExisteRol(datos);
		}
	}
function paginarf1718(){
	var params=new Array();
	//params[0]=window.document.frmedita.p1_1718.value;
	params[101]=window.document.frmedita.paginaf1718.value;
	params[102]=window.document.frmedita.lppf1718.value;
	params[103]=window.document.frmedita.ofer08idper_aca.value;
	params[104]=window.document.frmedita.ofer08idcurso.value;
	xajax_f1718_HtmlTabla(params);
	}
function cambiaagenda(){
	var params=new Array();
	params[1]=window.document.frmedita.ofer08id.value;
	params[2]=window.document.frmedita.ofer08idagenda.value;
	if (params[2]==''){params[2]=0;}
	xajax_Agenda_Cambia(params);
	}
function descargaragenda(){
	window.document.frmdescarga.idperaca.value=window.document.frmedita.ofer08idper_aca.value;
	window.document.frmdescarga.idcurso.value=window.document.frmedita.ofer08idcurso.value;
	window.document.frmdescarga.idaula.value=1;
	window.document.frmdescarga.submit();
	}
function descargaragendaV2(idaula){
	window.document.frmdescarga.idperaca.value=window.document.frmedita.ofer08idper_aca.value;
	window.document.frmdescarga.idcurso.value=window.document.frmedita.ofer08idcurso.value;
	window.document.frmdescarga.idaula.value=idaula;
	window.document.frmdescarga.submit();
	}
function agendaDatateca(idaula){
	window.document.frmdtk.idperaca.value=window.document.frmedita.ofer08idper_aca.value;
	window.document.frmdtk.idcurso.value=window.document.frmedita.ofer08idcurso.value;
	window.document.frmdtk.idaula.value=idaula;
	window.document.frmdtk.submit();
	}
function redir(){
	window.document.frmredir.submit();
	}
function ofer_registrarcopia(){
	if (window.document.frmedita.ofer08copiaruta.value==''){
		window.alert('No ha ingresado la ruta en la que se genero la copia de seguridad');
		window.document.frmedita.ofer08copiaruta.focus();
		}else{
		if (confirm('Confirma que ha hecho la copia de seguridad para este curso?')){
			window.document.frmedita.iscroll.value=window.pageYOffset;
			expandesector(98);
			window.document.frmedita.paso.value=44;
			window.document.frmedita.submit();
			}
		}
	}
function ofer_registrartablac(){
	if (window.document.frmedita.ofer08tablacruta.value==''){
		window.alert('No ha ingresado observaciones para la tabla de calificaciones');
		window.document.frmedita.ofer08tablacruta.focus();
		}else{
		window.document.frmedita.iscroll.value=window.pageYOffset;
		expandesector(98);
		window.document.frmedita.paso.value=45;
		window.document.frmedita.submit();
		}
	}
function descargarresponsables(){
	window.document.frmplantillaresponsables.separa.value=window.document.frmedita.csv_separa.value;
	window.document.frmplantillaresponsables.submit();
	}
function ofer_cargaresponsables(){
	extensiones_permitidas = new Array(".csv", ".txt");
	var sError='';
	var archivo=window.document.frmedita.archivodatos.value;
	if (!archivo) {
		sError = "No has seleccionado ning\u00fan archivo";
		}else{
		//recupero la extensión de este nombre de archivo
		extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
		//compruebo si la extensión está entre las permitidas
		permitida = false;
		for (var i = 0; i < extensiones_permitidas.length; i++) {
			if (extensiones_permitidas[i] == extension) {
				permitida = true;
				break;
				}
			}
	if (!permitida) {
		sError = "Comprueba la extensi\u00f3n de los archivos a subir. \nS\u00f3lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
		}else{
		expandesector(98);
		window.document.frmedita.paso.value=60;
		window.document.frmedita.submit();
		return 1;
		}
	}
	//si estoy aqui es que no se ha podido submitir
	alert (sError);
	return 0;
	}
function descargarnavs(){
	window.document.frmplantillanavs.separa.value=window.document.frmedita.csv_separa.value;
	window.document.frmplantillanavs.submit();
	}
function ofer_cargarnavs(){
	extensiones_permitidas = new Array(".csv", ".txt");
	var sError='';
	var archivo=window.document.frmedita.archivodatosnav.value;
	if (!archivo) {
		sError = "No has seleccionado ning\u00fan archivo";
		}else{
		//recupero la extensión de este nombre de archivo
		extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
		//compruebo si la extensión está entre las permitidas
		permitida = false;
		for (var i = 0; i < extensiones_permitidas.length; i++) {
			if (extensiones_permitidas[i] == extension) {
				permitida = true;
				break;
				}
			}
	if (!permitida) {
		sError = "Comprueba la extensi\u00f3n de los archivos a subir. \nS\u00f3lo se pueden subir archivos con extensiones: " + extensiones_permitidas.join();
		}else{
		window.document.frmedita.iscroll.value=window.pageYOffset;
		expandesector(98);
		window.document.frmedita.paso.value=61;
		window.document.frmedita.submit();
		return 1;
		}
	}
	//si estoy aqui es que no se ha podido submitir
	alert (sError);
	return 0;
	}
function actualizarmatricula(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=71;
	window.document.frmedita.submit();
	}
// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" type="text/javascript" charset="UTF-8" src="<?php echo $APP->rutacomun; ?>jsi/jsforo.js?v=1"></script>
<script language="javascript" src="jsi/js148campus.js"></script>
<script language="javascript" src="jsi/js1711.js"></script>
<script language="javascript" src="jsi/js1712.js"></script>
<script language="javascript" src="jsi/js1713.js"></script>
<script language="javascript" src="jsi/js1730.js"></script>
<script language="javascript" src="jsi/js1738campus.js"></script>
<?php
	}
?>
<form id="frmplantillaresponsables" name="frmplantillaresponsables" action="t1715.php" method="post" target="_blank">
<input id="idperaca" name="idperaca" type="hidden" value="<?php echo $_REQUEST['ofer08idper_aca']; ?>" />
<input id="separa" name="separa" type="hidden" value="," />
</form>
<form id="frmplantillanavs" name="frmplantillanavs" action="t1716.php" method="post" target="_blank">
<input id="idperaca" name="idperaca" type="hidden" value="<?php echo $_REQUEST['ofer08idper_aca']; ?>" />
<input id="separa" name="separa" type="hidden" value="," />
</form>
<form id="frmredir" name="frmredir" action="redir.php" method="post" target="_blank">
<input id="r1" name="r1" type="hidden" value="<?php echo $_REQUEST['ofer08idper_aca']; ?>" />
<input id="r2" name="r2" type="hidden" value="<?php echo $_REQUEST['ofer08idnav']; ?>" />
<input id="r3" name="r3" type="hidden" value="<?php echo $_REQUEST['ofer08idcurso']; ?>" />
<input id="r4" name="r4" type="hidden" value="1" />
</form>
<form id="frmdtk" name="frmdtk" method="post" action="http://datateca.unad.edu.co/contenidos/agendas/agehtml.php" target="_blank">
<input id="idperaca" name="idperaca" type="hidden" value="0" />
<input id="idcurso" name="idcurso" type="hidden" value="0" />
<input id="idaula" name="idaula" type="hidden" value="1" />
</form>
<form id="frmdescarga" name="frmdescarga" method="post" action="agehtml.php" target="_blank">
<input id="idperaca" name="idperaca" type="hidden" value="0" />
<input id="idcurso" name="idcurso" type="hidden" value="0" />
<input id="idaula" name="idaula" type="hidden" value="1" />
</form>
</form>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off" enctype="multipart/form-data">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="iNumEscuelas" name="iNumEscuelas" type="hidden" value="<?php echo $iNumEscuelas; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<input id="ofer08estadodiseno" name="ofer08estadodiseno" type="hidden" value="<?php echo $_REQUEST['ofer08estadodiseno']; ?>"/>
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		//$bHayImprimir=true;
		//$sScript='imprimep()';
		//$sClaseBoton='btEnviarPDF'; //btUpPrint
		//if ($id_rpt!=0){$sScript='verrpt()';}
		}
	}
if ($bHayImprimir){
?>
<input id="cmdImprimir" name="cmdImprimir" type="button" class="<?php echo $sClaseBoton; ?>" onclick="<?php echo $sScript; ?>" title="<?php echo $ETI['bt_imprimir']; ?>" value="<?php echo $ETI['bt_imprimir']; ?>"/>
<?php
	}
?>
<input id="cmdLimpiar" name="cmdLimpiar" type="button" class="btUpLimpiar" onclick="limpiapagina();" title="<?php echo $ETI['bt_limpiar']; ?>" value="<?php echo $ETI['bt_limpiar']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
$bPasa=false;
if ($_REQUEST['ofer08idper_aca']<222){
	if ($_REQUEST['ofer08estadocampus']==10){$bPasa=true;}
	}else{
	switch($_REQUEST['ofer08estadocampus']){
		case 15:
		case 19:
		case 20:
		$bPasa=true;
		}
	}
if ($bPasa){
?>
<input id="cmdNada" name="cmdNada" type="button" class="btSupNada" />
<input id="cmdNegar" name="cmdNegar" type="button" class="btSupNegar" onclick="expandesector(2);" title="<?php echo $ETI['bt_Negar']; ?>" value="<?php echo $ETI['bt_Negar']; ?>"/>
<?php
		}
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1715'].'</h2>';
?>
</div>
</div>
<div class="areaform">
<div class="areatrabajo">
<?php
//Div para ocultar
$bconexpande=true;
if ($bconexpande){
?>
<div class="ir_derecha" style="width:62px;">
<input id="boculta1707" name="boculta1707" type="hidden" value="<?php echo $_REQUEST['boculta1707']; ?>" />
<label class="Label30">
<input id="btexpande1707" name="btexpande1707" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(1707,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1707']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1707" name="btrecoge1707" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(1707,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1707']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p1707" style="display:<?php if ($_REQUEST['boculta1707']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['ofer08idper_aca'];
?>
</label>
<label class="Label450">
<?php
echo $html_ofer08idper_aca;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['ofer08estadooferta'];
?>
</label>
<label class="Label220"><div id="div_ofer08estadooferta">
<?php
echo html_oculto('ofer08estadooferta', $_REQUEST['ofer08estadooferta'], $ofer08estadooferta_nombre);
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['ofer08id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('ofer08id', $_REQUEST['ofer08id']);
?>
</label>
<label class="Label90">
<?php
echo $ETI['ofer08obligaacreditar'];
?>
</label>
<label class="Label160">
<?php
echo $html_ofer08obligaacreditar;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['ofer08idescuela'];
?>
</label>
<label class="Label700">
<?php
echo $html_ofer08idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['ofer08idcurso'];
?>
</label>
<label class="Label700"><div id="div_ofer08idcurso">
<?php
echo $html_ofer08idcurso;
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['ofer08tipostandard'];
?>
</label>
<label class="Label160">
<?php
	echo '<b><font class="MarquesinaMedia">'.$ofer08tipostandard_nombre.'</font></b>';
?>
</label>
<label class="Label90">
<?php
echo $ETI['ofer08cead'];
?>
</label>
<label>
<?php
echo $html_ofer08cead;
?>
</label>
<label class="Label90">
<?php
echo $ETI['ofer08idcohorte'];
?>
</label>
<label>
<div id="div_ofer08idcohorte">
<?php
echo $html_ofer08idcohorte;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label200">
<?php
echo $ETI['ofer08fechaoferta'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto("ofer08fechaoferta", $_REQUEST['ofer08fechaoferta']);
?>
</label>
<label class="Label130">
</label>
<label class="Label160">
<?php
echo $ETI['ofer08fechacancela'];
?>
</label>
<label class="Label130">
<?php
$et_ofer08fechacancela='&nbsp;';
if ($_REQUEST['ofer08fechacancela']!='00/00/0000'){$et_ofer08fechacancela=$_REQUEST['ofer08fechacancela'];}
echo html_oculto("ofer08fechacancela", $_REQUEST['ofer08fechacancela'],$et_ofer08fechacancela);
?>
</label>
<label class="Label160">
<?php
echo $ETI['ofer08metodomatricula'];
?>
</label>
<label>
<?php
echo $html_ofer08metodomatricula;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['ofer08incluyelaboratorio'];
?>
</label>
<label class="Label60">
<?php
echo $html_ofer08incluyelaboratorio;
?>
</label>
<label class="Label160">
<?php
echo $ETI['ofer08puntajelaboratorio'];
?>
</label>
<label class="Label90">
<?php
$ofer08puntajelaboratorio_nombre='&nbsp;';
if ($_REQUEST['ofer08puntajelaboratorio']!=0){$ofer08puntajelaboratorio_nombre='';}
echo html_oculto('ofer08puntajelaboratorio', $_REQUEST['ofer08puntajelaboratorio']);
?>
</label>
<label class="Label130">
<?php
echo $ETI['ofer08incluyesalida'];
?>
</label>
<label class="Label60">
<?php
echo $html_ofer08incluyesalida;
?>
</label>
<label class="Label130">
<?php
echo $ETI['ofer08puntajesalida'];
?>
</label>
<label class="Label90">
<?php
$ofer08puntajesalida_nombre='&nbsp;';
if ($_REQUEST['ofer08puntajesalida']!=0){$ofer08puntajesalida_nombre='';}
echo html_oculto('ofer08puntajesalida', $_REQUEST['ofer08puntajesalida']);
?>
</label>

<div class="salto1px"></div>

<div class="GrupoCampos">
<div class="salto5px"></div>
<label class="Label200">
<?php
echo $ETI['ofer08grupoidforma'];
?>
</label>
<label class="Label220">
<?php
echo $html_ofer08grupoidforma;
?>
</label>
<label class="Label300">
<?php
echo $ETI['ofer08grupominest'];
?>
</label>
<label class="Label60">
<input id="ofer08grupominest" name="ofer08grupominest" type="text" value="<?php echo $_REQUEST['ofer08grupominest']; ?>" class="cuatro" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label class="Label300">
<?php
echo $ETI['ofer08grupomaxest'];
?>
</label>
<label class="Label60">
<input id="ofer08grupomaxest" name="ofer08grupomaxest" type="text" value="<?php echo $_REQUEST['ofer08grupomaxest']; ?>" class="cuatro" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<div class="salto1px"></div>
</div>

<?php
if (($_REQUEST['ofer08estadooferta']==9)){
	echo '<div class="salto1px"></div>'.$ETI['ofer08motivocancela'].': <b>'.$_REQUEST['ofer08motivocancela'].'</b>';
	}
?>
<?php
// -- Inicia Grupo campos 1730
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<div class="salto5px"></div>
<div class="salto5px"></div>
<?php
$iDatoTono=$_REQUEST['ofer08estadocampus'];
if ($_REQUEST['ofer08estadocampus']==$iEstadoBase){$iDatoTono=1;}
$sTono=OAI_TonoEstado($iDatoTono);
//<b>'.$ETI['ofer08estadocampus'].'</b>: 
$sTono2='';
if ($sTono!=''){
	$sTono='<div class="MarquesinaGrande"><div align="center" style="color:#'.$sTono.'">';
	$sTono2='</div></div>';
	}
echo ''.$sTono.'<b>'.$ofer08estadocampus_nombre.$ofer08estadodiseno_nombre.'</b>'.$sTono2.'';
?>
<div class="salto5px"></div>
<div class="salto5px"></div>
<input id="ofer08estadocampus" name="ofer08estadocampus" type="hidden" value="<?php echo $_REQUEST['ofer08estadocampus']; ?>" />
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['ofer08numrevisiones'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('ofer08numrevisiones', formato_numero($_REQUEST['ofer08numrevisiones']));
?>
</label>
<label class="Label160">
<?php
echo $ETI['ofer08numdevoluciones'];
?>
</label>
<label class="Label130"><div id="div_ofer08numdevoluciones">
<?php
echo html_oculto('ofer08numdevoluciones', $_REQUEST['ofer08numdevoluciones']);
?>
</div></label>
<label class="Label160">
<?php
echo $ETI['ofer08numajustes'];
?>
</label>
<label class="Label130"><div id="div_ofer08numajustes">
<?php
echo html_oculto('ofer08numajustes', $_REQUEST['ofer08numajustes']);
?>
</div></label>
<?php
echo html_salto();
echo $html_ActoresPrograma;
?>

<input id="boculta1730" name="boculta1730" type="hidden" value="<?php echo $_REQUEST['boculta1730']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div class="salto1px"></div>
<div id="div_f1730detalle">
<?php
echo $sTabla1730;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1730
// -- Inicia el foro.
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_foro'];
?>
</label>
<div class="salto1px"></div>
<div id="div_foro1708">
<?php
echo $sHTMLForo;
?>
</div>
<div class="salto1px"></div>
</div>
<?php
// -- Termina el foro.
?>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_campus'];
?>
</label>
<label class="Label90">
<?php
echo $ETI['ofer08estadocampus'];
?>
</label>
<label class="Label200">
<?php
echo html_oculto('ofer08estadocampus', $_REQUEST['ofer08estadocampus'], $ofer08estadocampus_nombre);
?>
</label>
<div class="salto1px"></div>
<?php
if ($_REQUEST['paso']==2){
	if (($_REQUEST['ofer08estadocampus']==-1)){
?>
<label></label>
<label class="Label90"></label>
<label class="Label130">
<input id="cmdactivar" name="cmdactivar" type="button" class="btSoloProceso" value="<?php echo $ETI['bt_restacceso']; ?>" onclick="ofer_ingresoactivado()"/>
</label>
<?php
		}
	}
?>

<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['ofer08idagenda'];
?>
</label>
<label class="Label600">
<?php
echo $html_ofer08idagenda;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['ofer08diainical'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['ofer08estadocampus']!=10){
?>
<input id="ofer08diainical" name="ofer08diainical" type="text" value="<?php echo $_REQUEST['ofer08diainical']; ?>" class="cuatro" maxlength="10"/>
<?php
	}else{
	echo html_oculto('ofer08diainical', $_REQUEST['ofer08diainical']);
	}
?>
</label>
<label class="Label160">
<?php
echo $ETI['ofer08numestudiantes'];
?>
</label>
<label class="Label90">
<?php
echo html_oculto('ofer08numestudiantes', $_REQUEST['ofer08numestudiantes']);
?>
</label>
<label class="Label160">
<?php
echo $ETI['ofer08numestaula1'];
?>
</label>
<label class="Label90">
<input id="ofer08numestaula1" name="ofer08numestaula1" type="text" value="<?php echo $_REQUEST['ofer08numestaula1']; ?>" class="diez" maxlength="10"/>
</label>
<label>
<?php
echo $html_AvanceMatricula;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['ofer08tipostandard'];
?>
</label>
<label class="Label160">
<?php
echo $html_ofer08tipostandard;
?>
</label>
<label class="Label30">
<?php
echo $ETI['ofer08idnav'];
?>
</label>
<label>
<?php
echo $html_ofer08idnav;
?>
</label>
<label class="Label90">
<?php
echo $ETI['ofer08idcursonav'];
?>
</label>
<label class="Label90">
<input id="ofer08idcursonav" name="ofer08idcursonav" type="text" value="<?php echo $_REQUEST['ofer08idcursonav']; ?>" class="cuatro" maxlength="10"/>
</label>

<?php
if ($_REQUEST['paso']==2){
	if (((int)$_REQUEST['ofer08idnav']!=0)&&((int)$_REQUEST['ofer08idcursonav']!=0)){
		echo '<label><a href="javascript:redir()" class="lnkresalte">'.$ETI['lnk_accesocurso'].'</a></label>';
		}else{
		echo '<label>'.$ETI['lnk_noaccesocurso'].'</label>';
		}
?>
<div class="salto1px"></div>
<label class="Label90"></label>
<label class="Label30">
<input type="button" id="bguarda1707" name="bguarda1707" value="Guardar" class="btMiniGuardar" onclick="enviaguardar()" title="<?php echo $ETI['bt_guardar']; ?>"/>
</label>
<label class="Label60">
</label>
<label class="Label30">
<input id="btActualizaAgenda" name="btActualizaAgenda" type="button" class="btMiniActualizar" onclick="actualizaagenda()" title="<?php echo $ETI['bt_actualiza']; ?>"/>
</label>
<label class="Label30">
</label>
<label class="Label30">
<input id="btDescargar" name="btDescargar" type="button" class="btMiniDescargar" onclick="descargaragenda()" title="<?php echo $ETI['bt_descargar']; ?>"/>
</label>
<label class="Label30">
</label>
<label class="Label30">
<input id="btDescargar" name="btDescargar" type="button" class="btMiniAnexar" onclick="agendaDatateca('')" title="<?php echo $ETI['bt_actualizar']; ?>"/>
</label>
<?php
	}
// Informacion de la agenda
?>
<div class="salto1px"></div>
<div id="div_f1718detalle">
<?php
echo $sTabla1718;
?>
</div>
<?php
// -- Fin de la agenda
?>
<div class="salto1px"></div>
<?php
// -- Inicia Grupo campos 148 Aulas adicionales
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_148'];
?>
</label>
<input id="boculta148" name="boculta148" type="hidden" value="<?php echo $_REQUEST['boculta148']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande148" name="btexpande148" type="button" value="Buscar" class="btMiniExpandir" onclick="expandepanel(148,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta148']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge148" name="btrecoge148" type="button" value="Buscar" class="btMiniRecoger" onclick="expandepanel(148,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta148']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p148" style="display:<?php if ($_REQUEST['boculta148']==0){echo 'block'; }else{echo 'none';} ?>;">
<input id="unad48per_aca" name="unad48per_aca" type="hidden" value="<?php echo $_REQUEST['unad48per_aca']; ?>"/>
<label class="Label90">
<?php
echo $ETI['unad48consec'];
?>
</label>
<label class="Label90"><div id="div_unad48consec">
<?php
if ((int)$_REQUEST['unad48id']==0){
?>
<input id="unad48consec" name="unad48consec" type="text" value="<?php echo $_REQUEST['unad48consec']; ?>" onchange="revisaf148()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('unad48consec', $_REQUEST['unad48consec']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['unad48id'];
?>
</label>
<label class="Label60"><div id="div_unad48id">
<?php
	echo html_oculto('unad48id', $_REQUEST['unad48id']);
?>
</div></label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['unad48identificador'];
?>
</label>
<label class="Label60">
<input id="unad48identificador" name="unad48identificador" type="text" value="<?php echo $_REQUEST['unad48identificador']; ?>" maxlength="1" class="dos"/>
</label>
<label class="Label130">
<?php
echo $ETI['unad48numestudiantes'];
?>
</label>
<label class="Label130">
<input id="unad48numestudiantes" name="unad48numestudiantes" type="text" value="<?php echo $_REQUEST['unad48numestudiantes']; ?>" class="diez" maxlength="10"/>
</label>
<label class="Label90">
<?php
echo $ETI['unad48diainicial'];
?>
</label>
<label class="Label60">
<input id="unad48diainicial" name="unad48diainicial" type="text" value="<?php echo $_REQUEST['unad48diainicial']; ?>" class="dos" maxlength="2"/>
</label>
<label class="Label30">
<input type="button" id="bguarda148" name="bguarda148" value="Guardar" class="btMiniGuardar" onclick="guardaf148()" title="<?php echo $ETI['bt_mini_guardar_148']; ?>"/>
</label>
<label class="Label30">
<input type="button" id="blimpia148" name="blimpia148" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf148()" title="<?php echo $ETI['bt_mini_limpiar_148']; ?>"/>
</label>
<label class="Label30">
<input type="button" id="belimina148" name="belimina148" value="Eliminar" class="btMiniEliminar" onclick="eliminaf148()" title="<?php echo $ETI['bt_mini_eliminar_148']; ?>" style="display:<?php if ((int)$_REQUEST['unad48id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<label class="Label30"></label>

<label class="Label30">
<input id="btActualizaHija" name="btActualizaHija" type="button" class="btMiniActualizar" onclick="actualizaAgendaHija()" title="<?php echo $ETI['msg_actualizahija']; ?>"/>
</label>

<label class="Label30"></label>
<label class="Label30">
<input id="btDescargar" name="btDescargar" type="button" class="btMiniDescargar" onclick="agendaDatateca(1)" title="<?php echo $ETI['bt_descargar']; ?>"/>
</label>

<?php
//Este es el cierre del div_p148
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div id="div_f148detalle">
<?php
echo $sTabla148;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 148 Aulas adicionales
?>
<div class="salto1px"></div>
<div class="GrupoCampos400">
<label class="TituloGrupo">
<?php
echo $ETI['ofer08usuarioconfirmaacceso'];
?>
</label>
<div class="salto1px"></div>
<input name="ofer08usuarioconfirmaacceso" type="hidden" id="ofer08usuarioconfirmaacceso" value="<?php echo $_REQUEST['ofer08usuarioconfirmaacceso']; ?>"/>
<label class="Label350">
<?php
//html_tipodoc("ofer08usuarioconfirmaacceso_td",$_REQUEST['ofer08usuarioconfirmaacceso_td'], false,"ter_muestra('ofer08usuarioconfirmaacceso',0)");
html_oculto("ofer08usuarioconfirmaacceso_td",$_REQUEST['ofer08usuarioconfirmaacceso_td']);
html_oculto("ofer08usuarioconfirmaacceso_doc",$_REQUEST['ofer08usuarioconfirmaacceso_doc']);
?>
</label>
<div class="salto1px"></div>
<div id="div_ofer08usuarioconfirmaacceso" class="L"><?php echo $ofer08usuarioconfirmaacceso_rs; ?></div>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['ofer08fechaaccede'];
?>
</label>
<div class="Campo220">
<?php
//echo html_fecha("ofer08fechaaccede", $_REQUEST['ofer08fechaaccede']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
echo html_oculto("ofer08fechaaccede", $_REQUEST['ofer08fechaaccede']);
?>
</div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos400">
<label class="TituloGrupo">
<?php
echo $ETI['msg_actualizacion'];
?>
</label>
<label class="Label380">
<?php
echo $ETI['ofer08origen'];
if ($_REQUEST['ofer08estadocampus']==4){
?>
<input id="ofer08origen" name="ofer08origen" type="text" value="<?php echo $_REQUEST['ofer08origen']; ?>">
<?php
	}else{
	echo html_oculto('ofer08origen', $_REQUEST['ofer08origen']);
	//echo ' -- '.$_REQUEST['ofer08estadocampus'];
	}
?>
</label>
<?php
if ($_REQUEST['ofer08estadocampus']==4){
?>
<div class="salto1px"></div>
<label class="Label60"></label>
<label class="Label130">
<input id="cmdretorna" name="cmdretorna" type="button" class="btSoloProceso" value="<?php echo $ETI['bt_actualizado']; ?>" onclick="ofer_actualizado()"/>
</label>
<label class="Label30">
</label>
<label class="Label130">
<input id="cmdNoContens" name="cmdNoContens" type="button" class="btSoloProceso" value="<?php echo $ETI['bt_noactualizado']; ?>" onclick="ofer_noactualizado()"/>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos400">
<label class="TituloGrupo">
<?php
echo $ETI['ofer08idacredita'];
?>
</label>
<div class="salto1px"></div>
<input id="ofer08idacredita" name="ofer08idacredita" type="hidden" value="<?php echo $_REQUEST['ofer08idacredita']; ?>"/>
<label class="Label350">
<?php
echo html_oculto("ofer08idacredita_td",$_REQUEST['ofer08idacredita_td']);//, false,"ter_muestra('ofer08idacredita',0)"
echo html_oculto("ofer08idacredita_doc",$_REQUEST['ofer08idacredita_doc']);
?>
</label>
<div class="salto1px"></div>
<div id="div_ofer08idacredita" class="L"><?php echo $ofer08idacredita_rs; ?></div>
<div class="salto1px"></div>
<label class="Label160">
<?php
if ($_REQUEST['ofer08obligaacreditar']!='S'){
	echo $ETI['msg_fechacertifica'];
	}else{
	echo $ETI['ofer08fechaacredita'];
	}
?>
</label>
<label class="Label130">
<?php
echo html_oculto("ofer08fechaacredita", $_REQUEST['ofer08fechaacredita']);
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['ofer08idevalacredita'];
?>
</label>
<label>
<?php
echo $html_ofer08idevalacredita;
?>
</label>
<div class="salto1px"></div>
<?php
$sAdd='';
if ($_REQUEST['ofer08obligaacreditar']!='S'){$sAdd=' {<b>'.$ETI['bt_obligatoria'].'</b>}';}
echo $ETI['ofer08notaacredita'].$sAdd.'<div class="salto1px"></div><b>'.cadena_notildes($_REQUEST['ofer08notaacredita']).'</b>';
?>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['ofer08puntajeacredita'];
?>
</label>
<label class="Label90">
<?php
echo html_oculto('ofer08puntajeacredita', $_REQUEST['ofer08puntajeacredita']);
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos400">
<label class="TituloGrupo">
<?php
echo $ETI['ofer08idaprueba'];
?>
</label>
<div class="salto1px"></div>
<input name="ofer08idaprueba" type="hidden" id="ofer08idaprueba" value="<?php echo $_REQUEST['ofer08idaprueba']; ?>"/>
<label class="Label350">
<?php
//html_tipodoc("ofer08idaprueba_td",$_REQUEST['ofer08idaprueba_td'], false,"ter_muestra('ofer08idaprueba',0)");
html_oculto("ofer08idaprueba_td",$_REQUEST['ofer08idaprueba_td']);
html_oculto("ofer08idaprueba_doc",$_REQUEST['ofer08idaprueba_doc']);
?>
</label>
<div class="salto1px"></div>
<div id="div_ofer08idaprueba" class="L"><?php echo $ofer08idaprueba_rs; ?></div>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['ofer08fechaaprobado'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto("ofer08fechaaprobado", $_REQUEST['ofer08fechaaprobado']);
?>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos400">
<label class="TituloGrupo">
<?php
echo $ETI['ofer08idusariorestaura'];
?>
</label>
<div class="salto1px"></div>
<div class="Campo220">
<label class="Label90">
<?php
echo $ETI['ofer08fechasolicrestaurar'];
?>
</label>
<?php
//echo html_fecha("ofer08fechasolicrestaurar", $_REQUEST['ofer08fechasolicrestaurar']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
echo html_oculto("ofer08fechasolicrestaurar", $_REQUEST['ofer08fechasolicrestaurar']);
?>
</div>
<?php
$bPasa=false;
if ($_REQUEST['ofer08idper_aca']<222){
	if ($_REQUEST['ofer08estadocampus']==15){$bPasa=true;}
	}else{
	if ($_REQUEST['ofer08estadocampus']==1){$bPasa=true;}
	}
if ($bPasa){
?>
<div class="salto1px"></div>
<label class="Label130">
<input id="cmdsolrest" name="cmdsolrest" type="button" class="btSoloProceso" value="<?php echo $ETI['bt_solrest']; ?>" onclick="ofer_solrest()"/>
</label>
<?php
	}
?>
<div class="salto1px"></div>
<input name="ofer08idusariorestaura" type="hidden" id="ofer08idusariorestaura" value="<?php echo $_REQUEST['ofer08idusariorestaura']; ?>"/>
<label class="Label350">
<?php
//html_tipodoc("ofer08idusariorestaura_td",$_REQUEST['ofer08idusariorestaura_td'], false,"ter_muestra('ofer08idusariorestaura',0)");
html_oculto("ofer08idusariorestaura_td",$_REQUEST['ofer08idusariorestaura_td']);
html_oculto("ofer08idusariorestaura_doc",$_REQUEST['ofer08idusariorestaura_doc']);
?>
</label>
<div class="salto1px"></div>
<div id="div_ofer08idusariorestaura" class="L"><?php echo $ofer08idusariorestaura_rs; ?></div>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['ofer08fecharestaurado'];
?>
</label>
<div class="Campo220">
<?php
//echo html_fecha("ofer08fecharestaurado", $_REQUEST['ofer08fecharestaurado']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
echo html_oculto("ofer08fecharestaurado", $_REQUEST['ofer08fecharestaurado']);
?>
</div>
<label class="L">
<?php
echo $ETI['ofer08migrados'].'&nbsp;&nbsp;';
if (($_REQUEST['ofer08estadocampus']==19)){
?>
<input id="ofer08migrados" name="ofer08migrados" type="text" value="<?php echo $_REQUEST['ofer08migrados']; ?>" class="L">
<?php
	}else{
	echo html_oculto('ofer08migrados', $_REQUEST['ofer08migrados']);
	}
?>
</label>
<?php
if (($_REQUEST['ofer08estadocampus']==19)){
?>
<div class="salto1px"></div>
<label class="Label130">
<input id="cmdmigrado" name="cmdmigrado" type="button" class="btSoloProceso" value="<?php echo $ETI['bt_migrado']; ?>" onclick="ofer_migrado()"/>
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>

<?php
//fin del div campus
?>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_copiacurso'];
?>
</label>
<div class="salto1px"></div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['ofer08copiaidusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="ofer08copiaidusuario" name="ofer08copiaidusuario" type="hidden" value="<?php echo $_REQUEST['ofer08copiaidusuario']; ?>"/>
<div id="div_ofer08copiaidusuario_llaves">
<?php
$bOculto=true;
echo html_DivTercero('ofer08copiaidusuario', $_REQUEST['ofer08copiaidusuario_td'], $_REQUEST['ofer08copiaidusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_ofer08copiaidusuario" class="L"><?php echo $ofer08copiaidusuario_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label90">
<?php
echo $ETI['ofer08copiafecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('ofer08copiafecha', $_REQUEST['ofer08copiafecha'], true);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<label class="Label30">
<input id="bofer08copiafecha_hoy" name="bofer08copiafecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('ofer08copiafecha','<?php echo fecha_hoy(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['ofer08copiaruta'];
?>

<input id="ofer08copiaruta" name="ofer08copiaruta" type="text" value="<?php echo $_REQUEST['ofer08copiaruta']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['ofer08copiaruta']; ?>"/>
</label>
<?php
if ($bConBotonCopia){
?>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label130">
<input id="cmdRegCopia" name="cmdRegCopia" type="button" class="btSoloProceso" value="Registrar" onclick="ofer_registrarcopia()" title="Registrar copia" />
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['msg_tablaccurso'];
?>
</label>
<div class="salto1px"></div>

<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['ofer08tablacidusuario'];
?>
</label>
<div class="salto1px"></div>
<input id="ofer08tablacidusuario" name="ofer08tablacidusuario" type="hidden" value="<?php echo $_REQUEST['ofer08tablacidusuario']; ?>"/>
<div id="div_ofer08tablacidusuario_llaves">
<?php
$bOculto=true;
echo html_DivTercero('ofer08tablacidusuario', $_REQUEST['ofer08tablacidusuario_td'], $_REQUEST['ofer08tablacidusuario_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_ofer08tablacidusuario" class="L"><?php echo $ofer08tablacidusuario_rs; ?></div>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['ofer08tablacfecha'];
?>
</label>
<div class="Campo220">
<?php
echo html_fecha('ofer08tablacfecha', $_REQUEST['ofer08tablacfecha'], true);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<label class="Label30">
<input id="bofer08tablacfecha_hoy" name="bofer08tablacfecha_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_asignar('ofer08tablacfecha','<?php echo fecha_hoy(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="txtAreaS">
<?php
echo $ETI['ofer08tablacruta'];
?>
<textarea id="ofer08tablacruta" name="ofer08tablacruta" placeholder="<?php echo $ETI['ing_campo'].$ETI['ofer08tablacruta']; ?>"><?php echo $_REQUEST['ofer08tablacruta']; ?></textarea>
</label>

<?php
//$bConBotonCopia
if (true){
?>
<div class="salto1px"></div>
<label class="Label130"></label>
<label class="Label130">
<input id="cmdRegtablac" name="cmdRegtablac" type="button" class="btSoloProceso" value="Registrar" onclick="ofer_registrartablac()" title="Registrar Tabla de calificaciones" />
</label>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['ofer08idresponsablepti'];
?>
</label>
<div class="salto1px"></div>
<input id="ofer08idresponsablepti" name="ofer08idresponsablepti" type="hidden" value="<?php echo $_REQUEST['ofer08idresponsablepti']; ?>"/>
<div id="div_ofer08idresponsablepti_llaves">
<?php
$bOculto=false;
echo html_DivTercero('ofer08idresponsablepti', $_REQUEST['ofer08idresponsablepti_td'], $_REQUEST['ofer08idresponsablepti_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_ofer08idresponsablepti" class="L"><?php echo $ofer08idresponsablepti_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos50Porc">
<div id="div_f1707historico">
<?php
echo $sTabla1707Historico;
?>
</div>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
</div>

<?php
// -- Siguen las anotaciones.
// -- Inicia Grupo campos 1713 Anotaciones
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1713'];
?>
</label>
<input id="boculta1713" name="boculta1713" type="hidden" value="<?php echo $_REQUEST['boculta1713']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel1713" name="btexcel1713" type="button" value="Exportar" class="btMiniExcel" onclick="imprime1713();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande1713" name="btexpande1713" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(1713,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1713']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1713" name="btrecoge1713" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(1713,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1713']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1713" style="display:<?php if ($_REQUEST['boculta1713']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label90">
<?php
echo $ETI['ofer13consec'];
?>
</label>
<label class="Label90"><div id="div_ofer13consec">
<?php
if ((int)$_REQUEST['ofer13id']==0){
?>
<input id="ofer13consec" name="ofer13consec" type="text" value="<?php echo $_REQUEST['ofer13consec']; ?>" onchange="revisaf1713()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('ofer13consec', $_REQUEST['ofer13consec']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['ofer13id'];
?>
</label>
<label class="Label60"><div id="div_ofer13id">
<?php
	echo html_oculto('ofer13id', $_REQUEST['ofer13id']);
?>
</div></label>
<div class="salto1px"></div>
<div class="GrupoCampos400">
<label class="TituloGrupo">
<?php
echo $ETI['ofer13anotacion'];
?>
</label>
<div class="salto1px"></div>
<label class="txtAreaS">
<textarea name="ofer13anotacion" id="ofer13anotacion"><?php echo $_REQUEST['ofer13anotacion']; ?></textarea>
</label>
<label class="Label90"></label>
<label class="Label30">
<input id="bguarda1713" name="bguarda1713" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf1713()" title="Guardar Anotaciones"/>
</label>
<label class="Label30">
<input type="button" name="blimpia1713" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1713()" title="Limpiar Anotaciones"/>
</label>
<label class="Label30">
<input type="button" id="belimina1713" name="belimina1713" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1713()" title="Eliminar Anotaciones" style="display:<?php if ((int)$_REQUEST['ofer13id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<div class="salto1px"></div>
</div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['ofer13idusuario'];
?>
</label>
<div class="salto1px"></div>
<input name="ofer13idusuario" type="hidden" id="ofer13idusuario" value="<?php echo $_REQUEST['ofer13idusuario']; ?>"/>
<label class="Label350"><div id="div_ofer13idusuario_td">
<?php
echo html_oculto("ofer13idusuario_td", $_REQUEST['ofer13idusuario_td']);
echo html_oculto("ofer13idusuario_doc", $_REQUEST['ofer13idusuario_doc']);
?>
</div></label>
<div class="salto1px"></div>
<div id="div_ofer13idusuario" class="L"><?php echo $ofer13idusuario_rs; ?></div>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['ofer13fecha'];
?>
</label>
<div class="Campo220" id="div_ofer13fecha">
<?php
echo html_oculto("ofer13fecha", $_REQUEST['ofer13fecha']);
?>
</div>
<label class="Label30"><div id="div_ofer13hora">
<?php
echo html_oculto("ofer13hora", $_REQUEST['ofer13hora'], formato_hora($_REQUEST['ofer13hora']));
?>
</div></label>
<label class="Label30">&nbsp;<b>:</b></label>
<label class="Label30"><div id="div_ofer13minuto">
<?php
echo html_oculto("ofer13minuto", $_REQUEST['ofer13minuto'], formato_hora($_REQUEST['ofer13minuto']));
?>
</div></label>
<div class="salto1px"></div>
</div>
<?php
//Este es el cierre del div_p1713
?>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div id="div_f1713detalle">
<?php
echo $sTabla1713;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 1713 Anotaciones
?>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1711'];
?>
</label>
<input id="boculta1711" name="boculta1711" type="hidden" value="<?php echo $_REQUEST['boculta1711']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div class="ir_derecha" style="width:92px;">
<label class="Label30">
<input id="bupdmatricula" name="bupdmatricula" type="button" value="Actualizar Matricula" class="btMiniActualizar" onclick="actualizarmatricula();" title="<?php echo $ETI['bt_actualizar']; ?>"/>
</label>
<label class="Label30">
<input id="btexpande1711" name="btexpande1711" type="button" value="Buscar" class="btMiniExpandir" onclick="expandepanel(1711,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1711']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1711" name="btrecoge1711" type="button" value="Buscar" class="btMiniRecoger" onclick="expandepanel(1711,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1711']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p1711" style="display:<?php if ($_REQUEST['boculta1711']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="GrupoCampos400">
<label class="TituloGrupo">
<?php
echo $ETI['ofer11idtercero'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['ofer11id'];
?>
</label>
<label class="Label60"><div id="div_ofer11id">
<?php
	echo html_oculto('ofer11id', $_REQUEST['ofer11id']);
?>
</div></label>
<div class="salto1px"></div>
<input id="ofer11idtercero" name="ofer11idtercero" type="hidden" value="<?php echo $_REQUEST['ofer11idtercero']; ?>"/>
<label class="Label350">
<?php
html_tipodoc("ofer11idtercero_td",$_REQUEST['ofer11idtercero_td'], false,"ter_muestra('ofer11idtercero',0)");
?>
<input id="ofer11idtercero_doc" name="ofer11idtercero_doc" type="text" value="<?php echo $_REQUEST['ofer11idtercero_doc']; ?>" onchange="ter_muestra('ofer11idtercero',0)" maxlength="13" onclick="revfoco(this);"/>
</label>
<label class="Label30">
<input type="button" name="bofer11idtercero" value="Buscar" class="btMiniBuscar" onclick="ter_busca('ofer11idtercero',0)" title="<?php echo $ETI['bt_ter_buscar']; ?>"/>
</label>
<div class="salto1px"></div>
<div id="div_ofer11idtercero" class="L"><?php echo $ofer11idtercero_rs; ?></div>
<div class="salto1px"></div>
<label class="Label60">
<?php
echo $ETI['ofer11idrol'];
?>
</label>
<label><div id="div_ofer11idrol">
<?php
echo $html_ofer11idrol;
?>
</div></label>
<div class="salto1px"></div>
<label class="Label60"></label>
<label class="Label30">
<input type="button" id="bguarda1711" name="bguarda1711" value="Guardar" class="btMiniGuardar" onclick="guardaf1711()" title="Guardar Actor"/>
</label>
<label class="Label30">
<input type="button" name="blimpia1711" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf1711()" title="Limpiar Actor"/>
</label>
<label class="Label30">
<input type="button" id="belimina1711" name="belimina1711" value="Eliminar" class="btMiniEliminar" onclick="eliminaf1711()" title="Eliminar Actor" style="display:<?php if ((int)$_REQUEST['ofer11id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
</div>
<div class="salto1px"></div>
<div id="div_f1711detalle">
<?php
echo $sTabla1711;
?>
</div>
<?php
	//fin de si el paso es 2
	}
?>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_1712'];
?>
</label>
<input name="boculta1712" type="hidden" id="boculta1712" value="<?php echo $_REQUEST['boculta1712']; ?>" />
<div class="salto1px"></div>
<?php
if ($_REQUEST['paso']==2){
?>
<div class="salto1px"></div>
<div id="div_f1712detalle">
<?php
echo $sTabla1712;
?>
</div>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
<?php
if ($bconexpande){
	//Este es el cierre del div_p1707
?>
<div class="salto1px"></div>
</div>
<?php
	}
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->

<div class="areaform">
<div class="areatitulo">
<?php
//'.$ETI['titulo_1791'].' - 
if ($_REQUEST['paso']==0){
	echo '<h3>'.$ETI['titulo_1791'].'</h3>';
	}else{
	echo '<h3>'.$ETI['titulo_1791'].' - '.$ofer08idper_aca_nombre.'</h3>';
	}
?>
</div>
<div class="areatrabajo">
<div class="salto1px"></div>
<input id="boculta1791" name="boculta1791" type="hidden" value="<?php echo $_REQUEST['boculta1791']; ?>" />
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande1791" name="btexpande1791" type="button" value="Buscar" class="btMiniExpandir" onclick="expandepanel(1791,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1791']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1791" name="btrecoge1791" type="button" value="Buscar" class="btMiniRecoger" onclick="expandepanel(1791,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1791']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<label class="TituloGrupo">
<?php
echo $ETI['titulo_responsables'];
?>
</label>
<label class="Label30">
<input id="cmdDescargaResponsables" name="cmdDescargaResponsables" type="button" value="" class="btMiniExcel" onclick="descargarresponsables()" title="Descargar Plantilla de Responsables" />
</label>
<div class="salto1px"></div>
<div id="div_p1791" style="display:<?php if ($_REQUEST['boculta1791']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="salto1px"></div>
<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="100000" />
<label class="Label450">
<input id="archivodatos" name="archivodatos" type="file" />
</label>
<label class="Label130">
<input id="cmdanexar" name="cmdanexar" type="button" class="btSoloAnexar" value="<?php echo $ETI['msg_subir']; ?>" onclick="ofer_cargaresponsables()"/>
</label>
<div class="salto1px"></div>
<?php
if ($sInfoDegub!=''){
?>
<div class="salto1px"></div>
<div style="height:100px;overflow:scroll;overflow-x:hidden;">
<?php
echo $sInfoDegub;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<hr />
<div class="salto1px"></div>
<input id="boculta1792" name="boculta1792" type="hidden" value="<?php echo $_REQUEST['boculta1792']; ?>" />
<div class="ir_derecha" style="width:62px;">
<label class="Label30">
<input id="btexpande1792" name="btexpande1792" type="button" value="Buscar" class="btMiniExpandir" onclick="expandepanel(1792,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta1792']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge1792" name="btrecoge1792" type="button" value="Buscar" class="btMiniRecoger" onclick="expandepanel(1792,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta1792']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<label class="TituloGrupo">
<?php
echo $ETI['titulo_navs'];
?>
</label>
<label class="Label30">
<input id="cmdDescargaNavs" name="cmdDescargaNavs" type="button" value="" class="btMiniExcel" onclick="descargarnavs()" title="Descargar Plantilla de NAVs" />
</label>
<div class="salto1px"></div>
<div id="div_p1792" style="display:<?php if ($_REQUEST['boculta1792']==0){echo 'block'; }else{echo 'none';} ?>;">
<div class="salto1px"></div>
<label class="Label450">
<input id="archivodatosnav" name="archivodatosnav" type="file" />
</label>
<label class="Label130">
<input id="cmdanexarnav" name="cmdanexarnav" type="button" class="btSoloAnexar" value="<?php echo $ETI['msg_subir']; ?>" onclick="ofer_cargarnavs()"/>
</label>
<div class="salto1px"></div>
<?php
if ($sInfoDegub2!=''){
?>
<div class="salto1px"></div>
<div style="height:100px;overflow:scroll;overflow-x:hidden;">
<?php
echo $sInfoDegub2;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>

</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
<div class="salto1px"></div>
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>'.$ETI['bloque1'].'</h3>';
?>
</div>
<div class="areatrabajo">
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['ofer08idescuela'];
?>
</label>
<label class="Label600">
<?php
echo $html_bescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
Codigo Curso
</label>
<label>
<input name="bcodcurso" type="text" id="bcodcurso" value="<?php echo $_REQUEST['bcodcurso']; ?>" onchange="paginarf1707()"/>
</label>
<label class="Label130">
Nombre Curso
</label>
<label>
<input name="bnombre" type="text" id="bnombre" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf1707()"/>
</label>
<label class="Label90">
<?php
echo $ETI['ofer08estadocampus'];
?>
</label>
<label class="Label200">
<?php
echo $html_blistar2;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['ofer08tipostandard'];
?>
</label>
<label class="Label130">
<?php
echo $html_bestandar;
?>
</label>
<label class="Label90">
<?php
echo $ETI['faltantes'];
?>
</label>
<label>
<?php
echo $html_blistar; 
?>
</label>
<label class="Label60">
<?php
echo $ETI['ofer08idnav'];
?>
</label>
<label class="Label200">
<?php
echo $html_bnav;
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['ofer08obligaacreditar'];
?>
</label>
<label class="Label160">
<?php
echo $html_bproceso;
?>
</label>
<label class="Label90">
<?php
echo $ETI['ofer08idcohorte'];
?>
</label>
<label class="Label250">
<?php
echo $html_bcohorte;
?>
</label>
<label class="Label160">
<?php
echo $ETI['ofer08idresponsablepti'];
?>
</label>
<label class="Label380">
<?php
echo $html_bresponsable;
?>
</label>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
</div>
<div class="salto1px"></div>
<div id="div_f1707detalle">
<?php
echo $sTabla1707;
?>
</div>
</div><!-- /div_areatrabajo -->
</div><!-- /DIV_areaform -->
</div><!-- /DIV_Sector1 -->


<div id="div_sector2" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda2" name="cmdAyuda2" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec2" name="cmdVolverSec2" type="button" class="btSupVolver" onclick="expandesector(1);" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_sector2campus'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_ayudadevol'];
?>
<div class="salto1px"></div>
</div>
<label class="L">
<?php
echo $ETI['msg_motivodev'];
?>
<input id="ofer30anotacion" name="ofer30anotacion" type="text" value="<?php echo $_REQUEST['ofer30anotacion']; ?>" class="L"/>
</label>
<div class="salto1px"></div>
<label class="Label220"></label>
<label class="Label130">
<input id="cmdDevuelveAcredita" name="cmdDevuelveAcredita" type="button" class="btSoloProceso" onclick="ofer_devueltoacredita()" title="Devolver" value="Devolver" />
</label>
<div class="salto1px"></div>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


<div id="div_sector3" style="display:none">
</div><!-- /DIV_Sector3 -->


<div id="div_sector4" style="display:none">
</div><!-- /DIV_Sector4 -->


<div id="div_sector95" style="display:none">
<div id="cargaForm">
<div id="div_95cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector95 -->


<div id="div_sector96" style="display:none">
<input id="div96v1" name="div96v1" type="hidden" value="" />
<input id="div96v2" name="div96v2" type="hidden" value="" />
<input id="div96v3" name="div96v3" type="hidden" value="" />
<input id="div96campo" name="div96campo" type="hidden" value="" />
<input id="div96llave" name="div96llave" type="hidden" value="" />
<input id="titulo_1707" name="titulo_1707" type="hidden" value="<?php echo $ETI['titulo_1715']; ?>" />
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda96" name="cmdAyuda96" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI" id="div_96titulo"></div>
</div>
<div id="cargaForm">
<div id="div_96cuerpo"></div>
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector96 -->


<div id="div_sector97" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda97" name="cmdAyuda97" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<input id="cmdVolverSec97" name="cmdVolverSec97" type="button" class="btSupVolver" onclick="retornacontrol();" title="<?php echo $ETI['bt_volver']; ?>" value="<?php echo $ETI['bt_volver']; ?>"/>
</div>
<div class="titulosI" id="div_97titulo">
<?php
echo '<h2>'.$ETI['titulo_1715'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div id="div_97params"></div>
<div class="salto1px"></div>
<div id="div_97tabla"></div>
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector97 -->


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_1715'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
<div class="MarquesinaMedia">
<?php
echo $ETI['msg_espere'];
?>
</div><!-- /Termina la marquesina -->
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector98 -->


<?php
if ($sDebug!=''){
	$iSegFin=microtime(true);
	$iSegundos=$iSegFin-$iSegIni;
	echo '<div class="salto1px"></div><div class="GrupoCampos" id="div_debug">'.$sDebug.fecha_microtiempo().' Tiempo total del proceso: <b>'.$iSegundos.'</b> Segundos'.'<div class="salto1px"></div></div>';
	}
?>
<input id="scampobusca" name="scampobusca" type="hidden" value=""/>
<input id="iscroll" name="iscroll" type="hidden" value="<?php echo $_REQUEST['iscroll']; ?>"/>
<input id="itipoerror" name="itipoerror" type="hidden" value="<?php echo $iTipoError; ?>"/>
<input id="debug" name="debug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
</form>
</div><!-- /DIV_interna -->
<div class="flotante">
<?php
if ($_REQUEST['paso']!=0){
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
	}
?>
</div>
<?php
echo html_DivAlarmaV2($sError, $iTipoError);
	//El script que cambia el sector que se muestra
?>

<script language="javascript">
<!--
<?php
if ($iSector!=1){
	echo 'setTimeout(function(){expandesector('.$iSector.');}, 10);
';
	}
if ($bMueveScroll){
	echo 'setTimeout(function(){retornacontrol();}, 2);
';
	}
?>
-->
</script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="ac_1707.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>