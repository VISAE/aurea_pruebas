<?php
/*
--- © Cristhiam Dario Silva Chavez - UNAD - 2019 ---
--- cristhiam.silva@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.23.7 Tuesday, October 22, 2019
*/
/** Archivo plaboferta.php.
* Modulo 2910 plab10oferta.
* @author Cristhiam Dario Silva Chavez - cristhiam.silva@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date Tuesday, October 22, 2019
*/
if (file_exists('./err_control.php')){require './err_control.php';}
$bDebug=false;
$sDebug='';
if (isset($_REQUEST['deb_doc'])!=0){
	$bDebug=true;
	}
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
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
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=2910;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_2910='lg/lg_2910_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2910)){$mensajes_2910='lg/lg_2910_es.php';}
require $mensajes_todas;
require $mensajes_2910;
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
if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=plaboferta.php');
		die();
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
	$bDebug=false;
	}else{
	$_REQUEST['deb_doc']='';
	}
if (isset($_REQUEST['debug'])!=0){
	if ($_REQUEST['debug']==1){$bDebug=true;}
	}else{
	$_REQUEST['debug']=0;
	}
//PROCESOS DE LA PAGINA
$idEntidad=0;
if (isset($APP->entidad)!=0){
	if ($APP->entidad==1){$idEntidad=1;}
	}
$mensajes_2914='lg/lg_2914_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2914)){$mensajes_2914='lg/lg_2914_es.php';}
$mensajes_2918='lg/lg_2918_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2918)){$mensajes_2918='lg/lg_2918_es.php';}
require $mensajes_2914;
require $mensajes_2918;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 2910 plab10oferta
require 'lib2910.php';
// -- 2914 aplicacion a oferta
require 'lib2914.php';
// -- 2918 
require 'lib2918.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'f2910_Comboplab10ubidep');
$xajax->register(XAJAX_FUNCTION,'f2910_Comboplab10ubiciudad');
$xajax->register(XAJAX_FUNCTION,'f2910_Comboplab10rangsala');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f2910_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2910_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f2910_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f2910_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f2914_Guardar');
$xajax->register(XAJAX_FUNCTION,'f2914_Traer');
$xajax->register(XAJAX_FUNCTION,'f2914_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f2914_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2914_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f2918_Comboplab18ubidep');
$xajax->register(XAJAX_FUNCTION,'f2918_Comboplab18ubiciudad');
$xajax->register(XAJAX_FUNCTION,'f2918_Guardar');
$xajax->register(XAJAX_FUNCTION,'f2918_Traer');
$xajax->register(XAJAX_FUNCTION,'f2918_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f2918_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2918_PintarLlaves');
$xajax->processRequest();
if ($bPeticionXAJAX){
	die(); // Esto hace que las llamadas por xajax terminen aquí.
	}
