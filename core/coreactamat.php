<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2018 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
--- Modelo Versión 2.22.3 lunes, 13 de agosto de 2018
*/
if (file_exists('./err_control.php')){
    require './err_control.php';}
$bDebug=false;
$sDebug='';
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
require $APP->rutacomun.'libdatos.php';
require $APP->rutacomun.'libmovil.php';
//No conformidades.
require $APP->rutacomun.'libs/cls203.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=2216;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_2216=$APP->rutacomun.'lg/lg_2216_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2216)){$mensajes_2216=$APP->rutacomun.'lg/lg_2216_es.php';}
require $mensajes_todas;
require $mensajes_2216;
$xajax=NULL;
$objDB=new clsdbadmin($APP->dbhost, $APP->dbuser, $APP->dbpass, $APP->dbname);
if ($APP->dbpuerto!=''){$objDB->dbPuerto=$APP->dbpuerto;}
if (isset($APP->piel)==0){$APP->piel=1;}
$iPiel=$APP->piel;
$iPiel=1; //Piel 2018.
/*if (!seg_revisa_permiso($iCodModulo, 1, $objDB)){
	header('Location:nopermiso.php');
	die();
	}
*/
if (!$bPeticionXAJAX){
	if (noticias_pendientes($objDB)){
		$objDB->CerrarConexion();
		header('Location:noticia.php?ret=coreactamat.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
$mensajes_2204='lg/lg_2204_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2204)){$mensajes_2204='lg/lg_2204_es.php';}
require $mensajes_2204;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 2216 core16actamatricula
require $APP->rutacomun.'lib2216.php';
require $APP->rutacomun.'lib2202comun.php';
// -- 2204 Matricula
require 'lib2204acta.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'formatear_moneda');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantener');
$xajax->register(XAJAX_FUNCTION,'f2216_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2216_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f2216_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f2216_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f2204_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2202_Combobprograma');
$xajax->register(XAJAX_FUNCTION,'f2202_Combobcead');
$xajax->register(XAJAX_FUNCTION,'f2216_Comboprograma');
$xajax->register(XAJAX_FUNCTION,'f2216_Combocead');

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
if (isset($_REQUEST['paginaf2216'])==0){$_REQUEST['paginaf2216']=1;}
if (isset($_REQUEST['lppf2216'])==0){$_REQUEST['lppf2216']=20;}
if (isset($_REQUEST['boculta2216'])==0){$_REQUEST['boculta2216']=0;}
if (isset($_REQUEST['paginaf2204'])==0){$_REQUEST['paginaf2204']=1;}
if (isset($_REQUEST['lppf2204'])==0){$_REQUEST['lppf2204']=20;}
if (isset($_REQUEST['boculta2204'])==0){$_REQUEST['boculta2204']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['core16peraca'])==0){$_REQUEST['core16peraca']='';}
if (isset($_REQUEST['core16tercero'])==0){$_REQUEST['core16tercero']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['core16tercero_td'])==0){$_REQUEST['core16tercero_td']=$APP->tipo_doc;}
if (isset($_REQUEST['core16tercero_doc'])==0){$_REQUEST['core16tercero_doc']='';}
if (isset($_REQUEST['core16idprograma'])==0){$_REQUEST['core16idprograma']='';}
if (isset($_REQUEST['core16id'])==0){$_REQUEST['core16id']='';}
if (isset($_REQUEST['core16idcead'])==0){$_REQUEST['core16idcead']='';}
if (isset($_REQUEST['core16parametros'])==0){$_REQUEST['core16parametros']='';}
if (isset($_REQUEST['core16idescuela'])==0){$_REQUEST['core16idescuela']='';}
if (isset($_REQUEST['core16idzona'])==0){$_REQUEST['core16idzona']='';}
if (isset($_REQUEST['core16fecharecibido'])==0){$_REQUEST['core16fecharecibido']='';}//{fecha_hoy();}
if (isset($_REQUEST['core16minrecibido'])==0){$_REQUEST['core16minrecibido']='';}
if (isset($_REQUEST['core16procesado'])==0){$_REQUEST['core16procesado']=0;}
if (isset($_REQUEST['core16numcursos'])==0){$_REQUEST['core16numcursos']=0;}
if (isset($_REQUEST['core16numaprobados'])==0){$_REQUEST['core16numaprobados']=0;}
if (isset($_REQUEST['core16promedio'])==0){$_REQUEST['core16promedio']=0;}
if (isset($_REQUEST['core16origen'])==0){$_REQUEST['core16origen']=0;}
if (isset($_REQUEST['core16nuevo'])==0){$_REQUEST['core16nuevo']='';}
if (isset($_REQUEST['core16proccarac'])==0){$_REQUEST['core16proccarac']=0;}
if (isset($_REQUEST['core16procagenda'])==0){$_REQUEST['core16procagenda']=0;}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
if (isset($_REQUEST['bdoc'])==0){$_REQUEST['bdoc']='';}
if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']=3;}
if (isset($_REQUEST['bperiodo'])==0){$_REQUEST['bperiodo']='';}
if (isset($_REQUEST['bdetalle'])==0){$_REQUEST['bdetalle']='';}
if (isset($_REQUEST['bconvenio'])==0){$_REQUEST['bconvenio']='';}
if (isset($_REQUEST['bzona'])==0){$_REQUEST['bzona']='';}
if (isset($_REQUEST['bcead'])==0){$_REQUEST['bcead']='';}
if (isset($_REQUEST['bescuela'])==0){$_REQUEST['bescuela']='';}
if (isset($_REQUEST['bprograma'])==0){$_REQUEST['bprograma']='';}
if ($_REQUEST['paso']==21){
	// -- 2301 cara01encuesta
	require $APP->rutacomun.'lib2301.php';
	$_REQUEST['paso']=2;
	list($_REQUEST['core16procesado'], $_REQUEST['core16proccarac'], $_REQUEST['core16procagenda'], $_REQUEST['core16numcursos'], $sError, $sDebugP)=f2216_Procesar($_REQUEST['core16id'], $objDB, $bDebug, 1);
	$sDebug=$sDebug.$sDebugP;
	}
