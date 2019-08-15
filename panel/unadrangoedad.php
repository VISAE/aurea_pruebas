<?php
/*
--- © Angel Mauro Avellaneda Barreto - UNAD - 2019 ---
--- angel.avellaneda@unad.edu.co - http://www.unad.edu.co
*/
/** Archivo unadrangoedad.php.
* Modulo 218 unae18rangoedad.
* @author Angel Mauro Avellaneda Barreto - angel.avellaneda@unad.edu.co
* @param debug=1 (Opcional), bandera para indicar si se generan datos de depuración
* @date Wednesday, August 14, 2019
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
if (($bPeticionXAJAX)&&($_SESSION['unad_id_tercero']==0)){
	// viene por xajax.
	$xajax=new xajax();
	$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
	$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
	$xajax->processRequest();
	die();
	}
$grupo_id=1;//Necesita ajustarlo...
$iCodModulo=218;
$audita[1]=false;
$audita[2]=true;
$audita[3]=true;
$audita[4]=true;
$audita[5]=false;
// -- Se cargan los archivos de idioma
$mensajes_todas=$APP->rutacomun.'lg/lg_todas_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_todas)){$mensajes_todas=$APP->rutacomun.'lg/lg_todas_es.php';}
$mensajes_218='lg/lg_218_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_218)){$mensajes_218='lg/lg_218_es.php';}
require $mensajes_todas;
require $mensajes_218;
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
		header('Location:noticia.php?ret=unadrangoedad.php');
		die();
		}
	}
//PROCESOS DE LA PAGINA
$mensajes_219='lg/lg_219_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_219)){$mensajes_219='lg/lg_219_es.php';}
$mensajes_220='lg/lg_220_'.$_SESSION['unad_idioma'].'.php';
if (!file_exists($mensajes_220)){$mensajes_220='lg/lg_220_es.php';}
require $mensajes_219;
require $mensajes_220;
// -- Si esta cargando la pagina por primer vez se revisa si requiere auditar y se manda a hacer un limpiar (paso -1)
if (isset($_REQUEST['paso'])==0){
	$_REQUEST['paso']=-1;
	if ($audita[1]){seg_auditaingreso($iCodModulo, $_SESSION['unad_id_tercero'], $objDB);}
	}
// -- 218 unae18rangoedad
require 'lib218.php';
// -- 219 Rangos
require 'lib219.php';
// -- 220 Distribucion
require 'lib220.php';
$xajax=new xajax();
$xajax->configure('javascript URI', $APP->rutacomun.'xajax/');
$xajax->register(XAJAX_FUNCTION,'sesion_abandona_V2');
$xajax->register(XAJAX_FUNCTION,'sesion_mantenerV4');
$xajax->register(XAJAX_FUNCTION,'f218_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f218_ExisteDato');
$xajax->register(XAJAX_FUNCTION,'f219_Guardar');
$xajax->register(XAJAX_FUNCTION,'f219_Traer');
$xajax->register(XAJAX_FUNCTION,'f219_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f219_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f219_PintarLlaves');
$xajax->register(XAJAX_FUNCTION,'f220_Guardar');
$xajax->register(XAJAX_FUNCTION,'f220_Traer');
$xajax->register(XAJAX_FUNCTION,'f220_Eliminar');
$xajax->register(XAJAX_FUNCTION,'f220_HtmlTabla');
$xajax->register(XAJAX_FUNCTION,'f220_PintarLlaves');
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
if (isset($_REQUEST['paginaf218'])==0){$_REQUEST['paginaf218']=1;}
if (isset($_REQUEST['lppf218'])==0){$_REQUEST['lppf218']=20;}
if (isset($_REQUEST['boculta218'])==0){$_REQUEST['boculta218']=0;}
if (isset($_REQUEST['paginaf219'])==0){$_REQUEST['paginaf219']=1;}
if (isset($_REQUEST['lppf219'])==0){$_REQUEST['lppf219']=20;}
if (isset($_REQUEST['boculta219'])==0){$_REQUEST['boculta219']=0;}
if (isset($_REQUEST['paginaf220'])==0){$_REQUEST['paginaf220']=1;}
if (isset($_REQUEST['lppf220'])==0){$_REQUEST['lppf220']=20;}
if (isset($_REQUEST['boculta220'])==0){$_REQUEST['boculta220']=0;}
// -- Inicializar variables de datos.
if (isset($_REQUEST['unae18consec'])==0){$_REQUEST['unae18consec']='';}
if (isset($_REQUEST['unae18consec_nuevo'])==0){$_REQUEST['unae18consec_nuevo']='';}
if (isset($_REQUEST['unae18id'])==0){$_REQUEST['unae18id']='';}
if (isset($_REQUEST['unae18estado'])==0){$_REQUEST['unae18estado']='N';}
if (isset($_REQUEST['unae18titulo'])==0){$_REQUEST['unae18titulo']='';}
if ((int)$_REQUEST['paso']>0){
	//Rangos
	if (isset($_REQUEST['unae19consec'])==0){$_REQUEST['unae19consec']='';}
	if (isset($_REQUEST['unae19id'])==0){$_REQUEST['unae19id']='';}
	if (isset($_REQUEST['unae19titulo'])==0){$_REQUEST['unae19titulo']='';}
	if (isset($_REQUEST['unae19base'])==0){$_REQUEST['unae19base']='';}
	if (isset($_REQUEST['unae19techo'])==0){$_REQUEST['unae19techo']='';}
	//Distribucion
	if (isset($_REQUEST['unae20edad'])==0){$_REQUEST['unae20edad']='';}
	if (isset($_REQUEST['unae20id'])==0){$_REQUEST['unae20id']='';}
	if (isset($_REQUEST['unae20idrango'])==0){$_REQUEST['unae20idrango']='';}
	}
// Espacio para inicializar otras variables
if (isset($_REQUEST['csv_separa'])==0){$_REQUEST['csv_separa']=',';}
if (isset($_REQUEST['bnombre'])==0){$_REQUEST['bnombre']='';}
//if (isset($_REQUEST['blistar'])==0){$_REQUEST['blistar']='';}
if ((int)$_REQUEST['paso']>0){
	//Rangos
	if (isset($_REQUEST['bnombre219'])==0){$_REQUEST['bnombre219']='';}
	//if (isset($_REQUEST['blistar219'])==0){$_REQUEST['blistar219']='';}
	//Distribucion
	if (isset($_REQUEST['bnombre220'])==0){$_REQUEST['bnombre220']='';}
	//if (isset($_REQUEST['blistar220'])==0){$_REQUEST['blistar220']='';}
	}
//Si Modifica o Elimina Cargar los campos
if (($_REQUEST['paso']==1)||($_REQUEST['paso']==3)){
	if ($_REQUEST['paso']==1){
		$sSQLcondi='unae18consec='.$_REQUEST['unae18consec'].'';
		}else{
		$sSQLcondi='unae18id='.$_REQUEST['unae18id'].'';
		}
	$sSQL='SELECT * FROM unae18rangoedad WHERE '.$sSQLcondi;
	$tabla=$objDB->ejecutasql($sSQL);
	if ($objDB->nf($tabla)>0){
		$fila=$objDB->sf($tabla);
		$_REQUEST['unae18consec']=$fila['unae18consec'];
		$_REQUEST['unae18id']=$fila['unae18id'];
		$_REQUEST['unae18estado']=$fila['unae18estado'];
		$_REQUEST['unae18titulo']=$fila['unae18titulo'];
		$bcargo=true;
		$_REQUEST['paso']=2;
		$_REQUEST['boculta218']=0;
		$bLimpiaHijos=true;
		}else{
		$_REQUEST['paso']=0;
		}
	}
//Cerrar
$bCerrando=false;
if ($_REQUEST['paso']==16){
	$_REQUEST['paso']=12;
	$_REQUEST['unae18estado']='S';
	$bCerrando=true;
	}
//Abrir
if ($_REQUEST['paso']==17){
	$_REQUEST['paso']=2;
	//Es posible que deba definir el codigo de permiso para abrir.
	if (!seg_revisa_permiso($iCodModulo, 17, $objDB)){
		$sError=$ERR['3'];
		}
	//Otras restricciones para abrir.
	if ($sError==''){
		//$sError='Motivo por el que no se pueda abrir, no se permite modificar.';
		}
	if ($sError!=''){
		$_REQUEST['unae18estado']='S';
		}else{
		$sSQL='UPDATE unae18rangoedad SET unae18estado="N" WHERE unae18id='.$_REQUEST['unae18id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		seg_auditar($iCodModulo, $_SESSION['unad_id_tercero'], 3, $_REQUEST['unae18id'], 'Abre Rangos de edad', $objDB);
		$_REQUEST['unae18estado']='N';
		$sError='<b>El documento ha sido abierto</b>';
		$iTipoError=1;
		}
	}
//Insertar o modificar un elemento
if (($_REQUEST['paso']==10)||($_REQUEST['paso']==12)){
	$bMueveScroll=true;
	list($_REQUEST, $sError, $iTipoError, $bCerrando, $sErrorCerrando, $sDebugGuardar)=f218_db_GuardarV2($_REQUEST, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugGuardar;
	if ($sError==''){
		$sError='<b>'.$ETI['msg_itemguardado'].'</b>';
		$iTipoError=1;
		if ($sErrorCerrando!=''){
			$iTipoError=0;
			$sError='<b>'.$ETI['msg_itemguardado'].'</b><br>'.$sErrorCerrando;
			}
		if ($bCerrando){
			$sError='<b>'.$ETI['msg_itemcerrado'].'</b>';
			}
		}
	}
if ($bCerrando){
	//acciones del cerrado
	}
// Cambio de consecutivo.
if ($_REQUEST['paso']==93){
	$_REQUEST['paso']=2;
	$_REQUEST['unae18consec_nuevo']=numeros_validar($_REQUEST['unae18consec_nuevo']);
	if ($_REQUEST['unae18consec_nuevo']==''){$sError=$ERR['unae18consec'];}
	if ($sError==''){
		if (!seg_revisa_permiso($iCodModulo, 8, $objDB)){
			$sError=$ERR['8'];
			}
		}
	if ($sError==''){
		//Ver que el consecutivo no exista.
		$sSQL='SELECT unae18id FROM unae18rangoedad WHERE unae18consec='.$_REQUEST['unae18consec_nuevo'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		if ($objDB->nf($tabla)>0){
			$sError='El consecutivo '.$_REQUEST['unae18consec_nuevo'].' ya existe';
			}
		}
	if ($sError==''){
		//Aplicar el cambio.
		$sSQL='UPDATE unae18rangoedad SET unae18consec='.$_REQUEST['unae18consec_nuevo'].' WHERE unae18id='.$_REQUEST['unae18id'].'';
		$tabla=$objDB->ejecutasql($sSQL);
		$sDetalle='Cambia el consecutivo de '.$_REQUEST['unae18consec'].' a '.$_REQUEST['unae18consec_nuevo'].'';
		$_REQUEST['unae18consec']=$_REQUEST['unae18consec_nuevo'];
		$_REQUEST['unae18consec_nuevo']='';
		seg_auditar($iCodModulo, $_SESSION['u_idtercero'], 8, $_REQUEST['unae18id'], $sDetalle, $objDB);
		$sError='<b>Se ha aplicado el cambio de consecutivo.</b>';
		$iTipoError=1;
		}else{
		$iSector=93;
		}
	}
//Eliminar un elemento
if ($_REQUEST['paso']==13){
	$_REQUEST['paso']=2;
	list($sError, $iTipoError, $sDebugElimina)=f218_db_Eliminar($_REQUEST['unae18id'], $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugElimina;
	if ($sError==''){
		$_REQUEST['paso']=-1;
		$sError=$ETI['msg_itemeliminado'];
		$iTipoError=1;
		}
	}
//limpiar la pantalla
if ($_REQUEST['paso']==-1){
	$_REQUEST['unae18consec']='';
	$_REQUEST['unae18consec_nuevo']='';
	$_REQUEST['unae18id']='';
	$_REQUEST['unae18estado']='N';
	$_REQUEST['unae18titulo']='';
	$_REQUEST['paso']=0;
	}
if ($bLimpiaHijos){
	$_REQUEST['unae19idrangoedad']='';
	$_REQUEST['unae19consec']='';
	$_REQUEST['unae19id']='';
	$_REQUEST['unae19titulo']='';
	$_REQUEST['unae19base']='';
	$_REQUEST['unae19techo']='';
	$_REQUEST['unae20idrangoedad']='';
	$_REQUEST['unae20edad']='';
	$_REQUEST['unae20id']='';
	$_REQUEST['unae20idrango']='';
	}
//AQUI SE DEBEN CARGAR TODOS LOS DATOS QUE LA FORMA NECESITE.
//DATOS PARA COMPLETAR EL FORMULARIO
//Crear los controles que requieran llamado a base de datos
if ((int)$_REQUEST['paso']==0){
	}else{
	}
//Alistar datos adicionales
$bPuedeAbrir=false;
if ($_REQUEST['paso']!=0){
	if ($_REQUEST['unae18estado']=='S'){
		//Definir las condiciones que permitirán abrir el registro.
		if (seg_revisa_permiso($iCodModulo, 17, $objDB)){$bPuedeAbrir=true;}
		}
	}
$id_rpt=0;
//$id_rpt=reportes_id(_Identificador_Tipo_Reporte_, $objDB);
/*
$objCombos->nuevo('blistar', $_REQUEST['blistar'], true, '{'.$ETI['msg_todos'].'}');
$objCombos->sAccion='paginarf218()';
$html_blistar=$objCombos->html('', $objDB);
//$html_blistar=$objCombos->comboSistema(218, 1, $objDB, 'paginarf218()');
$objCombos->nuevo('blistar219', $_REQUEST['blistar219'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar219=$objCombos->comboSistema(219, 1, $objDB, 'paginarf219()');
$objCombos->nuevo('blistar220', $_REQUEST['blistar220'], true, '{'.$ETI['msg_todos'].'}');
$html_blistar220=$objCombos->comboSistema(220, 1, $objDB, 'paginarf220()');
*/
//Permisos adicionales
$seg_5=0;
$seg_6=0;
$seg_8=0;
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
$iModeloReporte=218;
$html_iFormatoImprime='<input id="iformatoimprime" name="iformatoimprime" type="hidden" value="0" />
';
if ($_REQUEST['paso']>0){
	if (seg_revisa_permiso($iCodModulo, 5, $objDB)){
		$seg_5=1;
		}
	if ($_REQUEST['unae18estado']!='S'){
		if (seg_revisa_permiso($iCodModulo, 8, $objDB)){$seg_8=1;}
		}
	}
