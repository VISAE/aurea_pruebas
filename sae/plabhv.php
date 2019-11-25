<?php
/** Archivo porlabhv.php.
* Modulo 2901 plab01hv.
* © Omar Augusto Bautista Mora - UNAD - 2019 ---
* @author Omar Augusto Bautista Mora - omar.bautista@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date Monday, Noviembre 18, 2019
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
require $APP->rutacomun.'libs/clsplanos.php';
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=2901;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_2901='lg/lg_2901_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_2901)){$mensajes_2901='lg/lg_2901_es.php';}
require $mensajes_todas;
require $mensajes_2901;
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
		header('Location:noticia.php?ret=porlabhv.php');
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
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 2901 plab01hv
require 'lib2901.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'unad11_Mostrar_v2');
$xajax->register(XAJAX_FUNCTION,'unad11_TraerXid');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f2901_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f2901_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f2901_Busquedas');
$xajax->register(XAJAX_FUNCTION,'f2901_HtmlBusqueda');
$xajax->register(XAJAX_FUNCTION,'f1902_Buscar_HV');
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
$sInfoProceso='';
// -- Se inicializan las variables, primero las que controlan la visualización de la página.
if (isset($_REQUEST['iscroll'])==0){$_REQUEST['iscroll']=0;}
if (isset($_REQUEST['paginaf2901'])==0){$_REQUEST['paginaf2901']=1;}
if (isset($_REQUEST['lppf2901'])==0){$_REQUEST['lppf2901']=20;}
if (isset($_REQUEST['boculta2901'])==0){$_REQUEST['boculta2901']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['plab01emprbolsempleo'])==0){$_REQUEST['plab01emprbolsempleo']='';}
if (isset($_REQUEST['plab01idtercero'])==0){$_REQUEST['plab01idtercero']=0;}// {$_SESSION['unad_id_tercero'];}
if (isset($_REQUEST['plab01idtercero_td'])==0){$_REQUEST['plab01idtercero_td']=$APP->tipo_doc;}
if (isset($_REQUEST['plab01idtercero_doc'])==0){$_REQUEST['plab01idtercero_doc']='';}
if (isset($_REQUEST['plab01id'])==0){$_REQUEST['plab01id']='';}
if (isset($_REQUEST['plab01fechareg'])==0){$_REQUEST['plab01fechareg']='';}//{fecha_hoy();}
if (isset($_REQUEST['plab01telprin'])==0){$_REQUEST['plab01telprin']='';}
if (isset($_REQUEST['plab01telofic'])==0){$_REQUEST['plab01telofic']='';}
if (isset($_REQUEST['plab01telmov'])==0){$_REQUEST['plab01telmov']='';}
if (isset($_REQUEST['plab01correo'])==0){$_REQUEST['plab01correo']='';}
if (isset($_REQUEST['plab01ultprof'])==0){$_REQUEST['plab01ultprof']='';}
if (isset($_REQUEST['plab01aspsal'])==0){$_REQUEST['plab01aspsal']='';}
if (isset($_REQUEST['plab01nomemprultexp'])==0){$_REQUEST['plab01nomemprultexp']='';}
if (isset($_REQUEST['plab01cargo'])==0){$_REQUEST['plab01cargo']='';}
if (isset($_REQUEST['plab01industria'])==0){$_REQUEST['plab01industria']='';}
if (isset($_REQUEST['plab01sector'])==0){$_REQUEST['plab01sector']='';}
if (isset($_REQUEST['plab01fechainiexp'])==0){$_REQUEST['plab01fechainiexp']='';}//{fecha_hoy();}
if (isset($_REQUEST['plab01fechafinexp'])==0){$_REQUEST['plab01fechafinexp']='';}//{fecha_hoy();}
if (isset($_REQUEST['plab01nivingles'])==0){$_REQUEST['plab01nivingles']='';}
if (isset($_REQUEST['plab01fechaacthv'])==0){$_REQUEST['plab01fechaacthv']='';}//{fecha_hoy();}
if (isset($_REQUEST['plab01numpostula'])==0){$_REQUEST['plab01numpostula']='';}
if (isset($_REQUEST['plab01condicion'])==0){$_REQUEST['plab01condicion']='';}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if (isset($_REQUEST['bdesde'])==0){$_REQUEST['bdesde']='0';}
if (isset($_REQUEST['bhasta'])==0){$_REQUEST['bhasta']='0';}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	$_REQUEST['plab01idtercero_td']=$APP->tipo_doc;
	$_REQUEST['plab01idtercero_doc']='';
	if ($_REQUEST['paso']==1){
		$sSQLcondi='plab01emprbolsempleo='.$_REQUEST['plab01emprbolsempleo'].' AND plab01idtercero="'.$_REQUEST['plab01idtercero'].'"';
		}else{
		$sSQLcondi='plab01id='.$_REQUEST['plab01id'].'';
		}
	$sSQL='SELECT * FROM plab01hv WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['plab01emprbolsempleo']=$fila['plab01emprbolsempleo'];
		$_REQUEST['plab01idtercero']=$fila['plab01idtercero'];
		$_REQUEST['plab01id']=$fila['plab01id'];
		$_REQUEST['plab01fechareg']=$fila['plab01fechareg'];
		$_REQUEST['plab01telprin']=$fila['plab01telprin'];
		$_REQUEST['plab01telofic']=$fila['plab01telofic'];
		$_REQUEST['plab01telmov']=$fila['plab01telmov'];
		$_REQUEST['plab01correo']=$fila['plab01correo'];
		$_REQUEST['plab01ultprof']=$fila['plab01ultprof'];
		$_REQUEST['plab01aspsal']=$fila['plab01aspsal'];
		$_REQUEST['plab01nomemprultexp']=$fila['plab01nomemprultexp'];
		$_REQUEST['plab01cargo']=$fila['plab01cargo'];
		$_REQUEST['plab01industria']=$fila['plab01industria'];
		$_REQUEST['plab01sector']=$fila['plab01sector'];
		$_REQUEST['plab01fechainiexp']=$fila['plab01fechainiexp'];
		$_REQUEST['plab01fechafinexp']=$fila['plab01fechafinexp'];
		$_REQUEST['plab01nivingles']=$fila['plab01nivingles'];
		$_REQUEST['plab01fechaacthv']=$fila['plab01fechaacthv'];
		$_REQUEST['plab01numpostula']=$fila['plab01numpostula'];
		$_REQUEST['plab01condicion']=$fila['plab01condicion'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta2901']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $sDebugGuardar)=f2901_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f2901_db_Eliminar($_REQUEST['plab01id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
if (($_REQUEST['paso']==50)){
	$_REQUEST['paso']=2;
	if (!seg_revisa_permiso($iCodModulo, 2, $objDB)){
		$sError=$ERR['2'];
		}
	if ($sError==''){
        if($_REQUEST['plab01emprbolsempleo']==''){
            $sError='Debe seleccionar la empresa bolsa de empleo';
            $_REQUEST['paso']=-1;
            }else{
            list($sError, $iTipoError, $sInfoProceso, $sDebugP) = f2901_ProcesarArchivo($_REQUEST, $_FILES, $objDB, $bDebug);
            $sDebug = $sDebug . $sDebugP;
            }
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['plab01emprbolsempleo']='';
	$_REQUEST['plab01idtercero']=0;//$_SESSION['unad_id_tercero'];
	$_REQUEST['plab01idtercero_td']=$APP->tipo_doc;
	$_REQUEST['plab01idtercero_doc']='';
	$_REQUEST['plab01id']='';
	$_REQUEST['plab01fechareg']='';//fecha_hoy();
	$_REQUEST['plab01telprin']='';
	$_REQUEST['plab01telofic']='';
	$_REQUEST['plab01telmov']='';
	$_REQUEST['plab01correo']='';
	$_REQUEST['plab01ultprof']='';
	$_REQUEST['plab01aspsal']='';
	$_REQUEST['plab01nomemprultexp']='';
	$_REQUEST['plab01cargo']='';
	$_REQUEST['plab01industria']='';
	$_REQUEST['plab01sector']='';
	$_REQUEST['plab01fechainiexp']='';//fecha_hoy();
	$_REQUEST['plab01fechafinexp']='';//fecha_hoy();
	$_REQUEST['plab01nivingles']='';
	$_REQUEST['plab01fechaacthv']='';//fecha_hoy();
	$_REQUEST['plab01numpostula']='';
	$_REQUEST['plab01condicion']='';
    $_REQUEST['bdesde']=0;
    $_REQUEST['bhasta']=0;
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Permisos adicionales
$seg_5=0;
$seg_6=0;
list($devuelve, $sDebugP)=seg_revisa_permisoV3($iCodModulo, 6, $idTercero, $objDB, $bDebug);
if ($devuelve){$seg_6=1;}
//$sDebug=$sDebug.$sDebugP;
//Crear los controles que requieran llamado a base de datos
$objCombos=new clsHtmlCombos();
$objTercero=new clsHtmlTercero();
list($plab01idtercero_rs, $_REQUEST['plab01idtercero'], $_REQUEST['plab01idtercero_td'], $_REQUEST['plab01idtercero_doc'])=html_tercero($_REQUEST['plab01idtercero_td'], $_REQUEST['plab01idtercero_doc'], $_REQUEST['plab01idtercero'], 0, $objDB);
$objCombos->nuevo('plab01ultprof', $_REQUEST['plab01ultprof'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->iAncho=330;
$sSQL='SELECT plab02id AS id, plab02nombre AS nombre FROM plab02prof ORDER BY plab02nombre';
$html_plab01ultprof=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('plab01aspsal', $_REQUEST['plab01aspsal'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->iAncho=330;
$sSQL='SELECT plab16id AS id, plab16nombre AS nombre FROM plab16aspsala ORDER BY plab16nombre';
$html_plab01aspsal=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('plab01cargo', $_REQUEST['plab01cargo'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->iAncho=330;
$sSQL='SELECT plab04id AS id, plab04nombre AS nombre FROM plab04cargo ORDER BY plab04nombre';
$html_plab01cargo=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('plab01industria', $_REQUEST['plab01industria'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->iAncho=330;
$sSQL='SELECT plab05id AS id, plab05nombre AS nombre FROM plab05industria ORDER BY plab05nombre';
$html_plab01industria=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('plab01sector', $_REQUEST['plab01sector'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->iAncho=330;
$sSQL='SELECT plab06id AS id, plab06nombre AS nombre FROM plab06sector ORDER BY plab06nombre';
$html_plab01sector=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('plab01nivingles', $_REQUEST['plab01nivingles'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->iAncho=330;
$sSQL='SELECT plab07id AS id, plab07nombre AS nombre FROM plab07nivingles ORDER BY plab07nombre';
$html_plab01nivingles=$objCombos->html($sSQL, $objDB);
$objCombos->nuevo('plab01condicion', $_REQUEST['plab01condicion'], true, '{'.$ETI['msg_seleccione'].'}');
$objCombos->iAncho=270;
$sSQL='SELECT plab15id AS id, plab15nombre AS nombre FROM plab15hvcondicion ORDER BY plab15nombre';
$html_plab01condicion=$objCombos->html($sSQL, $objDB);
if ((int)$_REQUEST['paso']==0){
	$html_plab01emprbolsempleo=f2901_HTMLComboV2_plab01emprbolsempleo($objDB, $objCombos, $_REQUEST['plab01emprbolsempleo']);
	}else{
    $plab01emprbolsempleo_nombre='';
	$sSQL='SELECT T1.unad11razonsocial AS nombre FROM plab08emprbolsempleo AS TB, unad11terceros AS T1 WHERE TB.plab08idtercero=T1.unad11id AND TB.plab08id='.$_REQUEST['plab01emprbolsempleo'].'';
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
	    $fila=$objDB->sf($tabla);
        $plab01emprbolsempleo_nombre=$fila['nombre'];
	    }
	$html_plab01emprbolsempleo=html_oculto('plab01emprbolsempleo', $_REQUEST['plab01emprbolsempleo'], $plab01emprbolsempleo_nombre);
	}
//Alistar datos adicionales
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf2901()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(2901, 1, $objDB, 'paginarf2901()');
*/
//if ($seg_6==1){}
if (true){
	$objCombos->nuevo('csv_separa', $_REQUEST['csv_separa'], false);
	$objCombos->addItem(',', $ETI['msg_coma']);
	$objCombos->addItem(';', $ETI['msg_puntoycoma']);
	$csv_separa='<label class="Label90">'.$ETI['msg_separador'].'</label><label class="Label130">'.$objCombos->html('', $objDB).'</label>';
	}else{
	$csv_separa='<input id="csv_separa" name="csv_separa" type="hidden" value="," />';
	}