if ($_REQUEST['paso']==22){
	$_REQUEST['paso']=2;
	$objNoConformidad=new clsT203(2216);
	list($_REQUEST['core16procesado'], $_REQUEST['core16numcursos'], $sError, $sDebugP)=f2216_ProcesarMatricula($_REQUEST['core16id'], $objDB, $objNoConformidad, $bDebug);
	$sDebug=$sDebug.$sDebugP;
	list($sErrorT, $sDebugP)=f2216_TotalizarPeraca($_REQUEST['core16peraca'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugP;
	}
if ($_REQUEST['paso']==23){
	$_REQUEST['paso']=2;
	list($_REQUEST['core16procagenda'], $sError, $sDebugP)=f2216_ProcesarAgenda($_REQUEST['core16id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugP;
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['core16tercero_td']=$APP->tipo_doc;
	$_REQUEST['core16tercero_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='core16peraca='.$_REQUEST['core16peraca'].' AND core16tercero="'.$_REQUEST['core16tercero'].'" AND core16idprograma='.$_REQUEST['core16idprograma'].'';
		}else{
		$sSQLcondi='core16id='.$_REQUEST['core16id'].'';
		}
	$sSQL='SELECT * FROM core16actamatricula WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['core16peraca']=$fila['core16peraca'];
		$_REQUEST['core16tercero']=$fila['core16tercero'];
		$_REQUEST['core16idprograma']=$fila['core16idprograma'];
		$_REQUEST['core16id']=$fila['core16id'];
		$_REQUEST['core16idcead']=$fila['core16idcead'];
		$_REQUEST['core16parametros']=$fila['core16parametros'];
		$_REQUEST['core16idescuela']=$fila['core16idescuela'];
		$_REQUEST['core16idzona']=$fila['core16idzona'];
		$_REQUEST['core16fecharecibido']=$fila['core16fecharecibido'];
		$_REQUEST['core16minrecibido']=$fila['core16minrecibido'];
		$_REQUEST['core16procesado']=$fila['core16procesado'];
		$_REQUEST['core16numcursos']=$fila['core16numcursos'];
		$_REQUEST['core16numaprobados']=$fila['core16numaprobados'];
		$_REQUEST['core16promedio']=$fila['core16promedio'];
		$_REQUEST['core16origen']=$fila['core16origen'];
		$_REQUEST['core16nuevo']=$fila['core16nuevo'];
		$_REQUEST['core16proccarac']=$fila['core16proccarac'];
		$_REQUEST['core16procagenda']=$fila['core16procagenda'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta2216']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f2216_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f2216_db_Eliminar($_REQUEST['core16id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['core16peraca']='';
	$_REQUEST['core16tercero']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['core16tercero_td']=$APP->tipo_doc;
	$_REQUEST['core16tercero_doc']='';
	$_REQUEST['core16idprograma']='';
	$_REQUEST['core16id']='';
	$_REQUEST['core16idcead']='';
	$_REQUEST['core16parametros']='';
	$_REQUEST['core16idescuela']='';
	$_REQUEST['core16idzona']='';
	$_REQUEST['core16fecharecibido']='';//fecha_hoy();
	$_REQUEST['core16minrecibido']='';
	$_REQUEST['core16procesado']=0;
	$_REQUEST['core16numcursos']=0;
	$_REQUEST['core16numaprobados']=0;
	$_REQUEST['core16promedio']=0;
	$_REQUEST['core16origen']=0;
	$_REQUEST['core16nuevo']=0;
	$_REQUEST['core16proccarac']=0;
	$_REQUEST['core16procagenda']=0;
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos('n');
list($core16tercero_rs, $_REQUEST['core16tercero'], $_REQUEST['core16tercero_td'], $_REQUEST['core16tercero_doc'])=html_tercero($_REQUEST['core16tercero_td'], $_REQUEST['core16tercero_doc'], $_REQUEST['core16tercero'], 0, $objDB);
$objCombos->nuevo('core16idcead', $_REQUEST['core16idcead'], true, '{'.$ETI['msg_seleccione'].'}');
//$objCombos->addArreglo($acore16idcead, $icore16idcead);
$sSQL='SELECT unad24id AS id, unad24nombre AS nombre FROM unad24sede WHERE unad24activa="S" ORDER BY unad24nombre';
$html_core16idcead=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('core16idzona', $_REQUEST['core16idzona'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->sAccion='carga_combo_cead()';
$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona ORDER BY unad23nombre';
$html_core16idzona=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('core16nuevo', $_REQUEST['core16nuevo'], false, '{'.$ETI['msg_seleccione'].'}');
//$objCombos->addArreglo($acore16nuevo, $icore16nuevo);
$objCombos->addItem('0', $ETI['no']);
$objCombos->addItem('1', $ETI['si']);
$html_core16nuevo=$objCombos->html('', $objDB);
if ((int)$_REQUEST['paso']==0){
	$html_core16peraca=f2216_HTMLComboV2_core16peraca($objDB, $objCombos, $_REQUEST['core16peraca']);
	$objCombos->nuevo('core16idescuela', $_REQUEST['core16idescuela'], true, '{'.$ETI['msg_seleccione'].'}');
	$objCombos->sAccion='carga_combo_programa()';
	$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela ORDER BY core12nombre';
	$html_core16idescuela=$objCombos->html($sSQL, $objDB);
	$html_core16idprograma=f2216_HTMLComboV2_core16idprograma($objDB, $objCombos, $_REQUEST['core16idprograma']);
	}else{
	list($core16peraca_nombre, $sErrorDet)=tabla_campoxid('exte02per_aca','exte02nombre','exte02id',$_REQUEST['core16peraca'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_core16peraca=html_oculto('core16peraca', $_REQUEST['core16peraca'], $core16peraca_nombre);
	list($core16idescuela_nombre, $sErrorDet)=tabla_campoxid('core12escuela','core12nombre','core12id',$_REQUEST['core16idescuela'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_core16idescuela=html_oculto('core16idescuela', $_REQUEST['core16idescuela'], $core16idescuela_nombre);
	list($core16idprograma_nombre, $sErrorDet)=tabla_campoxid('exte03programa','exte03nombre','exte03id',$_REQUEST['core16idprograma'],'{'.$ETI['msg_sindato'].'}', $objDB);
	$html_core16idprograma=html_oculto('core16idprograma', $_REQUEST['core16idprograma'], $core16idprograma_nombre);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);

$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2216()';
$objCombos->addItem(1, 'Estudiantes nuevos');
$objCombos->addItem(2, 'Estudiantes antiguos');
$objCombos->addItem(3, 'Sin procesar matricula');
$objCombos->addItem(4, 'Sin procesar caracterizaci&oacute;n');
$objCombos->addItem(5, 'Sin procesar agenda');
$sSQL='';
$html_blistar=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('bperiodo', $_REQUEST['bperiodo'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2216()';
$sSQL=f146_ConsultaCombo();
$html_bperiodo=$objCombos->html($sSQL, $objDB);

$objCombos->nuevo('bzona', $_REQUEST['bzona'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='carga_combo_bcead()';
$sSQL='SELECT unad23id AS id, unad23nombre AS nombre FROM unad23zona WHERE unad23conestudiantes="S" ORDER BY unad23nombre';
$html_bzona=$objCombos->html($sSQL, $objDB);
$html_bcead=f2202_HTMLComboV2_bcead($objDB, $objCombos, $_REQUEST['bcead'], $_REQUEST['bzona']);
$objCombos->nuevo('bescuela', $_REQUEST['bescuela'], true, '{'.$ETI['msg_todas'].'}');
$objCombos->sAccion='carga_combo_bprograma()';
$sSQL='SELECT core12id AS id, core12nombre AS nombre FROM core12escuela WHERE core12tieneestudiantes="S" AND core12id>0 ORDER BY core12nombre';
$html_bescuela=$objCombos->html($sSQL, $objDB);
$html_bprograma=f2202_HTMLComboV2_bprograma($objDB, $objCombos, $_REQUEST['bprograma'], $_REQUEST['bescuela'], 1);

$objCombos->nuevo('bconvenio', $_REQUEST['bconvenio'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2216()';
$sSQL='SELECT core50id AS id, core50nombre AS nombre FROM core50convenios ORDER BY core50estado DESC, core50nombre';
$html_bconvenio=$objCombos->html($sSQL, $objDB);
//Permisos adicionales
$seg_5=0;
$seg_6=0;
if (seg_revisa_permiso($iCodModulo, 6, $objDB)){$seg_6=1;}
if ($seg_6==1){}
if (false){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=2216;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_2216'];
$aParametros[101]=$_REQUEST['paginaf2216'];
$aParametros[102]=$_REQUEST['lppf2216'];
$aParametros[103]=$_REQUEST['bnombre'];
$aParametros[104]=$_REQUEST['blistar'];
$aParametros[105]=$_REQUEST['bdoc'];
$aParametros[106]=$_REQUEST['bperiodo'];
$aParametros[107]=$_REQUEST['bdetalle'];
$aParametros[108]=$_REQUEST['bconvenio'];
$aParametros[109]=$_REQUEST['bzona'];
$aParametros[110]=$_REQUEST['bcead'];
$aParametros[111]=$_REQUEST['bescuela'];
$aParametros[112]=$_REQUEST['bprograma'];
list($sTabla2216, $sDebugTabla)=f2216_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla2204='';
if ($_REQUEST['paso']!=0){
	//Matricula
	$aParametros2204[0]=$_REQUEST['core16tercero'];
	$aParametros2204[1]=$_REQUEST['core16peraca'];
	$aParametros2204[101]=$_REQUEST['paginaf2204'];
	$aParametros2204[102]=$_REQUEST['lppf2204'];
	//$aParametros2204[103]=$_REQUEST['bnombre2204'];
	//$aParametros2204[104]=$_REQUEST['blistar2204'];
	list($sTabla2204, $sDebugTabla)=f2204_TablaDetalleV2($aParametros2204, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug);
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2216']);
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
		document.getElementById('div_'+idcampo).innerHTML='';
		//FuncionCuandoNoHayNada
		}
	}
function ter_traerxid(idcampo, vrcampo){
	var params=new Array();
	params[0]=vrcampo;
	params[1]=idcampo;
	if (params[0]!=0){
		if (idcampo=='core16tercero'){
			params[4]='RevisaLlave';
			}
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_2216.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_2216.value;
		window.document.frmlista.nombrearchivo.value='Actas de matricula';
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
	if (window.document.frmedita.seg_6.value==1){
		asignarvariables();
		window.document.frmimpp.action='e2216.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p2216.php';
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
	datos[1]=window.document.frmedita.core16peraca.value;
	datos[2]=window.document.frmedita.core16tercero.value;
	datos[3]=window.document.frmedita.core16idprograma.value;
	if ((datos[1]!='')&&(datos[2]!='')&&(datos[3]!='')){
		xajax_f2216_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2, llave3){
	window.document.frmedita.core16peraca.value=String(llave1);
	window.document.frmedita.core16tercero.value=String(llave2);
	window.document.frmedita.core16idprograma.value=String(llave3);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf2216(llave1){
	window.document.frmedita.core16id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf2216(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2216.value;
	params[102]=window.document.frmedita.lppf2216.value;
	params[103]=window.document.frmedita.bnombre.value;
	params[104]=window.document.frmedita.blistar.value;
	params[105]=window.document.frmedita.bdoc.value;
	params[106]=window.document.frmedita.bperiodo.value;
	params[107]=window.document.frmedita.bdetalle.value;
	params[108]=window.document.frmedita.bconvenio.value;
	params[109]=window.document.frmedita.bzona.value;
	params[110]=window.document.frmedita.bcead.value;
	params[111]=window.document.frmedita.bescuela.value;
	params[112]=window.document.frmedita.bprograma.value;
	xajax_f2216_HtmlTabla(params);
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
	document.getElementById("core16peraca").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f2216_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='core16tercero'){
		ter_traerxid('core16tercero', sValor);
		}
	retornacontrol();
	}
function mantener_sesion(){xajax_sesion_mantener();}
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
function procesar(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=21;
	window.document.frmedita.submit();
	}
function procesarmatricula(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=22;
	window.document.frmedita.submit();
	}
function procesaragenda(){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.paso.value=23;
	window.document.frmedita.submit();
	}
function carga_combo_bprograma(){
	var params=new Array();
	params[0]=window.document.frmedita.bescuela.value;
	params[1]=1;
	xajax_f2202_Combobprograma(params);
	}
function carga_combo_bcead(){
	var params=new Array();
	params[0]=window.document.frmedita.bzona.value;
	xajax_f2202_Combobcead(params);
	}
function paginarf2202(){
	paginarf2216();
	}
	
function carga_combo_programa(){
	var params=new Array();
	params[0]= window.document.frmedita.core16idescuela.value;
	params[1]=1;
	xajax_f2216_Comboprograma(params);
	}	
function carga_combo_cead(){
	var params=new Array();
	params[0]= window.document.frmedita.core16idzona.value;
	params[1]=1;
	xajax_f2216_Combocead(params);
	}	

// -->
</script>
<?php
if ($_REQUEST['paso']!=0){
?>
<script language="javascript" src="jsi/js2204acta.js"></script>
<?php
	}
?>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p2216.php" target="_blank">
<input id="r" name="r" type="hidden" value="2216" />
<input id="id2216" name="id2216" type="hidden" value="<?php echo $_REQUEST['core16id']; ?>" />
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
echo '<h2>'.$ETI['titulo_2216'].'</h2>';
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
<input id="boculta2216" name="boculta2216" type="hidden" value="<?php echo $_REQUEST['boculta2216']; ?>" />
<label class="Label30">
<input id="btexpande2216" name="btexpande2216" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2216,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2216']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2216" name="btrecoge2216" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2216,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2216']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p2216" style="display:<?php if ($_REQUEST['boculta2216']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['core16peraca'];
?>
</label>
<label>
<?php
echo $html_core16peraca;
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="TituloGrupo">
<?php
echo $ETI['core16tercero'];
?>
</label>
<div class="salto1px"></div>
<input id="core16tercero" name="core16tercero" type="hidden" value="<?php echo $_REQUEST['core16tercero']; ?>"/>
<div id="div_core16tercero_llaves">
<?php
$bOculto=true;
if ($_REQUEST['paso']!=2){$bOculto=false;}
echo html_DivTerceroV2('core16tercero', $_REQUEST['core16tercero_td'], $_REQUEST['core16tercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_core16tercero" class="L"><?php echo $core16tercero_rs; ?></div>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label90">
<?php
echo $ETI['core16nuevo'];
?>
</label>
<label class="Label90">
<?php
echo $html_core16nuevo;
?>
</label>
<label class="Label60">
<?php
echo $ETI['core16id'];
?>
</label>
<label class="Label60">
<?php
echo html_oculto('core16id', $_REQUEST['core16id']);
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['core16idescuela'];
?>
</label>
<label>
<?php
echo $html_core16idescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['core16idprograma'];
?>
</label>
<label><div id="div_core16idprograma">
<?php
echo $html_core16idprograma;
?>
</div></label>
<div class="salto1px"></div>
</div>

<label class="L">
<?php
echo $ETI['core16parametros'];
?>

<input id="core16parametros" name="core16parametros" type="text" value="<?php echo $_REQUEST['core16parametros']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['core16parametros']; ?>"/>
</label>

<div class="salto1px"></div>
<div class="GrupoCampos450">
<label class="Label90">
<?php
echo $ETI['core16idzona'];
?>
</label>
<label>
<?php
echo $html_core16idzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
<?php
echo $ETI['core16idcead'];
?>
</label>
<label><div id="div_core16idcead">
<?php
echo $html_core16idcead;
?>
</div></label>
<?php
if (false){
?>
<label class="Label130">
<?php
echo $ETI['core16origen'];
?>
</label>
<label class="Label130"><div id="div_core16origen">
<?php
echo html_oculto('core16origen', $_REQUEST['core16origen']);
?>
</div></label>
<?php
	}else{
?>
<input id="core16origen" name="core16origen" type="hidden" value="<?php echo $_REQUEST['core16origen']; ?>"/>
<?php
	}
?>
<div class="salto1px"></div>
</div>

<div class="GrupoCampos450">
<label class="Label130">
<?php
echo $ETI['core16fecharecibido'];
?>
</label>
<label class="Label130">
<?php
echo html_oculto('core16fecharecibido', $_REQUEST['core16fecharecibido'], fecha_desdenumero($_REQUEST['core16fecharecibido']));
?>
</label>
<label class="Label130">
<?php
echo html_oculto('core16minrecibido', $_REQUEST['core16minrecibido'], html_TablaHoraMinDesdeNumero($_REQUEST['core16minrecibido']));
?>
</label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core16numcursos'];
?>
</label>
<label class="Label60"><div id="div_core16numcursos">
<?php
echo html_oculto('core16numcursos', $_REQUEST['core16numcursos']);
?>
</div></label>
<label class="Label130">
<?php
echo $ETI['core16numaprobados'];
?>
</label>
<label class="Label60"><div id="div_core16numaprobados">
<?php
echo html_oculto('core16numaprobados', $_REQUEST['core16numaprobados']);
?>
</div></label>
<div class="salto1px"></div>
<label class="Label130">
<?php
echo $ETI['core16promedio'];
?>
</label>
<label class="Label160">
<?php
echo html_oculto('core16promedio', $_REQUEST['core16promedio'], formato_numero($_REQUEST['core16promedio'], 2));
?>
</label>
<div class="salto1px"></div>
</div>

<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="Label160">
<?php
echo $ETI['core16procesado'];
?>
</label>
<label class="Label90"><div id="div_core16procesado">
<?php
echo html_oculto('core16procesado', $_REQUEST['core16procesado']);
?>
</div></label>
<?php
if ($_REQUEST['paso']==2){
	//if ($_REQUEST['core16procesado']==0){
?>
<label class="Label30">
<input id="cmdProcesarM" name="cmdProcesarM" type="button" class="btMiniActualizar" value="Procesar" onclick="procesarmatricula()" title="Procesar Matricula" />
</label>
<label class="Label30">&nbsp;</label>
<?php
		//}
	}
?>

<label class="Label200">
<?php
echo $ETI['core16proccarac'];
?>
</label>
<label class="Label90"><div id="div_core16proccarac">
<?php
echo html_oculto('core16proccarac', $_REQUEST['core16proccarac']);
?>
</div></label>
<?php
if ($_REQUEST['paso']==2){
	//if ($_REQUEST['core16proccarac']==0){
?>
<label class="Label30">
<input id="cmdProcesar" name="cmdProcesar" type="button" class="btMiniActualizar" value="Procesar" onclick="procesar()" title="Procesar Caracterizaci&oacute;n" />
</label>
<label class="Label30">&nbsp;</label>
<?php
		//}
	}
?>
<label class="Label130">
<?php
echo $ETI['core16procagenda'];
?>
</label>
<label class="Label90"><div id="div_core16procagenda">
<?php
echo html_oculto('core16procagenda', $_REQUEST['core16procagenda']);
?>
</div></label>
<?php
if ($_REQUEST['paso']==2){
	//if ($_REQUEST['core16procagenda']==0){
?>
<label class="Label30">
<input id="cmdProcesarA" name="cmdProcesarA" type="button" class="btMiniActualizar" value="Procesar" onclick="procesaragenda()" title="Procesar Agenda" />
</label>
<label class="Label30">&nbsp;</label>
<?php
		//}
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Inicia Grupo campos 2204 Matricula
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_2204'];
?>
</label>
<input id="boculta2204" name="boculta2204" type="hidden" value="<?php echo $_REQUEST['boculta2204']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div class="salto1px"></div>
<?php
if (false){
?>
<div class="ir_derecha">
<label class="Label130">
Nombre
</label>
<label>
<input id="bnombre2204" name="bnombre2204" type="text" value="<?php echo $_REQUEST['bnombre2204']; ?>" onchange="paginarf2204()"/>
</label>
<label class="Label130">
Listar
</label>
<label>
<?php
echo $html_blistar2204;
?>
</label>
<div class="salto1px"></div>
</div>
<?php
	}
?>
<div id="div_f2204detalle">
<?php
echo $sTabla2204;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 2204 Matricula
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p2216
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
<div class="ir_derecha">
<label class="Label90">
Documento
</label>
<label>
<input id="bdoc" name="bdoc" type="text" value="<?php echo $_REQUEST['bdoc']; ?>" onchange="paginarf2216()" autocomplete="off"/>
</label>
<label class="Label90">
Nombre
</label>
<label>
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2216()" autocomplete="off"/>
</label>
<label class="Label60">
Listar
</label>
<label class="Label250">
<?php
echo $html_blistar;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Periodo
</label>
<label class="Label500">
<?php
echo $html_bperiodo;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Escuela
</label>
<label class="Label600">
<?php
echo $html_bescuela;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Programa
</label>
<label class="Label130">
<div id="div_bprograma">
<?php
echo $html_bprograma;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
Zona
</label>
<label class="Label350">
<?php
echo $html_bzona;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
CEAD
</label>
<label class="Label130">
<div id="div_bcead">
<?php
echo $html_bcead;
?>
</div>
</label>
<div class="salto1px"></div>
<label class="Label90">
Convenio
</label>
<label class="Label500">
<?php
echo $html_bconvenio;
?>
</label>
<div class="salto1px"></div>
<label class="Label90">
Parametros
</label>
<label>
<input id="bdetalle" name="bdetalle" type="text" value="<?php echo $_REQUEST['bdetalle']; ?>" onchange="paginarf2216()" autocomplete="off"/>
</label>
</div>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f2216detalle">
<?php
echo $sTabla2216;
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
<input id="titulo_2216" name="titulo_2216" type="hidden" value="<?php echo $ETI['titulo_2216']; ?>" />
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
echo '<h2>'.$ETI['titulo_2216'].'</h2>';
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
echo '<h2>'.$ETI['titulo_2216'].'</h2>';
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery-3.3.1.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/popper.min.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/bootstrap.min.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.css" type="text/css"/>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>js/jquery.autocomplete.js"></script>
<script language="javascript" src="ac_2216.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>