//Cargar las tablas de datos
$aParametros[0]='';//$_REQUEST['p1_218'];
$aParametros[101]=$_REQUEST['paginaf218'];
$aParametros[102]=$_REQUEST['lppf218'];
//$aParametros[103]=$_REQUEST['bnombre'];
//$aParametros[104]=$_REQUEST['blistar'];
list($sTabla218, $sDebugTabla)=f218_TablaDetalleV2($aParametros, $objDB, $bDebug);
$sDebug=$sDebug.$sDebugTabla;
$sTabla219='';
$sTabla220='';
if ($_REQUEST['paso']!=0){
	//Rangos
	$aParametros219[0]=$_REQUEST['unae18id'];
	$aParametros219[101]=$_REQUEST['paginaf219'];
	$aParametros219[102]=$_REQUEST['lppf219'];
	//$aParametros219[103]=$_REQUEST['bnombre219'];
	//$aParametros219[104]=$_REQUEST['blistar219'];
	list($sTabla219, $sDebugTabla)=f219_TablaDetalleV2($aParametros219, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	//Distribucion
	$aParametros220[0]=$_REQUEST['unae18id'];
	$aParametros220[101]=$_REQUEST['paginaf220'];
	$aParametros220[102]=$_REQUEST['lppf220'];
	//$aParametros220[103]=$_REQUEST['bnombre220'];
	//$aParametros220[104]=$_REQUEST['blistar220'];
	list($sTabla220, $sDebugTabla)=f220_TablaDetalleV2($aParametros220, $objDB, $bDebug);
	$sDebug=$sDebug.$sDebugTabla;
	}
list($et_menu, $sDebugM)=html_menuV2($APP->idsistema, $objDB, $iPiel, $bDebug, $idTercero);
$sDebug=$sDebug.$sDebugM;
$objDB->CerrarConexion();
//FORMA
require $APP->rutacomun.'unad_forma_v2.php';
forma_cabeceraV3($xajax, $ETI['titulo_218']);
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
	document.getElementById('div_sector98').style.display='none';
	document.getElementById('div_sector'+codigo).style.display='block';
	if (window.document.frmedita.unae18estado.value!='S'){
		var sEst='none';
		if (codigo==1){sEst='block';}
		document.getElementById('cmdGuardarf').style.display=sEst;
		}
	}