$iNumFormatosImprime=0;
$iModeloReporte=2901;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_2901'];
$aParametros[101]=$_REQUEST['paginaf2901'];
$aParametros[102]=$_REQUEST['lppf2901'];
//$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
$aParametros[105]=$_REQUEST['bdesde'];
$aParametros[106]=$_REQUEST['bhasta'];
list($sTabla2901, $sDebugTabla)=f2901_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$bDebugMenu=false;
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebugMenu, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_2901']);
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
        params[4]=document.getElementById('plab01emprbolsempleo').value;
        xajax_f1902_Buscar_HV(params);
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
		if (idcampo=='plab01idtercero'){
			params[4]='RevisaLlave';
			}
		xajax_unad11_TraerXid(params);
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_2901.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_2901.value;
		window.document.frmlista.nombrearchivo.value='hoja de vida';
		window.document.frmlista.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function asignarvariables(){
    window.document.frmimpp.bdesdee.value=window.document.frmedita.bdesde.value;
    window.document.frmimpp.bhastae.value=window.document.frmedita.bhasta.value;
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
		window.document.frmimpp.action='t2901.php';
        window.document.frmimpp.separa.value=window.document.frmedita.csv_separa.value;
		window.document.frmimpp.submit();
		}else{
		window.alert(sError);
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p2901.php';
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
	datos[1]=window.document.frmedita.plab01emprbolsempleo.value;
	datos[2]=window.document.frmedita.plab01idtercero.value;
	if ((datos[1]!='')&&(datos[2]!='')){
		xajax_f2901_ExisteDato(datos);
		}
	}