$bcargo=false;
$sError='';
$sErrorCerrando='';
$iTipoError=0;
$bLimpiaHijos=false;
$bMueveScroll=false;
$iSector=1;
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf2910'])==0){$_REQUEST['paginaf2910']=1;}
if (isset($_REQUEST['lppf2910'])==0){$_REQUEST['lppf2910']=20;}
if (isset($_REQUEST['boculta2910'])==0){$_REQUEST['boculta2910']=0;}
if (isset($_REQUEST['paginaf2914'])==0){$_REQUEST['paginaf2914']=1;}
if (isset($_REQUEST['lppf2914'])==0){$_REQUEST['lppf2914']=20;}
if (isset($_REQUEST['boculta2914'])==0){$_REQUEST['boculta2914']=0;}
if (isset($_REQUEST['paginaf2918'])==0){$_REQUEST['paginaf2918']=1;}
if (isset($_REQUEST['lppf2918'])==0){$_REQUEST['lppf2918']=20;}
if (isset($_REQUEST['boculta2918'])==0){$_REQUEST['boculta2918']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['plab10consecutivo'])==0){$_REQUEST['plab10consecutivo']='';}
if (isset($_REQUEST['plab10consecutivo_nuevo'])==0){$_REQUEST['plab10consecutivo_nuevo']='';}
if (isset($_REQUEST['plab10id'])==0){$_REQUEST['plab10id']='';}
if (isset($_REQUEST['plab10emprbolsempleo'])==0){$_REQUEST['plab10emprbolsempleo']='';}
if (isset($_REQUEST['plab10refoferta'])==0){$_REQUEST['plab10refoferta']='';}
if (isset($_REQUEST['plab10empresa'])==0){$_REQUEST['plab10empresa']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['plab10empresa_td'])==0){$_REQUEST['plab10empresa_td']=$APP->tipo_doc;}
if (isset($_REQUEST['plab10empresa_doc'])==0){$_REQUEST['plab10empresa_doc']='';}
if (isset($_REQUEST['plab10titulo'])==0){$_REQUEST['plab10titulo']='';}
if (isset($_REQUEST['plab10ubicacion'])==0){$_REQUEST['plab10ubicacion']='';}
if (isset($_REQUEST['plab10ubipais'])==0){$_REQUEST['plab10ubipais']=$_SESSION['unad_pais'];}
if (isset($_REQUEST['plab10ubidep'])==0){$_REQUEST['plab10ubidep']='';}
if (isset($_REQUEST['plab10ubiciudad'])==0){$_REQUEST['plab10ubiciudad']='';}
if (isset($_REQUEST['plab10fechapubini'])==0){$_REQUEST['plab10fechapubini']='';}//{fecha_hoy();}
if (isset($_REQUEST['plab10tipocont'])==0){$_REQUEST['plab10tipocont']='';}
if (isset($_REQUEST['plab10estado'])==0){$_REQUEST['plab10estado']='';}
if (isset($_REQUEST['plab10rangsala'])==0){$_REQUEST['plab10rangsala']='';}
if (isset($_REQUEST['plab10segmento'])==0){$_REQUEST['plab10segmento']='';}
if (isset($_REQUEST['plab10totalaplica'])==0){$_REQUEST['plab10totalaplica']='';}
if (isset($_REQUEST['plab10numvacantes'])==0){$_REQUEST['plab10numvacantes']='';}
if (isset($_REQUEST['plab10profesion'])==0){$_REQUEST['plab10profesion']='';}
if (isset($_REQUEST['plab10activo'])==0){$_REQUEST['plab10activo']='S';}
if (isset($_REQUEST['plab10fechapubfin'])==0){$_REQUEST['plab10fechapubfin']='';}//{fecha_hoy();}
if (isset($_REQUEST['plab10detalle'])==0){$_REQUEST['plab10detalle']='';}
if ((int)$_REQUEST['paso']>0){
	//aplicacion a oferta
	if (isset($_REQUEST['plab14hv'])==0){$_REQUEST['plab14hv']='';}
	if (isset($_REQUEST['plab14id'])==0){$_REQUEST['plab14id']='';}
	if (isset($_REQUEST['plab14fechaaplica'])==0){$_REQUEST['plab14fechaaplica']='';}//{fecha_hoy();}
	if (isset($_REQUEST['plab14estado'])==0){$_REQUEST['plab14estado']='';}
	if (isset($_REQUEST['plab14fechacancela'])==0){$_REQUEST['plab14fechacancela']='';}//{fecha_hoy();}
	if (isset($_REQUEST['plab14motivocancela'])==0){$_REQUEST['plab14motivocancela']='';}
	//
	if (isset($_REQUEST['plab18consecutivo'])==0){$_REQUEST['plab18consecutivo']='';}
	if (isset($_REQUEST['plab18id'])==0){$_REQUEST['plab18id']='';}
	if (isset($_REQUEST['plab18ubipais'])==0){$_REQUEST['plab18ubipais']=$_SESSION['unad_pais'];}
	if (isset($_REQUEST['plab18ubidep'])==0){$_REQUEST['plab18ubidep']='';}
	if (isset($_REQUEST['plab18ubiciudad'])==0){$_REQUEST['plab18ubiciudad']='';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if ((int)$_REQUEST['paso']>0){
	//aplicacion a oferta
	if (isset($_REQUEST['bnombre2914'])==0){$_REQUEST['bnombre2914']='';}
	//if (isset($_REQUEST['blistar2914'])==0){$_REQUEST['blistar2914']='';}
	//
	if (isset($_REQUEST['bnombre2918'])==0){$_REQUEST['bnombre2918']='';}
	//if (isset($_REQUEST['blistar2918'])==0){$_REQUEST['blistar2918']='';}
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['plab10empresa_td']=$APP->tipo_doc;
	$_REQUEST['plab10empresa_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='plab10consecutivo='.$_REQUEST['plab10consecutivo'].'';
		}else{
		$sSQLcondi='plab10id='.$_REQUEST['plab10id'].'';
		}
	$sSQL='SELECT * FROM plab10oferta WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['plab10consecutivo']=$fila['plab10consecutivo'];
		$_REQUEST['plab10id']=$fila['plab10id'];
		$_REQUEST['plab10emprbolsempleo']=$fila['plab10emprbolsempleo'];
		$_REQUEST['plab10refoferta']=$fila['plab10refoferta'];
		$_REQUEST['plab10empresa']=$fila['plab10empresa'];
		$_REQUEST['plab10titulo']=$fila['plab10titulo'];
		$_REQUEST['plab10ubicacion']=$fila['plab10ubicacion'];
		$_REQUEST['plab10ubipais']=$fila['plab10ubipais'];
		$_REQUEST['plab10ubidep']=$fila['plab10ubidep'];
		$_REQUEST['plab10ubiciudad']=$fila['plab10ubiciudad'];
		$_REQUEST['plab10fechapubini']=$fila['plab10fechapubini'];
		$_REQUEST['plab10tipocont']=$fila['plab10tipocont'];
		$_REQUEST['plab10estado']=$fila['plab10estado'];
		$_REQUEST['plab10rangsala']=$fila['plab10rangsala'];
		$_REQUEST['plab10segmento']=$fila['plab10segmento'];
		$_REQUEST['plab10totalaplica']=$fila['plab10totalaplica'];
		$_REQUEST['plab10numvacantes']=$fila['plab10numvacantes'];
		$_REQUEST['plab10profesion']=$fila['plab10profesion'];
		$_REQUEST['plab10activo']=$fila['plab10activo'];
		$_REQUEST['plab10fechapubfin']=$fila['plab10fechapubfin'];
		$_REQUEST['plab10detalle']=$fila['plab10detalle'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta2910']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f2910_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['plab10consecutivo_nuevo']=numeros_validar($_REQUEST['plab10consecutivo_nuevo']);
	if ($_REQUEST['plab10consecutivo_nuevo']==''){$sError=$ERR['plab10consecutivo'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT plab10id FROM plab10oferta WHERE plab10consecutivo='.$_REQUEST['plab10consecutivo_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['plab10consecutivo_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE plab10oferta SET plab10consecutivo='.$_REQUEST['plab10consecutivo_nuevo'].' WHERE plab10id='.$_REQUEST['plab10id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['plab10consecutivo'].' a '.$_REQUEST['plab10consecutivo_nuevo'].'';
		$_REQUEST['plab10consecutivo']=$_REQUEST['plab10consecutivo_nuevo'];
		$_REQUEST['plab10consecutivo_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['plab10id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f2910_db_Eliminar($_REQUEST['plab10id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['plab10consecutivo']='';
	$_REQUEST['plab10consecutivo_nuevo']='';
	$_REQUEST['plab10id']='';
	$_REQUEST['plab10emprbolsempleo']='';
	$_REQUEST['plab10refoferta']='';
	$_REQUEST['plab10empresa']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['plab10empresa_td']=$APP->tipo_doc;
	$_REQUEST['plab10empresa_doc']='';
	$_REQUEST['plab10titulo']='';
	$_REQUEST['plab10ubicacion']='';
	$_REQUEST['plab10ubipais']=$_SESSION['unad_pais'];
	$_REQUEST['plab10ubidep']='';
	$_REQUEST['plab10ubiciudad']='';
	$_REQUEST['plab10fechapubini']='';//fecha_hoy();
	$_REQUEST['plab10tipocont']='';
	$_REQUEST['plab10estado']='';
	$_REQUEST['plab10rangsala']='';
	$_REQUEST['plab10segmento']='';
	$_REQUEST['plab10totalaplica']='';
	$_REQUEST['plab10numvacantes']='';
	$_REQUEST['plab10profesion']='';
	$_REQUEST['plab10activo']='S';
	$_REQUEST['plab10fechapubfin']='';//fecha_hoy();
	$_REQUEST['plab10detalle']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['plab14oferta']='';
	$_REQUEST['plab14hv']='';
	$_REQUEST['plab14id']='';
	$_REQUEST['plab14fechaaplica']='';//fecha_hoy();
	$_REQUEST['plab14estado']='';
	$_REQUEST['plab14fechacancela']=fecha_hoy();
	$_REQUEST['plab14motivocancela']='';
	$_REQUEST['plab18idoferta']='';
	$_REQUEST['plab18consecutivo']='';
	$_REQUEST['plab18id']='';
	$_REQUEST['plab18ubipais']=$_SESSION['unad_pais'];
	$_REQUEST['plab18ubidep']='';
	$_REQUEST['plab18ubiciudad']='';
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
//list($devuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB, $bDebug);
//if ($devuelve){$seg_6=1;}
//$sDebug=$sDebug.$sDebugP;
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
list($plab10empresa_rs, $_REQUEST['plab10empresa'], $_REQUEST['plab10empresa_td'], $_REQUEST['plab10empresa_doc'])=html_tercero($_REQUEST['plab10empresa_td'], $_REQUEST['plab10empresa_doc'], $_REQUEST['plab10empresa'], 0, $objDB);
$objCombos->nuevo('plab10ubipais', $_REQUEST['plab10ubipais'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_plab10ubidep();';
$sSQL='SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
$html_plab10ubipais=$objCombos->html($sSQL, $objDB);
$html_plab10ubidep=f2910_HTMLComboV2_plab10ubidep($objDB, $objCombos, $_REQUEST['plab10ubidep'], $_REQUEST['plab10ubipais']);
$html_plab10ubiciudad=f2910_HTMLComboV2_plab10ubiciudad($objDB, $objCombos, $_REQUEST['plab10ubiciudad'], $_REQUEST['plab10ubidep']);
$objCombos->nuevo('plab10tipocont', $_REQUEST['plab10tipocont'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT plab11id AS id, plab11nombre AS nombre FROM plab11tipocont ORDER BY plab11nombre';
$html_plab10tipocont=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('plab10estado', $_REQUEST['plab10estado'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT plab12id AS id, plab12nombre AS nombre FROM plab12estadooferta ORDER BY plab12nombre';
$html_plab10estado=$objCombos->html($sSQL, $objDB);
$html_plab10rangsala=f2910_HTMLComboV2_plab10rangsala($objDB, $objCombos, $_REQUEST['plab10rangsala'], $_REQUEST['plab10empresa']);
$objCombos->nuevo('plab10segmento', $_REQUEST['plab10segmento'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT plab13id AS id, plab13nombre AS nombre FROM plab13segmento ORDER BY plab13nombre';
$html_plab10segmento=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('plab10profesion', $_REQUEST['plab10profesion'], true, '{'.$ETI['msg_seleccione'].'}');
$sSQL='SELECT plab02id AS id, plab02nombre AS nombre FROM plab02prof ORDER BY plab02nombre';
$html_plab10profesion=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('plab10activo', $_REQUEST['plab10activo'], false);
$objCombos->sino();
$html_plab10activo=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']==0){
	$objCombos->nuevo('plab10emprbolsempleo', $_REQUEST['plab10emprbolsempleo'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT plab08id AS id, plab08idtercero AS nombre FROM plab08emprbolsempleo ORDER BY plab08idtercero';
	$html_plab10emprbolsempleo=$objCombos->html($sSQL, $objDB);
	}else{
	list($plab10emprbolsempleo_nombre, $sErrorDet)=tabla_campoxid('plab08emprbolsempleo','plab08idtercero','plab08id',$_REQUEST['plab10emprbolsempleo'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_plab10emprbolsempleo=html_oculto('plab10emprbolsempleo', $_REQUEST['plab10emprbolsempleo'], $plab10emprbolsempleo_nombre);
	$html_plab14hv=f2914_HTMLComboV2_plab14hv($objDB, $objCombos, $_REQUEST['plab14hv']);
	$objCombos->nuevo('plab14estado', $_REQUEST['plab14estado'], true, '{'.$ETI['msg_seleccione'].'}');
	$sSQL='SELECT plab17id AS id, plab17nombre AS nombre FROM plab17estadoaplica ORDER BY plab17nombre';
	$html_plab14estado=$objCombos->html($sSQL, $objDB);
	$objCombos->nuevo('plab18ubipais', $_REQUEST['plab18ubipais'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_plab18ubidep();';
	$sSQL='SELECT unad18codigo AS id, unad18nombre AS nombre FROM unad18pais ORDER BY unad18nombre';
	$html_plab18ubipais=$objCombos->html($sSQL, $objDB);
	$html_plab18ubidep=f2918_HTMLComboV2_plab18ubidep($objDB, $objCombos, $_REQUEST['plab18ubidep'], $_REQUEST['plab18ubipais']);
	$html_plab18ubiciudad=f2918_HTMLComboV2_plab18ubiciudad($objDB, $objCombos, $_REQUEST['plab18ubiciudad'], $_REQUEST['plab18ubidep']);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2910()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(2910, 1, $objDB, 'paginarf2910()');
$objCombos->nuevo('blistar2914', $_REQUEST['blistar2914'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar2914=$objCombos->comboSistema(2914, 1, $objDB, 'paginarf2914()');
$objCombos->nuevo('blistar2918', $_REQUEST['blistar2918'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar2918=$objCombos->comboSistema(2918, 1, $objDB, 'paginarf2918()');
*/
//if ($seg_6==1){}
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=2910;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}
	if (seg_revisa_permiso($iCodModulo, 8, $objDB)){$seg_8=1;}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_2910'];
$aParametros[101]=$_REQUEST['paginaf2910'];
$aParametros[102]=$_REQUEST['lppf2910'];
//$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla2910, $sDebugTabla)=f2910_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla2914='';
$sTabla2918='';
if ($_REQUEST['paso']!=0){
	//aplicacion a oferta
	$aParametros2914[0]=$_REQUEST['plab10id'];
	$aParametros2914[101]=$_REQUEST['paginaf2914'];
	$aParametros2914[102]=$_REQUEST['lppf2914'];
	//$aParametros2914[103]=$_REQUEST['bnombre2914'];
	//$aParametros2914[104]=$_REQUEST['blistar2914'];
	list($sTabla2914, $sDebugTabla)=f2914_TablaDetalleV2($aParametros2914, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//
	$aParametros2918[0]=$_REQUEST['plab10id'];
	$aParametros2918[101]=$_REQUEST['paginaf2918'];
	$aParametros2918[102]=$_REQUEST['lppf2918'];
	//$aParametros2918[103]=$_REQUEST['bnombre2918'];
	//$aParametros2918[104]=$_REQUEST['blistar2918'];
	list($sTabla2918, $sDebugTabla)=f2918_TablaDetalleV2($aParametros2918, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2910']);
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
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
	if (dpaso.value==0){
		dpaso.value=10;
		}else{
		dpaso.value=12;
		}
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
	document.getElementById('div_sector93').style.display='none';
	document.getElementById('div_sector95').style.display='none';
	document.getElementById('div_sector96').style.display='none';
	document.getElementById('div_sector97').style.display='none';
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	var sEst='none';
	if (codigo==1){sEst='block';}
	document.getElementById('cmdGuardarf').style.display=sEst;
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
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_2910.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_2910.value;
		window.document.frmlista.nombrearchivo.value='ofertas';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
	//window.document.frmimpp.v3.value=window.document.frmedita.bnombre.value;
	//window.document.frmimpp.v4.value=window.document.frmedita.bcodigo.value;
	//window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value.trim();
	}
function imprimeexcel(){
	var sError='';
	if (window.document.frmedita.seg_6.value!=1){sError="<?php echo $ERR['6']; ?>";}
	//if (sError==''){/*Agregar validaciones*/}
	if (sError==''){
		asignarvariables();
		window.document.frmimpp.action='e2910.php';
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p2910.php';
		window.document.frmimpp.submit();
<?php
if ($iNumFormatosImprime>0){
?>
		expandesector(1);
<?php
	}
?>
		}else{
		window.alert("<?php echo $ERR['5']; ?>");
		}
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
	datos[1]=window.document.frmedita.plab10consecutivo.value;
	if ((datos[1]!='')){
		xajax_f2910_ExisteDato(datos);
		}
	}
function cargadato(llave1){
	window.document.frmedita.plab10consecutivo.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf2910(llave1){
	window.document.frmedita.plab10id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function carga_combo_plab10ubidep(){
	var params=new Array();
	params[0]=window.document.frmedita.plab10ubipais.value;
	xajax_f2910_Comboplab10ubidep(params);
	}
function carga_combo_plab10ubiciudad(){
	var params=new Array();
	params[0]=window.document.frmedita.plab10ubidep.value;
	xajax_f2910_Comboplab10ubiciudad(params);
	}
function carga_combo_plab10rangsala(){
	var params=new Array();
	params[0]=window.document.frmedita.plab10empresa.value;
	xajax_f2910_Comboplab10rangsala(params);
	}
function paginarf2910(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2910.value;
	params[102]=window.document.frmedita.lppf2910.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f2910detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2910" name="paginaf2910" type="hidden" value="'+params[101]+'" /><input id="lppf2910" name="lppf2910" type="hidden" value="'+params[102]+'" />';
	xajax_f2910_HtmlTabla(params);
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
	document.getElementById("plab10consecutivo").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f2910_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='plab10empresa'){
		ter_traerxid('plab10empresa', sValor);
		}
	retornacontrol();
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
function mod_consec(){
	if (confirm("Esta seguro de cambiar el consecutivo?")){
		expandesector(98);
		window.document.frmedita.paso.value=93;
		window.document.frmedita.submit();
		}
	}
// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" src="jsi/js2914.js"></script>
<script language="javascript" src="jsi/js2918.js"></script>
<?php
	}
?>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2910.php" target="_blank">
<input id="r" name="r" type="hidden" value="2910" />
<input id="id2910" name="id2910" type="hidden" value="<?php echo $_REQUEST['plab10id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
	}
?>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" autocomplete="off">
<input id="bNoAutocompletar" name="bNoAutocompletar" type="password" value="" style="display:none;"/>
<input id="paso" name="paso" type="hidden" value="<?php echo $_REQUEST['paso']; ?>" />
<input id="shoy" name="shoy" type="hidden" value="<?php echo fecha_hoy(); ?>" />
<input id="ihoy" name="ihoy" type="hidden" value="<?php echo fecha_DiaMod(); ?>" />
<input id="shora" name="shora" type="hidden" value="<?php echo fecha_hora(); ?>" />
<input id="stipodoc" name="stipodoc" type="hidden" value="<?php echo $APP->tipo_doc; ?>" />
<input id="idusuario" name="idusuario" type="hidden" value="<?php echo $_SESSION['unad_id_tercero']; ?>" />
<input id="seg_5" name="seg_5" type="hidden" value="<?php echo $seg_5; ?>" />
<input id="seg_6" name="seg_6" type="hidden" value="<?php echo $seg_6; ?>" />
<div id="div_sector1">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda" name="cmdAyuda" type="button" class="btUpAyuda" onclick="muestraayuda(<?php echo $APP->idsistema.', '.$iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
<?php
if ($_REQUEST['paso']==2){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		//$bHayImprimir=true;
		//$sScript='imprimep()';
		//if ($iNumFormatosImprime>0){
			//$sScript='expandesector(94)';
			//}
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
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
if (false){
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_2910'].'</h2>';
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
<input id="boculta2910" name="boculta2910" type="hidden" value="<?php echo $_REQUEST['boculta2910']; ?>" />
<label class="Label30">
<input id="btexpande2910" name="btexpande2910" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2910,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2910']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2910" name="btrecoge2910" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2910,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2910']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p2910" style="display:<?php if ($_REQUEST['boculta2910']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['plab10consecutivo'];
?>
</label>
<label class="Label130">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="plab10consecutivo" name="plab10consecutivo" type="text" value="<?php echo $_REQUEST['plab10consecutivo']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('plab10consecutivo', $_REQUEST['plab10consecutivo']);
	}
?>
</label>
<?php
/*
if ($seg_8==1){
	$objForma=new clsHtmlForma($iPiel);
	echo $objForma->htmlBotonSolo('cmdCambiaConsec', 'btMiniActualizar', 'expandesector(93);', $ETI['bt_cambiar'], 30);
	echo '<label class="Label30">&nbsp;</label>';
	}
*/
?>
<label class="Label60">
<?php
echo $ETI['plab10id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('plab10id', $_REQUEST['plab10id']);
?>
</label>
<label class="Label130">
<?php
echo $ETI['plab10emprbolsempleo'];
?>
</label>
<label>
<?php
echo $html_plab10emprbolsempleo;
?>
</label>
<label class="L">
<?php
echo $ETI['plab10refoferta'];
?>

<input id="plab10refoferta" name="plab10refoferta" type="text" value="<?php echo $_REQUEST['plab10refoferta']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['plab10refoferta']; ?>"/>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['plab10empresa'];
?>
</label>
<div class="salto1px"></div>
<input id="plab10empresa" name="plab10empresa" type="hidden" value="<?php echo $_REQUEST['plab10empresa']; ?>"/>
<div id="div_plab10empresa_llaves">
<?php
$bOculto=false;
echo html_DivTerceroV2('plab10empresa', $_REQUEST['plab10empresa_td'], $_REQUEST['plab10empresa_doc'], $bOculto, 0, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_plab10empresa" class="L"><?php echo $plab10empresa_rs; ?></div>
<div class="salto1px"></div>
</div>
<label class="L">
<?php
echo $ETI['plab10titulo'];
?>

<input id="plab10titulo" name="plab10titulo" type="text" value="<?php echo $_REQUEST['plab10titulo']; ?>" maxlength="150" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['plab10titulo']; ?>"/>
</label>
<label class="txtAreaS">
<?php
echo $ETI['plab10ubicacion'];
?>
<textarea id="plab10ubicacion" name="plab10ubicacion" placeholder="<?php echo $ETI['ing_campo'].$ETI['plab10ubicacion']; ?>"><?php echo $_REQUEST['plab10ubicacion']; ?></textarea>
</label>
<label class="Label130">
<?php
echo $ETI['plab10ubipais'];
?>
</label>
<label>
<?php
echo $html_plab10ubipais;
?>
</label>
<label class="Label130">
<?php
echo $ETI['plab10ubidep'];
?>
</label>
<label>
<div id="div_plab10ubidep">
<?php
echo $html_plab10ubidep;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['plab10ubiciudad'];
?>
</label>
<label>
<div id="div_plab10ubiciudad">
<?php
echo $html_plab10ubiciudad;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['plab10fechapubini'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab10fechapubini', $_REQUEST['plab10fechapubini']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bplab10fechapubini_hoy" name="bplab10fechapubini_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('plab10fechapubini','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['plab10tipocont'];
?>
</label>
<label>
<?php
echo $html_plab10tipocont;
?>
</label>
<label class="Label130">
<?php
echo $ETI['plab10estado'];
?>
</label>
<label>
<?php
echo $html_plab10estado;
?>
</label>
<label class="Label130">
<?php
echo $ETI['plab10rangsala'];
?>
</label>
<label>
<div id="div_plab10rangsala">
<?php
echo $html_plab10rangsala;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['plab10segmento'];
?>
</label>
<label>
<?php
echo $html_plab10segmento;
?>
</label>
<label class="Label130">
<?php
echo $ETI['plab10totalaplica'];
?>
</label>
<label class="Label130">
<input id="plab10totalaplica" name="plab10totalaplica" type="text" value="<?php echo $_REQUEST['plab10totalaplica']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['plab10numvacantes'];
?>
</label>
<label class="Label130">
<input id="plab10numvacantes" name="plab10numvacantes" type="text" value="<?php echo $_REQUEST['plab10numvacantes']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['plab10profesion'];
?>
</label>
<label>
<?php
echo $html_plab10profesion;
?>
</label>
<label class="Label130">
<?php
echo $ETI['plab10activo'];
?>
</label>
<label class="Label130">
<?php
echo $html_plab10activo;
?>
</label>
<label class="Label130">
<?php
echo $ETI['plab10fechapubfin'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab10fechapubfin', $_REQUEST['plab10fechapubfin']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bplab10fechapubfin_hoy" name="bplab10fechapubfin_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('plab10fechapubfin','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="txtAreaS">
<?php
echo $ETI['plab10detalle'];
?>
<textarea id="plab10detalle" name="plab10detalle" placeholder="<?php echo $ETI['ing_campo'].$ETI['plab10detalle']; ?>"><?php echo $_REQUEST['plab10detalle']; ?></textarea>
</label>
<?php
// -- Inicia Grupo campos 2914 aplicacion a oferta
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2914'];
?>
</label>
<input id="boculta2914" name="boculta2914" type="hidden" value="<?php echo $_REQUEST['boculta2914']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel2914" name="btexcel2914" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2914();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande2914" name="btexpande2914" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(2914,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2914']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2914" name="btrecoge2914" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(2914,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2914']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p2914" style="display:<?php if ($_REQUEST['boculta2914']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['plab14hv'];
?>
</label>
<label>
<div id="div_plab14hv">
<?php
echo $html_plab14hv;
?>
</div>
</label>
<label class="Label60">
<?php
echo $ETI['plab14id'];
?>
</label>
<label class="Label60"><div id="div_plab14id">
<?php
	echo html_oculto('plab14id', $_REQUEST['plab14id']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['plab14fechaaplica'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab14fechaaplica', $_REQUEST['plab14fechaaplica']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bplab14fechaaplica_hoy" name="bplab14fechaaplica_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('plab14fechaaplica','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<label class="Label130">
<?php
echo $ETI['plab14estado'];
?>
</label>
<label>
<?php
echo $html_plab14estado;
?>
</label>
<input id="plab14fechacancela" name="plab14fechacancela" type="hidden" value="<?php echo $_REQUEST['plab14fechacancela']; ?>"/>
<input id="plab14motivocancela" name="plab14motivocancela" type="hidden" value="<?php echo $_REQUEST['plab14motivocancela']; ?>"/>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda2914" name="bguarda2914" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2914()" title="<?php echo $ETI['bt_mini_guardar_2914']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia2914" name="blimpia2914" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2914()" title="<?php echo $ETI['bt_mini_limpiar_2914']; ?>"/>
</label>
<label class="Label30">
<input id="belimina2914" name="belimina2914" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2914()" title="<?php echo $ETI['bt_mini_eliminar_2914']; ?>" style="display:<?php if ((int)$_REQUEST['plab14id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p2914
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre2914" name="bnombre2914" type="text" value="<?php echo $_REQUEST['bnombre2914']; ?>" onchange="paginarf2914()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2914;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f2914detalle">
<?php
echo $sTabla2914;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2914 aplicacion a oferta
?>
<?php
// -- Inicia Grupo campos 2918 
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2918'];
?>
</label>
<input id="boculta2918" name="boculta2918" type="hidden" value="<?php echo $_REQUEST['boculta2918']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	//if ($bCondicion){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel2918" name="btexcel2918" type="button" value="Exportar" class="btMiniExcel" onclick="imprime2918();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande2918" name="btexpande2918" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(2918,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2918']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2918" name="btrecoge2918" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(2918,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2918']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p2918" style="display:<?php if ($_REQUEST['boculta2918']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['plab18consecutivo'];
?>
</label>
<label class="Label130"><div id="div_plab18consecutivo">
<?php
if ((int)$_REQUEST['plab18id']==0){
?>
<input id="plab18consecutivo" name="plab18consecutivo" type="text" value="<?php echo $_REQUEST['plab18consecutivo']; ?>" onchange="revisaf2918()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('plab18consecutivo', $_REQUEST['plab18consecutivo']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['plab18id'];
?>
</label>
<label class="Label60"><div id="div_plab18id">
<?php
	echo html_oculto('plab18id', $_REQUEST['plab18id']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['plab18ubipais'];
?>
</label>
<label>
<?php
echo $html_plab18ubipais;
?>
</label>
<label class="Label130">
<?php
echo $ETI['plab18ubidep'];
?>
</label>
<label>
<div id="div_plab18ubidep">
<?php
echo $html_plab18ubidep;
?>
</div>
</label>
<label class="Label130">
<?php
echo $ETI['plab18ubiciudad'];
?>
</label>
<label>
<div id="div_plab18ubiciudad">
<?php
echo $html_plab18ubiciudad;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda2918" name="bguarda2918" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf2918()" title="<?php echo $ETI['bt_mini_guardar_2918']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia2918" name="blimpia2918" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf2918()" title="<?php echo $ETI['bt_mini_limpiar_2918']; ?>"/>
</label>
<label class="Label30">
<input id="belimina2918" name="belimina2918" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf2918()" title="<?php echo $ETI['bt_mini_eliminar_2918']; ?>" style="display:<?php if ((int)$_REQUEST['plab18id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p2918
?>
<div class="salto1px"></div>
</div>
<?php
		//} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
<?php
echo $ETI['msg_nombre'];
?>
</label>
<label>
<input id="bnombre2918" name="bnombre2918" type="text" value="<?php echo $_REQUEST['bnombre2918']; ?>" onchange="paginarf2918()"/>
</label>
<label class="Label130">
<?php
echo $ETI['msg_Listar'];
?>
</label>
<label>
<?php
echo $html_blistar2918;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f2918detalle">
<?php
echo $sTabla2918;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2918 
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p2910
?>
<div class="salto1px"></div>
</div>
<?php
	}
//Mostrar el contenido de la tabla
?>
</div><!-- CIERRA EL DIV areatrabajo -->
</div><!-- CIERRA EL DIV areaform -->
<div class="areaform">
<div class="areatitulo">
<?php
echo '<h3>'.$ETI['bloque1'].'</h3>';
?>
</div>
<div class="areatrabajo">
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label90">
<?php
echo $ETI['msg_bnombre'];
?>
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2910()" autocomplete="off"/>
</label>
<label class="Label90">
<?php
echo $ETI['msg_blistar'];
?>
</label>
<label class="Label130">
<?php
echo $html_blistar;
?>
</label>
</div>
<div class="salto1px"></div>
<?php
	}
?>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f2910detalle">
<?php
echo $sTabla2910;
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
echo '<h2>'.$ETI['titulo_sector2'].'</h2>';
?>
</div>
</div>
<div id="cargaForm">
<div id="area">
</div><!-- /div_area -->
</div><!-- /DIV_cargaForm -->
</div><!-- /DIV_Sector2 -->


<div id="div_sector93" style="display:none">
<?php
$objForma=new clsHtmlForma($iPiel);
$objForma->addBoton('cmdAyuda93', 'btSupAyuda', 'muestraayuda('.$iCodModulo.');', $ETI['bt_ayuda']);
$objForma->addBoton('cmdVolverSec93', 'btSupVolver', 'expandesector(1);', $ETI['bt_volver']);
echo $objForma->htmlTitulo(''.$ETI['titulo_sector93'].'', $iCodModulo);
echo $objForma->htmlInicioMarco();
?>
<label class="Label160">
<?php
echo $ETI['msg_plab10consecutivo'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['plab10consecutivo'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_plab10consecutivo_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="plab10consecutivo_nuevo" name="plab10consecutivo_nuevo" type="text" value="<?php echo $_REQUEST['plab10consecutivo_nuevo']; ?>" class="cuatro"/>
</label>
<div class="salto1px"></div>
<label class="Label160">&nbsp;</label>
<?php
echo $objForma->htmlBotonSolo('cmdCambiaConsecFinal', 'botonProceso', 'mod_consec();', $ETI['bt_cambiar'], 130);
echo $objForma->htmlFinMarco();
?>
</div><!-- /DIV_Sector93 -->


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
<input id="titulo_2910" name="titulo_2910" type="hidden" value="<?php echo $ETI['titulo_2910']; ?>" />
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
echo '<h2>'.$ETI['titulo_2910'].'</h2>';
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
echo '<h2>'.$ETI['titulo_2910'].'</h2>';
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
?>
<input id="cmdGuardarf" name="cmdGuardarf" type="button" class="btSoloGuardar" onClick="enviaguardar();" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
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
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="ac_2910.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>