function imprimelista(){
	if (window.document.frmedita.seg_6.value==1){
		window.document.frmlista.consulta.value=window.document.frmedita.consulta_218.value;
		window.document.frmlista.titulos.value=window.document.frmedita.titulos_218.value;
		window.document.frmlista.nombrearchivo.value='Rangos de edad';
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
		window.document.frmimpp.action='e218.php';
		window.document.frmimpp.submit();
		}else{
		window.alert("<?php echo $ERR['6']; ?>");
		}
	}
function imprimep(){
	if (window.document.frmedita.seg_5.value==1){
		asignarvariables();
		window.document.frmimpp.action='p218.php';
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
	datos[1]=window.document.frmedita.unae18consec.value;
	if ((datos[1]!='')){
		xajax_f218_ExisteDato(datos);
		}
	}
function cargadato(llave1){
	window.document.frmedita.unae18consec.value=String(llave1);
	window.document.frmedita.paso.value=1;
	window.document.frmedita.submit();
	}
function cargaridf218(llave1){
	window.document.frmedita.unae18id.value=String(llave1);
	window.document.frmedita.paso.value=3;
	window.document.frmedita.submit();
	}
function paginarf218(){
	var params=new Array();
	params[99]=window.document.frmedita.debug.value;
	params[101]=window.document.frmedita.paginaf218.value;
	params[102]=window.document.frmedita.lppf218.value;
	//params[103]=window.document.frmedita.bnombre.value;
	//params[104]=window.document.frmedita.blistar.value;
	//document.getElementById('div_f218detalle').innerHTML='<div class="GrupoCamposAyuda"><div class="MarquesinaMedia">Procesando datos, por favor espere.</div></div><input id="paginaf218" name="paginaf218" type="hidden" value="'+params[101]+'" /><input id="lppf218" name="lppf218" type="hidden" value="'+params[102]+'" />';
	xajax_f218_HtmlTabla(params);
	}
function enviacerrar(){
	if (confirm('Esta seguro de cerrar el registro?\nluego de cerrado no se permite modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=16;
		window.document.frmedita.submit();
		}
	}
function enviaabrir(){
	if (confirm('Esta seguro de abrir el registro?\nesto le permite volver a modificar')){
		expandesector(98);
		window.document.frmedita.paso.value=17;
		window.document.frmedita.submit();
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
	document.getElementById("unae18consec").focus();
	}
function retornacontrol(){
	expandesector(1);
	window.scrollTo(0, window.document.frmedita.iscroll.value);
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
<script language="javascript" src="jsi/js219.js"></script>
<script language="javascript" src="jsi/js220.js"></script>
<?php
	}
?>
<?php
if ($_REQUEST['paso']!=0){
?>
<form id="frmimpp" name="frmimpp" method="post" action="p218.php" target="_blank">
<input id="r" name="r" type="hidden" value="218" />
<input id="id218" name="id218" type="hidden" value="<?php echo $_REQUEST['unae18id']; ?>" />
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
	if ($_REQUEST['unae18estado']!='S'){
?>
<input id="cmdEliminar" name="cmdEliminar" type="button" class="btUpEliminar" onclick="eliminadato();" title="<?php echo $ETI['bt_eliminar']; ?>" value="<?php echo $ETI['bt_eliminar']; ?>"/>
<?php
		}
	}
$bHayImprimir=false;
$sScript='imprimelista()';
$sClaseBoton='btEnviarExcel';
if ($seg_6==1){$bHayImprimir=true;}
if ($_REQUEST['paso']!=0){
	if ($seg_5==1){
		if ($_REQUEST['unae18estado']=='S'){
			//$bHayImprimir=true;
			//$sScript='imprimep()';
			//if ($iNumFormatosImprime>0){
				//$sScript='expandesector(94)';
				//}
			//$sClaseBoton='btEnviarPDF'; //btUpPrint
			//if ($id_rpt!=0){$sScript='verrpt()';}
			}
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
if ($_REQUEST['unae18estado']!='S'){
?>
<input id="cmdGuardar" name="cmdGuardar" type="button" class="btUpGuardar" onclick="enviaguardar();" title="<?php echo $ETI['bt_guardar']; ?>" value="<?php echo $ETI['bt_guardar']; ?>"/>
<?php
	if ($_REQUEST['paso']>0){
?>
<input id="cmdCerrar" name="cmdCerrar" type="button" class="btSupCerrar" onClick="enviacerrar();" title="Cerrar" value="Cerrar"/>
<?php
		}
	}else{
	if ($_REQUEST['paso']>0){
		if ($bPuedeAbrir){
?>
<input id="cmdAbrir" name="cmdAbrir" type="button" class="btSupAbrir" onclick="enviaabrir();" title="Abrir" value="Abrir"/>
<?php
			}
		}
	}
if (false){
?>
<input id="cmdAnular" name="cmdAnular" type="button" class="btSupAnular" onclick="expandesector(2);" title="<?php echo $ETI['bt_anular']; ?>" value="<?php echo $ETI['bt_anular']; ?>"/>
<?php
	}
?>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_218'].'</h2>';
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
<input id="boculta218" name="boculta218" type="hidden" value="<?php echo $_REQUEST['boculta218']; ?>" />
<label class="Label30">
<input id="btexpande218" name="btexpande218" type="button" value="Mostrar" class="btMiniExpandir" onclick="expandepanel(218,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta218']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge218" name="btrecoge218" type="button" value="Ocultar" class="btMiniRecoger" onclick="expandepanel(218,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta218']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div id="div_p218" style="display:<?php if ($_REQUEST['boculta218']==0){echo 'block'; }else{echo 'none';} ?>;">
<?php
	}
//Mostrar formulario para editar
?>
<label class="Label130">
<?php
echo $ETI['unae18consec'];
?>
</label>
<label class="Label90">
<?php
if ($_REQUEST['paso']!=2){
?>
<input id="unae18consec" name="unae18consec" type="text" value="<?php echo $_REQUEST['unae18consec']; ?>" onchange="RevisaLlave()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('unae18consec', $_REQUEST['unae18consec']);
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
echo $ETI['unae18id'];
?>
</label>
<label class="Label60">
<?php
	echo html_oculto('unae18id', $_REQUEST['unae18id']);
?>
</label>
<label class="Label90">
<?php
$et_unae18estado=$ETI['msg_abierto'];
if ($_REQUEST['unae18estado']=='S'){$et_unae18estado=$ETI['msg_cerrado'];}
echo html_oculto('unae18estado', $_REQUEST['unae18estado'], $et_unae18estado);
?>
</label>
<label class="L">
<?php
echo $ETI['unae18titulo'];
?>

<input id="unae18titulo" name="unae18titulo" type="text" value="<?php echo $_REQUEST['unae18titulo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unae18titulo']; ?>"/>
</label>
<?php
// -- Inicia Grupo campos 219 Rangos
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_219'];
?>
</label>
<input id="boculta219" name="boculta219" type="hidden" value="<?php echo $_REQUEST['boculta219']; ?>" />
<?php
if ($_REQUEST['paso']==2){
	if ($_REQUEST['unae18estado']!='S'){
?>
<div class="ir_derecha" style="width:62px;">
<!--
<label class="Label30">
<input id="btexcel219" name="btexcel219" type="button" value="Exportar" class="btMiniExcel" onclick="imprime219();" title="Exportar"/>
</label>
-->
<label class="Label30">
<input id="btexpande219" name="btexpande219" type="button" value="Expandir" class="btMiniExpandir" onclick="expandepanel(219,'block',0);" title="<?php echo $ETI['bt_mostrar']; ?>" style="display:<?php if ($_REQUEST['boculta219']==0){echo 'none'; }else{echo 'block';} ?>;"/>
</label>
<label class="Label30">
<input id="btrecoge219" name="btrecoge219" type="button" value="Recoger" class="btMiniRecoger" onclick="expandepanel(219,'none',1);" title="<?php echo $ETI['bt_ocultar']; ?>" style="display:<?php if ($_REQUEST['boculta219']==0){echo 'block'; }else{echo 'none';} ?>;"/>
</label>
</div>
<div class="salto1px"></div>
<div id="div_p219" style="display:<?php if ($_REQUEST['boculta219']==0){echo 'block'; }else{echo 'none';} ?>;">
<label class="Label130">
<?php
echo $ETI['unae19consec'];
?>
</label>
<label class="Label90"><div id="div_unae19consec">
<?php
if ((int)$_REQUEST['unae19id']==0){
?>
<input id="unae19consec" name="unae19consec" type="text" value="<?php echo $_REQUEST['unae19consec']; ?>" onchange="revisaf219()" class="cuatro"/>
<?php
	}else{
	echo html_oculto('unae19consec', $_REQUEST['unae19consec']);
	}
?>
</div></label>
<label class="Label60">
<?php
echo $ETI['unae19id'];
?>
</label>
<label class="Label60"><div id="div_unae19id">
<?php
	echo html_oculto('unae19id', $_REQUEST['unae19id']);
?>
</div></label>
<label class="L">
<?php
echo $ETI['unae19titulo'];
?>

<input id="unae19titulo" name="unae19titulo" type="text" value="<?php echo $_REQUEST['unae19titulo']; ?>" maxlength="100" class="L" placeholder="<?php echo $ETI['ing_campo'].$ETI['unae19titulo']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['unae19base'];
?>
</label>
<label class="Label130">
<input id="unae19base" name="unae19base" type="text" value="<?php echo $_REQUEST['unae19base']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<label class="Label130">
<?php
echo $ETI['unae19techo'];
?>
</label>
<label class="Label130">
<input id="unae19techo" name="unae19techo" type="text" value="<?php echo $_REQUEST['unae19techo']; ?>" class="diez" maxlength="10" placeholder="<?php echo $ETI['ing_vr']; ?>"/>
</label>
<div class="salto1px"></div>
<label class="Label130">&nbsp;</label>
<label class="Label30">
<input id="bguarda219" name="bguarda219" type="button" value="Guardar" class="btMiniGuardar" onclick="guardaf219()" title="<?php echo $ETI['bt_mini_guardar_219']; ?>"/>
</label>
<label class="Label30">
<input id="blimpia219" name="blimpia219" type="button" value="Limpiar" class="btMiniLimpiar" onclick="limpiaf219()" title="<?php echo $ETI['bt_mini_limpiar_219']; ?>"/>
</label>
<label class="Label30">
<input id="belimina219" name="belimina219" type="button" value="Eliminar" class="btMiniEliminar" onclick="eliminaf219()" title="<?php echo $ETI['bt_mini_eliminar_219']; ?>" style="display:<?php if ((int)$_REQUEST['unae19id']!=0){echo 'block';}else{echo 'none';} ?>;"/>
</label>
<?php
//Este es el cierre del div_p219
?>
<div class="salto1px"></div>
</div>
<?php
		} //Termina el segundo bloque  condicional - bloque editar.
?>
<div class="salto1px"></div>
<div id="div_f219detalle">
<?php
echo $sTabla219;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 219 Rangos
?>
<?php
// -- Inicia Grupo campos 220 Distribucion
?>
<div class="salto1px"></div>
<div class="GrupoCampos">
<label class="TituloGrupo">
<?php
echo $ETI['titulo_220'];
?>
</label>
<input id="boculta220" name="boculta220" type="hidden" value="<?php echo $_REQUEST['boculta220']; ?>" />
<?php
if ($_REQUEST['paso']==2){
?>
<div class="salto1px"></div>
<div id="div_f220detalle">
<?php
echo $sTabla220;
?>
</div>
<?php
	}
?>
<div class="salto1px"></div>
</div>
<?php
// -- Termina Grupo campos 220 Distribucion
?>
<?php
if (false){
	//Ejemplo de boton de ayuda
	//echo html_BotonAyuda('NombreCampo');
	//echo html_DivAyudaLocal('NombreCampo');
	}
if ($bconexpande){
	//Este es el cierre del div_p218
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
<input id="bnombre" name="bnombre" type="text" value="<?php echo $_REQUEST['bnombre']; ?>" onchange="paginarf218()" autocomplete="off"/>
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
<div id="div_f218detalle">
<?php
echo $sTabla218;
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
echo $ETI['msg_unae18consec'];
?>
</label>
<label class="Label90">
<?php
echo '<b>'.$_REQUEST['unae18consec'].'</b>';
?>
</label>
<div class="salto1px"></div>
<label class="Label160">
<?php
echo $ETI['msg_unae18consec_nuevo'];
// onchange="RevisaConsec()"
?>
</label>
<label class="Label90">
<input id="unae18consec_nuevo" name="unae18consec_nuevo" type="text" value="<?php echo $_REQUEST['unae18consec_nuevo']; ?>" class="cuatro"/>
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
<input id="titulo_218" name="titulo_218" type="hidden" value="<?php echo $ETI['titulo_218']; ?>" />
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


<div id="div_sector98" style="display:none">
<div class="titulos">
<div class="titulosD">
<input id="cmdAyuda98" name="cmdAyuda98" type="button" class="btSupAyuda" onclick="muestraayuda(<?php echo $iCodModulo; ?>);" title="<?php echo $ETI['bt_ayuda']; ?>" value="<?php echo $ETI['bt_ayuda']; ?>"/>
</div>
<div class="titulosI">
<?php
echo '<h2>'.$ETI['titulo_218'].'</h2>';
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
if ($_REQUEST['unae18estado']!='S'){
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
<script language="javascript" src="<?php echo $APP->rutacomun; ?>unad_todas.js?ver=8"></script>
<?php
forma_piedepagina();
?>