function cargadato(llave1, llave2){
	window.document.frmedita.plab01emprbolsempleo.value=String(llave1);
	window.document.frmedita.plab01idtercero.value=String(llave2);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf2901(llave1){
	window.document.frmedita.plab01id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf2901(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf2901.value;
	params[102]=window.document.frmedita.lppf2901.value;
    params[105]=window.document.frmedita.bdesde.value;
    params[106]=window.document.frmedita.bhasta.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f2901detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf2901" name="paginaf2901" type="hidden" value="'+params[101]+'" /><input id="lppf2901" name="lppf2901" type="hidden" value="'+params[102]+'" />';
	xajax_f2901_HtmlTabla(params);
	}
function f2901_cargamasiva(){
    var extensiones_permitidas=new Array(".xls", ".xlsx");
	var sError='';
	var archivo=window.document.frmedita.archivodatos.value;
	var permitida=false;
	var iTipoError=0;
	var extension='';
	if (archivo){
		//recupero la extensión de este nombre de archivo
		extension=(archivo.substring(archivo.lastIndexOf('.'))).toLowerCase();
		//compruebo si la extensión está entre las permitidas
		for (i=0; i<extensiones_permitidas.length; i++) {
			if (extensiones_permitidas[i]==extension){
				permitida=true;
				break;
				}
			}
		}else{
		sError='No has seleccionado ning\u00fan archivo';
		}
	if (sError==''){
		if (permitida){
			window.document.frmedita.iscroll.value=window.pageYOffset;
			expandesector(98);
			window.document.frmedita.paso.value=50;
			window.document.frmedita.submit();
			}else{
			sError='Comprueba la extensi\u00f3n de los archivos a subir. \nS\u00f3lo se pueden subir archivos con extensiones: ' + extensiones_permitidas.join();
			}
		}
	if (sError!=''){
		// Mostrar el error
		MensajeAlarmaV2(sError, iTipoError);
		}
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
	document.getElementById("plab01emprbolsempleo").focus();
	}
function buscarV2016(sCampo){
	window.document.frmedita.iscroll.value=window.pageYOffset;
	expandesector(98);
	window.document.frmedita.scampobusca.value=sCampo;
	var params=new Array();
	params[1]=sCampo;
	//params[2]=window.document.frmedita.iagno.value;
	//params[3]=window.document.frmedita.itipo.value;
	xajax_f2901_Busquedas(params);
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
	}
function Devuelve(sValor){
	var sCampo=window.document.frmedita.scampobusca.value;
	if (sCampo=='plab01idtercero'){
		ter_traerxid('plab01idtercero', sValor);
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
// -->
function masivo_cargarhv(){
	var sError='';
	var bPasa=false;
	var iTipoError=0;
	var sArchivo=window.document.frmedita.archivodatos.value;
	window.document.frmedita.sRuta.value=window.document.frmedita.archivodatos.value;
	var aTipoArchivo=new Array('.csv', '.txt', '.xls');
	var sTipoArchivo='';
	if (sArchivo){
		//recupero la extensión de este nombre de archivo
		sTipoArchivo=(sArchivo.substring(sArchivo.lastIndexOf('.'))).toLowerCase();
		//compruebo si la extensión está entre las permitidas
		for (i=0; i<aTipoArchivo.length; i++) {
			if (aTipoArchivo[i]==sTipoArchivo){
				bPasa=true;
				break;
				}
			}
		}else{
		sError='No has seleccionado ning\u00fan archivo';
		}
	if (sError==''){	
		if (bPasa){
			window.document.frmedita.iscroll.value=window.pageYOffset;
			expandesector(98);
			window.document.frmedita.paso.value=60;
			window.document.frmedita.submit();
			}else{
			sError='Comprueba la extensi\u00f3n de los archivos a subir. \nS\u00f3lo se pueden subir archivos con extensiones: ' + aTipoArchivo.join();
			}
		}
	if (sError!=''){	
		// Mostrar el error
		MensajeAlarmaV2(sError, iTipoError);
		}
	}
</script>
<?php
//if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="t2901.php" target="_blank">
<input id="r" name="r" type="hidden" value="2901" />
<input id="id2901" name="id2901" type="hidden" value="<?php echo $_REQUEST['plab01id']; ?>" />
<input id="v3" name="v3" type="hidden" value="" />
<input id="v4" name="v4" type="hidden" value="" />
<input id="v5" name="v5" type="hidden" value="" />
<input id="bdesdee" name="bdesdee" type="hidden" value="<?php echo $_REQUEST['bdesde']; ?>" />
<input id="bhastae" name="bhastae" type="hidden" value="<?php echo $_REQUEST['bhasta']; ?>" />
<input id="iformato94" name="iformato94" type="hidden" value="0" />
<input id="separa" name="separa" type="hidden" value="," />
<input id="rdebug" name="rdebug" type="hidden" value="<?php echo $_REQUEST['debug']; ?>"/>
<input id="clave" name="clave" type="hidden" value="" />
</form>
<?php
//	}
?>
<form id="frmlista" name="frmlista" method="post" action="listados.php" target="_blank">
<input id="titulos" name="titulos" type="hidden" value="" />
<input id="consulta" name="consulta" type="hidden" value="" />
<input id="nombrearchivo" name="nombrearchivo" type="hidden" value="" />
</form>
<div id="interna">
<form id="frmedita" name="frmedita" method="post" action="" enctype="multipart/form-data" autocomplete="off">
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
$sScript='imprimeexcel()';
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
echo '<h2>'.$ETI['titulo_2901'].'</h2>';
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
<input id="boculta2901" name="boculta2901" type="hidden" value="<?php echo $_REQUEST['boculta2901']; ?>" />
<label class="Label30">
<input id="btexpande2901" name="btexpande2901" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(2901,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta2901']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge2901" name="btrecoge2901" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(2901,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta2901']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p2901" style="display:<?php if ($_REQUEST['boculta2901']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['plab01idtercero'];
?>
</label>
<label class="Label60">
<?php
echo $ETI['plab01id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('plab01id', $_REQUEST['plab01id']);
?>
</label>
<div class="salto1px"></div>
<input id="plab01idtercero" name="plab01idtercero" type="hidden" value="<?php echo $_REQUEST['plab01idtercero']; ?>"/>
<div id="div_plab01idtercero_llaves">
<?php
$bOculto=true;
if ($_REQUEST['paso']!=2){$bOculto=false;}
echo html_DivTerceroV2('plab01idtercero', $_REQUEST['plab01idtercero_td'], $_REQUEST['plab01idtercero_doc'], $bOculto, 1, $ETI['ing_doc']);
?>
</div>
<div class="salto1px"></div>
<div id="div_plab01idtercero" class="L"><?php echo $plab01idtercero_rs; ?></div>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['plab01emprbolsempleo'];
?>
</label>
<label class="Label220">
<?php
echo $html_plab01emprbolsempleo;
?>
</label>
</div>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['grupoprofesion'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['plab01ultprof'];
?>
</label>
<label>
<?php
echo $html_plab01ultprof;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['plab01nivingles'];
?>
</label>
<label>
<?php
echo $html_plab01nivingles;
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['plab01aspsal'];
?>
</label>
<label>
<?php
echo $html_plab01aspsal;
?>
</label>
</div>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['grupocontacto'];
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['plab01telprin'];
?>
</label>
<label>
<input id="plab01telprin" name="plab01telprin" type="text" value="<?php echo $_REQUEST['plab01telprin']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['plab01telprin']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['plab01telofic'];
?>
</label>
<label>
<input id="plab01telofic" name="plab01telofic" type="text" value="<?php echo $_REQUEST['plab01telofic']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['plab01telofic']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['plab01telmov'];
?>
</label>
<label>
<input id="plab01telmov" name="plab01telmov" type="text" value="<?php echo $_REQUEST['plab01telmov']; ?>" maxlength="30" placeholder="<?php echo $ETI['ing_campo'].$ETI['plab01telmov']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['plab01correo'];
?>
</label>
<label>
<input id="plab01correo" name="plab01correo" type="text" value="<?php echo $_REQUEST['plab01correo']; ?>" maxlength="50" placeholder="<?php echo $ETI['ing_campo'].$ETI['plab01correo']; ?>"/>
</label>
</div>
<div class="GrupoCampos520">
<label class="TituloGrupo">
<?php
echo $ETI['gruporegistrohv'];
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['plab01fechareg'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab01fechareg', $_REQUEST['plab01fechareg']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bplab01fechareg_hoy" name="bplab01fechareg_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('plab01fechareg','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['plab01numpostula'];
?>
</label>
<label class="Label160">
<input id="plab01numpostula" name="plab01numpostula" type="text" value="<?php echo $_REQUEST['plab01numpostula']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['plab01condicion'];
?>
</label>
<label class="Label220">
<?php
echo $html_plab01condicion;
?>
</label>
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['plab01fechaacthv'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab01fechaacthv', $_REQUEST['plab01fechaacthv']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bplab01fechaacthv_hoy" name="bplab01fechaacthv_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('plab01fechaacthv','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
</div>
<div class="salto1px"></div>
<div class="GrupoCampos520">
<label class="TituloGrupo Label320">
<?php
echo $ETI['grupolaboral'];
?>
</label>
<div class="salto1px"></div>
<label class="L">
<?php
echo $ETI['plab01nomemprultexp'];
?>

<input id="plab01nomemprultexp" name="plab01nomemprultexp" type="text" value="<?php echo $_REQUEST['plab01nomemprultexp']; ?>" maxlength="250" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['plab01nomemprultexp']; ?>"/>
</label>
<label class="Label160">
<?php
echo $ETI['plab01cargo'];
?>
</label>
<label>
<?php
echo $html_plab01cargo;
?>
</label>
<label class="Label160">
<?php
echo $ETI['plab01industria'];
?>
</label>
<label>
<?php
echo $html_plab01industria;
?>
</label>
<label class="Label160">
<?php
echo $ETI['plab01sector'];
?>
</label>
<label>
<?php
echo $html_plab01sector;
?>
</label>
<label class="Label220">
<?php
echo $ETI['plab01fechainiexp'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab01fechainiexp', $_REQUEST['plab01fechainiexp']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bplab01fechainiexp_hoy" name="bplab01fechainiexp_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('plab01fechainiexp','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['plab01fechafinexp'];
?>
</label>
<div class="Campo220">
<?php
echo html_FechaEnNumero('plab01fechafinexp', $_REQUEST['plab01fechafinexp']);//$bvacio=false,$accion=",$iagnoini=0,$iagnofin=0
?>
</div>
<!--
<label class="Label30">
<input id="bplab01fechafinexp_hoy" name="bplab01fechafinexp_hoy" type="button" value="Hoy" class="btMiniHoy" onclick="fecha_AsignarNum('plab01fechafinexp','<?php echo fecha_DiaMod(); ?>')" title="<?php echo $ETI['bt_hoy']; ?>"/>
</label>
-->
</div>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['grupocargamasiva'];
?>
</label>
<div class="salto1px"></div>
<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="<?php echo (5*1024*1024); ?>" />
<label class="Label500">
<input id="archivodatos" name="archivodatos" type="file" />
</label>
<label class="Label130">
<?php
$objForma=new clsHtmlForma($iPiel);
echo $objForma->htmlBotonSolo('cmdanexar', 'botonAnexar', 'f2901_cargamasiva()', $ETI['msg_subir']);
?>
</label>
<div class="salto1px"></div>
<div class="GrupoCamposAyuda">
<?php
echo $ETI['msg_infoplanohv'];
?>
<div class="salto1px"></div>
</div>
<?php
if ($sInfoProceso!=''){
?>
<div style="height:200px;overflow:scroll;overflow-x:hidden;">
<?php
echo $sInfoProceso;
?>
</div>
<?php
//Fin de si hay error de datos.
}
?>
<div class="salto1px"></div>
</div>
<!-- Fin Carga Masiva -->
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p2901
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
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf2901()" autocomplete="off"/>
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
<div class="salto1px"></div>
<label class="Label220">
<?php
echo $ETI['plab01inifechareg'];
?>
</label>
<label class="Label250">
<?php
echo html_FechaEnNumero('bdesde', $_REQUEST['bdesde'], true, 'paginarf2901();', 1900, date('Y'));
?>
</label>
<label class="Label220">
<?php
echo $ETI['plab01finfechareg'];
?>
</label>
<label class="Label250">
<?php
echo html_FechaEnNumero('bhasta', $_REQUEST['bhasta'], true, 'paginarf2901();', 1900, date('Y'));
?>
</label>
<div class="salto1px"></div>
<?php
echo ' '.$csv_separa;
?>
<div id="div_f2901detalle">
<?php
echo $sTabla2901;
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
<input id="titulo_2901" name="titulo_2901" type="hidden" value="<?php echo $ETI['titulo_2901']; ?>" />
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
echo '<h2>'.$ETI['titulo_2901'].'</h2>';
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
echo '<h2>'.$ETI['titulo_2901'].'</h2>';
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
<script language="javascript" src="ac_2901.js"></script>
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>